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
     * Send SMS when admin approves a dossier.
     * Called from ProsController::approve()
     */
    public function sendApproved(string $phone, string $firstName): bool
    {
        $message = "Bonjour {$firstName}, votre dossier AssureX a été approuvé ✅. "
                 . "Félicitations ! Un agent vous contactera pour finaliser votre contrat.";

        return $this->send($phone, $message);
    }

    /**
     * Send SMS when admin rejects a dossier.
     * Called from ProsController::reject()
     */
    public function sendRejected(string $phone, string $firstName, ?string $reason = null): bool
    {
        $message = "Bonjour {$firstName}, votre dossier AssureX nécessite des modifications ❌.";

        if ($reason) {
            $message .= " Motif : {$reason}.";
        }

        $message .= " Un agent vous contactera prochainement.";

        return $this->send($phone, $message);
    }

    /**
     * Core send method — normalizes Moroccan numbers and calls Twilio.
     */
    private function send(string $to, string $message): bool
    {
        $normalized = $this->normalizePhone($to);

        if (!$normalized) {
            $this->logger->warning("SmsService: could not normalize phone number '{$to}', SMS skipped.");
            return false;
        }

        try {
            $this->client->messages->create($normalized, [
                'from' => $this->from,
                'body' => $message,
            ]);

            $this->logger->info("SmsService: SMS sent successfully to {$normalized}");
            return true;

        } catch (\Exception $e) {
            // Log the error but never crash the app — SMS failure is non-blocking
            $this->logger->error("SmsService: Twilio error — " . $e->getMessage());
            return false;
        }
    }

    /**
     * Normalize Moroccan phone numbers to E.164 format (+212XXXXXXXXX)
     *
     * Handles:
     *   0600000000   → +212600000000
     *   0700000000   → +212700000000
     *   212600000000 → +212600000000
     *   +212600000000 → +212600000000 (already fine)
     */
    private function normalizePhone(string $phone): ?string
    {
        // Strip all spaces, dashes, dots
        $phone = preg_replace('/[\s\-\.]/', '', $phone);

        // Already in E.164 format
        if (preg_match('/^\+212[67]\d{8}$/', $phone)) {
            return $phone;
        }

        // Local Moroccan format: 06XXXXXXXX or 07XXXXXXXX
        if (preg_match('/^0[67]\d{8}$/', $phone)) {
            return '+212' . substr($phone, 1);
        }

        // Without + but with country code: 2126XXXXXXXX
        if (preg_match('/^212[67]\d{8}$/', $phone)) {
            return '+' . $phone;
        }

        return null; // unrecognized — skip safely
    }
}