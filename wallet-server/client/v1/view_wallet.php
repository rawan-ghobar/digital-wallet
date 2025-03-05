<?php
include_once(__DIR__ . "/../../connection/connection.php");
include_once(__DIR__ . "/../../utils/utils.php");
include_once(__DIR__ . "/../../utils/jwt-loader.php");
include_once(__DIR__ . "/../../models/wallet.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    response(false, "Invalid request method");
    exit;
}

$userId = getUserIdFromToken();

if (!$userId) {
    response(false, "User not authenticated");
    exit;
}

$wallet = new Wallet($mysqli);
$wallet->readWallet($userId);
?>
