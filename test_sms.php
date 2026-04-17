<?php

$apiKey = 'b3a351e11a90f146aa241f1186d25ca21be4a8ad';
$from = '+33745894920';
$to = '+33755514837'; // The destination number
$message = "Test AssureX: Verification de la ligne Ringover. ✅";

// Try this specific combination:
$url = 'https://public-api.ringover.com/v2/push/sms/v1 '; 

// Update your payload to this exact format:
$payload = json_encode([
    'phone_number' => '+33745894920', // Your Ringover Pro number
    'to'           => '+33755514837', // The client's number
    'content'      => "Bonjour, votre dossier AssureX a été approuvé. ✅"
]);

echo "--- Sending SMS ---\n";
echo "From: $from\nTo: $to\n\n";

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => [
        'Authorization: ' . $apiKey,
        'Content-Type: application/json',
    ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    echo "CURL ERROR: $curlError\n";
} else {
    echo "HTTP CODE: $httpCode\n";
    echo "RESPONSE: $response\n";
}

if ($httpCode >= 200 && $httpCode < 300) {
    echo "\nSUCCESS: Check the phone +33 7 55 51 48 37!\n";
} else {
    echo "\nFAILED: Check your Ringover dashboard API permissions.\n";
}

