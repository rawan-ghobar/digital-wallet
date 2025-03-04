<?php
header('Content-Type: application/json');
include(__DIR__ . "/../../connection/connection.php");
include(__DIR__ . "/../../models/transaction.php");
include_once(__DIR__ . "/../../utils/utils.php");

if($_SERVER["REQUEST_METHOD"] !== "POST") 
{
    response(false, "Invalid request method");
    exit;
}
    $data = getJsonRequestData() ;
    $walletId  = $data['wallet_id']  ?? null;
    $walletPin = $data['wallet_pin'] ?? null;
    $amount    = floatval($data['amount'] ?? 0);

    $transaction = new Transaction($mysqli);
    $result = $transaction->deposit($walletId, $walletPin, $amount);
    json_encode($result);
    exit;
?>