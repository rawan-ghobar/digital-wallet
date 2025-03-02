<?php
session_start();
include(__DIR__ . "/../../connection/connection.php");
include(__DIR__ . "/../../models/transaction.php");
include_once(__DIR__ . "/../../utils/utils.php");

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    response(false, "Invalid request method");
    exit;
}

if (!isset($_GET["wallet_id"])) {
    response(false, "Wallet ID is required");
    exit;
}

$walletId = $_GET["wallet_id"];

$transaction = new Transaction($mysqli);
$result = $transaction->viewTransaction($walletId);

echo json_encode($result);
?>
