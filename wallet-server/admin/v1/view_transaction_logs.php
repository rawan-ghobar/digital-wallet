<?php
session_start();
include(__DIR__ . "/../../connection/connection.php");
include_once(__DIR__ . "/../../utils/utils.php");

header("Content-Type: application/json");

if (!isset($_SESSION["admin_id"])) {
    response(false, "Unauthorized. Admin access only");
    exit;
}

$userId = isset($_GET["user_id"]) ? $_GET["user_id"] : null;
$walletId = isset($_GET["wallet_id"]) ? $_GET["wallet_id"] : null;

$sql = "SELECT transactions.id, transactions.amount, transactions.transaction_type, transactions.created_at, 
               wallets.wallet_name, users.email AS user_email
        FROM transactions
        INNER JOIN wallets ON transactions.wallet_id = wallets.id
        INNER JOIN users ON wallets.user_id = users.id";

if ($userId) {
    $sql .= " WHERE wallets.user_id = $userId";
} elseif ($walletId) {
    $sql .= " WHERE transactions.wallet_id = $walletId";
}

$sql .= " ORDER BY transactions.created_at DESC";

$result = $mysqli->query($sql);
$transactions = [];

while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

response(true, ["transactions" => $transactions]);
?>
