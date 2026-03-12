<?php
include("../connection.php");

header("Content-Type: application/json");

$user_id   = $_POST['user_id'] ?? null;
$method_id = $_POST['method_id'] ?? null;
$amount    = $_POST['amount'] ?? null;

if (!$user_id || !$method_id || !$amount) {
    echo json_encode(["status" => false, "error" => "Missing fields"]);
    exit;
}

$reference = uniqid("DEP_");
$receipt_name = null;

if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === 0) {

    $upload_dir = "../../uploads/receipts/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $receipt_name = time() . "_" . basename($_FILES['receipt']['name']);

    if (!move_uploaded_file($_FILES['receipt']['tmp_name'], $upload_dir . $receipt_name)) {
        echo json_encode(["status" => false, "error" => "File upload failed"]);
        exit;
    }
}

$stmt = $connection->prepare("
    INSERT INTO deposit (user_id, type_id, amount, receipt, status, reference)
    VALUES (?, ?, ?, ?, 'pending', ?)
");

if (!$stmt) {
    echo json_encode([
        "status" => false,
        "error" => $connection->error
    ]);
    exit;
}

$stmt->bind_param("iidss", $user_id, $method_id, $amount, $receipt_name, $reference);

if (!$stmt->execute()) {
    echo json_encode([
        "status" => false,
        "error" => $stmt->error
    ]);
    exit;
}

echo json_encode([
    "status" => true,
    "reference" => $reference
]);