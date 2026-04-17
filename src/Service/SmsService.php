<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class SmsService
{
    private string $apiKey;
    private string $from;
    private LoggerInterface $logger;

    /**
     * Updated to the working Push API endpoint
     */
    private const API_URL = 'https://public-api.ringover.com/v2/push/sms';

    public function __construct(
        string $ringoverApiKey,
        string $ringoverFrom,
        LoggerInterface $logger
    ) {
        $this->apiKey = $ringoverApiKey;
        $this->from   = $ringoverFrom;
        $this->logger = $logger;
    }

    public function sendApproved(string $phone, string $firstName): bool
    {
        $message = "Bonjour {$firstName}, votre dossier AssureX a ete approuve. ✅";
        return $this->send($phone, $message);
    }

    public function sendRejected(string $phone, string $firstName, ?string $reason = null): bool
    {
        $message = "Bonjour {$firstName}, votre dossier AssureX necessite des modifications. ";
        if ($reason) {
            $message .= "Motif : {$reason}. ";
        }
        $message .= "Un agent vous contactera prochainement. - AssureX";
        return $this->send($phone, $message);
    }

    private function send(string $to, string $message): bool
    {
        $normalized = $this->normalizePhone($to);
        if (!$normalized) {
            $this->logger->error("SmsService: Invalid phone format for $to");
            return false;
        }

        // Using the exact payload structure from your successful curl
        $payload = json_encode([
            'from_number' => $this->from,
            'to_number'   => $normalized,
            'content'     => $message
        ]);

        $ch = curl_init(self::API_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Authorization: ' . $this->apiKey,
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        // Log the error instead of dying to keep the app running
        $this->logger->error("RINGOVER API ERROR: Code $httpCode | Response: $response");
        
        return false;
    }

    /**
     * Normalizes phone numbers to E.164 (+33...)
     */
    private function normalizePhone(string $phone): ?string
    {
        // Remove spaces, dots, dashes, and parentheses
        $phone = preg_replace('/[\s\-\.\(\)]/', '', $phone);
        
        // Handle French local format (06...)
        if (preg_match('/^0[67]\d{8}$/', $phone)) {
            return '+33' . substr($phone, 1);
        }
        
        // Handle French international without + (336...)
        if (preg_match('/^33[67]\d{8}$/', $phone)) {
            return '+' . $phone;
        }

        // Already correct (+336...)
        if (preg_match('/^\+33[67]\d{8}$/', $phone)) {
            return $phone;
        }

        // Return the number with a plus if it looks like it's already international
        if (strlen($phone) > 8 && !str_starts_with($phone, '+')) {
            return '+' . $phone;
        }

        return str_starts_with($phone, '+') ? $phone : null;
    }
}