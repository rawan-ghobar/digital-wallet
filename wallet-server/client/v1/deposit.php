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
    $walletId  = intval($data['wallet_id']);
    $walletPin = $data['wallet_pin'];
    $amount    = floatval($data['amount']);

    $transaction = new Transaction($mysqli);
    $result = $transaction->deposit($walletId, $walletPin, $amount);
    echo json_encode($result);
    exit;
?>