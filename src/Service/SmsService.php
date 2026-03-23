<?php

namespace App\Service;

use Twilio\Rest\Client;
use Psr\Log\LoggerInterface;

class SmsService
{
    private Client $client;
    private string $from;
    private LoggerInterface $logger;

    public function __construct(
        string $twilioSid,
        string $twilioToken,
        string $twilioFrom,
        LoggerInterface $logger
    ) {
        $this->client = new Client($twilioSid, $twilioToken);
        $this->from   = $twilioFrom;
        $this->logger = $logger;
    }

    /**
     * Send WhatsApp message when admin approves a dossier.
     */
    public function sendApproved(string $phone, string $firstName): bool
    {
        $message = "Bonjour {$firstName}, votre dossier AssureX a ete approuve. ";

        return $this->send($phone, $message);
    }

    /**
     * Send WhatsApp message when admin rejects a dossier.
     */
    public function sendRejected(string $phone, string $firstName, ?string $reason = null): bool
    {
        $message = "Bonjour {$firstName}, votre dossier AssureX necessite des modifications. ";

        if ($reason) {
            $message .= "Motif : {$reason}. ";
        }

        $message .= "Un agent vous contactera prochainement. - AssureX";

        return $this->send($phone, $message);
    }

    /**
     * Core send — uses Twilio WhatsApp sandbox.
     */
    private function send(string $to, string $message): bool
    {
        $normalized = $this->normalizePhone($to);

        if (!$normalized) {
            $this->logger->warning("SmsService: could not normalize phone '{$to}', skipped.");
            return false;
        }

        try {
            $this->client->messages->create(
                'whatsapp:' . $normalized,        // TO
                [
                    'from' => 'whatsapp:+14155238886', // Twilio sandbox FROM
                    'body' => $message,
                ]
            );

            $this->logger->info("WhatsApp sent to {$normalized}");
            return true;

        } catch (\Exception $e) {
            $this->logger->error("WhatsApp failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Normalize Moroccan numbers to E.164 (+212XXXXXXXXX)
     */
    private function normalizePhone(string $phone): ?string
    {
        $phone = preg_replace('/[\s\-\.]/', '', $phone);

        if (preg_match('/^\+212[67]\d{8}$/', $phone)) return $phone;
        if (preg_match('/^0[67]\d{8}$/', $phone))     return '+212' . substr($phone, 1);
        if (preg_match('/^212[67]\d{8}$/', $phone))   return '+' . $phone;

        return null;
    }
}