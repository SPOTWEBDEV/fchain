<?php

// ================================
// CONFIG
// ================================
$API_KEY = "07d5034dcbc38a18c815b7416251141f4fd15723";
$USER_ID = "be31af02-29a8-4c7e-a263-3cd72aefb4fb";
$URL     = "https://api.cryptomus.com/v1/payment/services";


// ================================
// REQUEST BODY (Empty in this case)
// ================================
$data = []; // because -d '{}' in curl

$jsonData = json_encode($data);

// ================================
// GENERATE SIGNATURE
// ================================
$sign = md5(base64_encode($jsonData) . $API_KEY);


// ================================
// CURL REQUEST
// ================================
$ch = curl_init($URL);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "userId: $USER_ID",
    "sign: $sign",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Curl Error: " . curl_error($ch);
} else {
    echo "Response: " . $response;
}

curl_close($ch);