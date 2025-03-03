<?php
class Transaction
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function createTransaction($walletId, $amount, $type)
{
    if (!in_array($type, ['deposit', 'withdraw', 'transfer'])) {
        response(false, "Invalid transaction type");
        return;
    }

    $sql = "SELECT user_id FROM wallets WHERE id = ?";
    $stmt = $this->mysqli->prepare($sql);
    $stmt->bind_param("i", $walletId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        response(false, "Wallet not found");
        return;
    }

    $wallet = $result->fetch_assoc();
    $userId = $wallet["user_id"];

    $sql = "INSERT INTO transactions (wallet_id, amount, transaction_type) VALUES (?, ?, ?)";
    $stmt = $this->mysqli->prepare($sql);
    $stmt->bind_param("ids", $walletId, $amount, $type);
    $stmt->execute();

    $notification = new Notification($this->mysqli);
    $notification->createNotification($userId, "New $type transaction of $$amount");

    response(true, "Transaction recorded successfully");
}


    public function viewTransaction($walletId)
    {
        $sql = "SELECT id, amount, transaction_type, created_at FROM transactions WHERE wallet_id = ? ORDER BY created_at DESC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $walletId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            response(false, "No transactions found");
        }

        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }

        response(true, ["transactions" => $transactions]);
    }
}
?>
