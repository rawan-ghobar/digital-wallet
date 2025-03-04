<?php

class Transaction
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function deposit($walletId, $walletPin, $amount)
    {
        if (!$walletId || !$walletPin || !$amount) {
            response(false,"Please fill out all required fields");
        }

        $stmt = $this->mysqli->prepare("SELECT balance FROM wallets WHERE id = ? AND pin = ?");
        $stmt->bind_param("is", $walletId, $walletPin);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            response(false,"Invalid wallet id / pin");
            exit;
        }

        $sql = "UPDATE wallets 
                   SET wallet_balance = wallet_balance + ?
                 WHERE id = ?
                   AND pin = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("dis", $amount, $walletId, $walletPin);
        $stmt->execute();

        $transactionResult = $this->createTransaction($walletId, $amount, 'deposit');
        if (!$transactionResult['success']) {
            return $transactionResult;
        }

        response(true,"Deposit successful");
    }

    public function withdraw($walletId, $walletPin, $amount)
    {
        if (!$walletId || !$walletPin || !$amount) {
            response(false,"Please fill out all required fields");
        }

        $stmt = $this->mysqli->prepare("SELECT wallet_balance FROM wallets WHERE id = ? AND pin = ?");
        $stmt->bind_param("is", $walletId, $walletPin);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            response(false,"Invalid wallet id / pin");
        }

        $wallet = $result->fetch_assoc();
        $currentBalance = (float)$wallet['wallet_balance'];

        if ($currentBalance < $amount) {
            response(false,"Insufficient funds");
            exit;
        }

        $newBalance = $currentBalance - $amount;
        $stmt = $this->mysqli->prepare("UPDATE wallets SET wallet_balance = ? WHERE id = ? AND pin = ?");
        $stmt->bind_param("dis", $newBalance, $walletId, $walletPin);
        $stmt->execute();

        $transactionResult = $this->createTransaction($walletId, $amount, 'withdraw');
        if (!$transactionResult['success']) {
            return $transactionResult;
        }

        response(true,"Withdrawal successful");
    }

    public function p2p($senderWalletId, $senderPin, $receiverWalletId, $amount)
    {
        if (!$senderWalletId || !$senderPin || !$receiverWalletId || !$amount) {
            response(false,"Please fill out all fields");
        }

        $stmt = $this->mysqli->prepare("SELECT wallet_balance FROM wallets WHERE id = ? AND pin = ?");
        $stmt->bind_param("is", $senderWalletId, $senderPin);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            response(false,"Invalid wallet id / pin");
        }

        $senderWallet = $result->fetch_assoc();
        $senderBalance = (float)$senderWallet['wallet_balance'];

        if ($senderBalance < $amount) {
            response(false,"Insufficient funds");
            exit;
        }

        $newSenderBalance = $senderBalance - $amount;
        $stmt = $this->mysqli->prepare("UPDATE wallets SET wallet_balance = ? WHERE id = ?");
        $stmt->bind_param("di", $newSenderBalance, $senderWalletId);
        $stmt->execute();

        $stmt = $this->mysqli->prepare("SELECT wallet_balance FROM wallets WHERE id = ?");
        $stmt->bind_param("i", $receiverWalletId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            response(false,"Invalidreciever wallet id / pin");
        }

        $receiverWallet = $result->fetch_assoc();
        $receiverBalance = (float)$receiverWallet['wallet_balance'];

        $newReceiverBalance = $receiverBalance + $amount;
        $stmt = $this->mysqli->prepare("UPDATE wallets SET wallet_balance = ? WHERE id = ?");
        $stmt->bind_param("di", $newReceiverBalance, $receiverWalletId);
        $stmt->execute();

        $senderTxn = $this->createTransaction($senderWalletId, $amount, 'transfer');
        if (!$senderTxn['success']) {
            return $senderTxn; 
        }

        $receiverTxn = $this->createTransaction($receiverWalletId, $amount, 'transfer');
        if (!$receiverTxn['success']) {
            return $receiverTxn;
        }

        response(true,"Transfer successful!");
    }

    public function createTransaction($walletId, $amount, $type)
    {
        if (!in_array($type, ['deposit', 'withdraw', 'transfer'])) {
            response(false,"Invalid transaction type");
        }

        $sql = "SELECT user_id FROM wallets WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $walletId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            response(false,"Wallet not found");
        }

        $sql = "INSERT INTO transactions (wallet_id, amount, transaction_type) 
                VALUES (?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ids", $walletId, $amount, $type);
        $stmt->execute();

        response(true,"Transaction recorded successfully");
    }

    public function viewTransaction($walletId)
    {
        $sql = "SELECT id, amount, transaction_type, created_at 
                FROM transactions 
                WHERE wallet_id = ? 
                ORDER BY created_at DESC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $walletId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            response(false,"No transactions found");
        }

        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }

        return ['success' => true, 'transactions' => $transactions];
    }
}
?>
