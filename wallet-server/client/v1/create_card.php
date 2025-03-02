<?php
session_start();
include(__DIR__ . "/../../connection/connection.php");
include(__DIR__ . "/../../models/card.php");
include_once(__DIR__ . "/../../utils/utils.php");

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    response(false, "Unauthorized. Please log in");
    exit;
}

$userId = $_SESSION["user_id"];

if (!isset($_GET["wallet_id"])) {
    response(false, "Wallet ID is required");
    exit;
}

$walletId = $_GET["wallet_id"];

$card = new Card($mysqli);
$result = $card->createCard($userId, $walletId);

echo json_encode($result);
?>
