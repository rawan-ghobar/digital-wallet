<?php

include_once(__DIR__ . "/../../connection/connection.php");
include_once(__DIR__ . "/../../utils/utils.php");
include_once(__DIR__ . "/../../models/user.php");

header("Content-Type: application/json");


if($_SERVER["REQUEST_METHOD"] !== "POST") 
{
    response(false, "Invalid request method");
    exit;
}

$data = getJsonRequestData() ;

if(!isset($data["wallet_name"]))
{
    response(false,"Please enter your first name");
}
else if (!isset($data["wallet_pin"]))
{
    response(false,"Please enter your last name");
}


$wallet_name= $data["wallet_name"];
$wallet_pin= $data["walet_pin"];

$user = new Wallet($mysqli);

$result = $wallet->createWallet($wallet_name, $wallet_pin);

echo json_encode($result);

?>