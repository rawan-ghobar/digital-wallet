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
        if (empty($walletId) || empty($walletPin) || empty($amount)) {
            response(false, "Please fill out all required fields");
            exit;
        }
        
        $stmt = $this->mysqli->prepare("SELECT * FROM wallets WHERE id = ? AND wallet_pin = ?");
        
        if (!$stmt) {
            response(false, "SQL Error (SELECT): " . $this->mysqli->error);
            exit;
        }

        $stmt->bind_param("is", $walletId, $walletPin);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            response(false, "Invalid wallet ID or PIN");
            exit;
        }

        $wallet = $result->fetch_assoc();
        $currentBalance = (float)$wallet['wallet_balance'];

        $sql = "UPDATE wallets SET wallet_balance = wallet_balance + ? WHERE id = ? AND wallet_pin = ?";
        $stmt = $this->mysqli->prepare($sql);

        if (!$stmt) {
            response(false, "SQL Error (UPDATE): " . $this->mysqli->error);
            exit;
        }

        $stmt->bind_param("dis", $amount, $walletId, $walletPin);
        
        if (!$stmt->execute()) {
            response(false, "Deposit failed: " . $stmt->error);
            exit;
        }

        response(true, "Deposit successful");
        exit;
    }


    public function withdraw($walletId, $walletPin, $amount)
    {
        if (empty($walletId) || empty($walletPin) || empty($amount)) {
            response(false, "Please fill out all required fields");
            exit;
        }

        $stmt = $this->mysqli->prepare("SELECT wallet_balance FROM wallets WHERE id = ? AND wallet_pin = ?");
        $stmt->bind_param("is", $walletId, $walletPin);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0)
        {
            response(false, "Invalid wallet ID or PIN");
            exit;
        }
        $wallet = $result->fetch_assoc();
        $currentBalance = (float)$wallet['wallet_balance'];

        if ($currentBalance < $amount) {
            response(false,"Insufficient funds");
            exit;
        }

        $newBalance = $currentBalance - $amount;
        $stmt = $this->mysqli->prepare("UPDATE wallets SET wallet_balance = ? WHERE id = ? AND wallet_pin = ?");
        $stmt->bind_param("dis", $newBalance, $walletId, $walletPin);
        $stmt->execute();

        // $transactionResult = $this->createTransaction($walletId, $amount, 'withdraw');
        // if (!$transactionResult['success']) {
        //     return $transactionResult;
        // }

        response(true,"Withdrawal successful");
    }

    public function p2p($senderWalletId, $senderPin, $receiverWalletId, $amount)
{
    
    if (empty($senderWalletId) || empty($senderPin) || empty($receiverWalletId) || empty($amount)) {
        response(false, "Please fill out all fields");
        exit;
    }
    
    $stmt = $this->mysqli->prepare("SELECT wallet_balance FROM wallets WHERE id = ? AND wallet_pin = ?");
    if (!$stmt) {
        response(false, "Error preparing sender query: " . $this->mysqli->error);
        exit;
    }
    $stmt->bind_param("is", $senderWalletId, $senderPin);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        response(false, "Invalid sender wallet ID or PIN");
        exit;
    }
    $senderWallet = $result->fetch_assoc();
    $senderBalance = (float)$senderWallet['wallet_balance'];
    $stmt->close();

    if ($senderBalance < $amount) {
        response(false, "Insufficient funds");
        exit;
    }

    $newSenderBalance = $senderBalance - $amount;
    $stmt = $this->mysqli->prepare("UPDATE wallets SET wallet_balance = ? WHERE id = ?");
    if (!$stmt) {
        response(false, "Error preparing sender update: " . $this->mysqli->error);
        exit;
    }
    $stmt->bind_param("di", $newSenderBalance, $senderWalletId);
    if (!$stmt->execute()) {
        response(false, "Error updating sender wallet: " . $stmt->error);
        exit;
    }
    $stmt->close();

    $stmt = $this->mysqli->prepare("SELECT wallet_balance FROM wallets WHERE id = ?");
    if (!$stmt) {
        response(false, "Error preparing receiver query: " . $this->mysqli->error);
        exit;
    }
    $stmt->bind_param("i", $receiverWalletId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        response(false, "Invalid receiver wallet ID");
        exit;
    }
    $receiverWallet = $result->fetch_assoc();
    $receiverBalance = (float)$receiverWallet['wallet_balance'];
    $stmt->close();

    $newReceiverBalance = $receiverBalance + $amount;
    $stmt = $this->mysqli->prepare("UPDATE wallets SET wallet_balance = ? WHERE id = ?");
    if (!$stmt) {
        response(false, "Error preparing receiver update: " . $this->mysqli->error);
        exit;
    }
    $stmt->bind_param("di", $newReceiverBalance, $receiverWalletId);
    if (!$stmt->execute()) {
        response(false, "Error updating receiver wallet: " . $stmt->error);
        exit;
    }
    $stmt->close();

    response(true, "Transfer successful!");
    exit;
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
