<?php
include_once(__DIR__ . "/../../connection/connection.php");
include_once(__DIR__ . "/../../utils/utils.php");
include_once(__DIR__ . "/../../utils/jwt-loader.php");
include_once(__DIR__ . "/../../models/wallet.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Content-Type: application/json");


if($_SERVER["REQUEST_METHOD"] !== "POST") 
{
    response(false, "Invalid request method");
    exit;
}

$data = getJsonRequestData() ;

if(!isset($data["wallet_name"]))
{
    response(false,"Please enter your wallet name");
    return;
}
else if (!isset($data["wallet_pin"]))
{
    response(false,"Please enter a pin to secure your wallet");
    return;
}


$wallet_name= $data["wallet_name"];
$wallet_pin= $data["wallet_pin"];
$userId = getUserIdFromToken();
error_log("User ID from token: " . $userId);


$wallet = new Wallet($mysqli);

$result = $wallet->createWallet($userId, $wallet_name, $wallet_pin);

echo json_encode($result);
exit;

?>