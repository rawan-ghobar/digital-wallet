<?php
session_start();
include(__DIR__ . "/../../connection/connection.php");
include(__DIR__ . "/../../models/wallet.php");
include_once(__DIR__ . "/../../utils/utils.php");

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    response(false, "Unauthorized. Please log in");
    exit;
}

$userId = $_SESSION["user_id"];

$wallet = new Wallet($mysqli);
$result = $wallet->readWallet($userId);

echo json_encode($result);
?>
