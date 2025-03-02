<?php
session_start();
include(__DIR__ . "/../../connection/connection.php");
include(__DIR__ . "/../../models/Transaction.php");
include_once(__DIR__ . "/../../utils/utils.php");
header("Content-Type: application/json");

// ✅ Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    response(false, "Unauthorized. Please log in");
    exit;
}

$userId = $_SESSION["user_id"];

$sql = "SELECT transactions.id, transactions.amount, transactions.transaction_type, transactions.created_at, wallets.wallet_name
        FROM transactions
        INNER JOIN wallets ON transactions.wallet_id = wallets.id
        WHERE wallets.user_id = ?
        ORDER BY transactions.created_at DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

response(true, ["history" => $transactions]);
?>
