<?php
include('../../server/connection.php');
include('../../server/auth/client.php');

header('Content-Type: application/json');

// Get JSON input
$body = json_decode(file_get_contents("php://input"), true);
$amount  = floatval($body['amount'] ?? 0);
$user_id = intval($body['user_id'] ?? 0);

if ($amount <= 0) {
    echo json_encode(["error" => "Invalid amount"]);
    exit;
}

if ($user_id <= 0) {
    echo json_encode(["error" => "Invalid user"]);
    exit;
}

/* -------------------------
   FETCH USER DETAILS
-------------------------- */
$stmt = $connection->prepare("SELECT email, phone, full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$userResult = $stmt->get_result();

if ($userResult->num_rows === 0) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

$user = $userResult->fetch_assoc();

/* -------------------------
   ETEGRAM CONFIG
-------------------------- */


// Generate unique reference
$reference = uniqid("DEP_");

/* -------------------------
   INIT PAYMENT
-------------------------- */
$url = "https://api-checkout.etegram.com/api/transaction/initialize/{$projectID}";

$callback_url =  $domain .  "user/deposit/status"; // your server endpoint

$data = json_encode([
    "amount"       => $amount,
    "email"        => $user['email'],
    "phone"        => $user['phone'],
    "firstname"    => $user['full_name'],
    "reference"    => $reference,
    "callback_url" => $callback_url, 
    "currency"     => 'USD'
]);

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $publicKey"
    ]
]);

$result = curl_exec($ch);
curl_close($ch);

$res = json_decode($result, true);

/* -------------------------
   SAVE TRANSACTION
-------------------------- */
if (!empty($res['data']['authorization_url'])) {

    $accessCode = $res['data']['access_code'] ?? null;

    $stmt = $connection->prepare(
        "INSERT INTO deposit (user_id, reference, access_code, amount, status)
         VALUES (?, ?, ?, ?, 'pending')"
    );
    $stmt->bind_param("issd", $user_id, $reference, $accessCode, $amount);
    $stmt->execute();

    echo json_encode([
        "status" => true,
        "authorization_url" => $res['data']['authorization_url']
    ]);
} else {
    echo json_encode([
        "error" => "Failed to initialize payment",
        "response" => $res
    ]);
}


?>