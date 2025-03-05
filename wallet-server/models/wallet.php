<?php
include_once(__DIR__ . "/../connection/connection.php");
include_once(__DIR__ . "/../utils/utils.php");


class Wallet
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }
    
    public function createWallet($userId, $wallet_name, $wallet_pin)
{
    if (!ctype_digit($wallet_pin) || strlen($wallet_pin) !== 4) {
        return response(false, "PIN must be exactly 4 digits");
    }
    
    $sql = "SELECT wallet_name FROM wallets WHERE user_id = ? AND wallet_name = ?";
    $stmt = $this->mysqli->prepare($sql);
    $stmt->bind_param("is", $userId, $wallet_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        response(false, "Wallet already exists, please choose a new name");
        return;
    }
    
    $sql = "INSERT INTO wallets (wallet_name, wallet_pin, user_id) VALUES (?, ?, ?)";
    $stmt = $this->mysqli->prepare($sql);
    $stmt->bind_param("ssi", $wallet_name, $wallet_pin, $userId);

    if ($stmt->execute()) {
        // Get the new wallet id from the insert
        $newWalletId = $this->mysqli->insert_id;
        // Optionally, retrieve wallet_balance (assumed default is 0)
        $sql2 = "SELECT wallet_balance FROM wallets WHERE id = ?";
        $stmt2 = $this->mysqli->prepare($sql2);
        $stmt2->bind_param("i", $newWalletId);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $row = $result2->fetch_assoc();
        $wallet_balance = $row['wallet_balance'];
        
        // Return an object with the wallet details
        response(true, [
            "wallet_id" => $newWalletId, 
            "wallet_balance" => $wallet_balance, 
            "message" => "Wallet added successfully"
        ]);
    }
    else
    {
        response(false, "Error: " . $this->mysqli->error);
    }
}


    public function readWallet($userId)
{
    $sql = "SELECT id AS wallet_id, wallet_name, wallet_balance FROM wallets WHERE user_id = ?";
    $stmt = $this->mysqli->prepare($sql);

    if (!$stmt) {
        response(false, "Database error: " . $this->mysqli->error);
        return;
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        response(false, "No wallets found for this user");
    }

    $wallets = [];
    while ($row = $result->fetch_assoc()) {
        $wallets[] = $row;
    }

    response(true, ["wallets" => $wallets]);
}

    public function updatewallet($walletId, $wallet_name)
    {

        $sql = "UPDATE wallets SET wallet_name = ?, WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("si", $wallet_name, $walletId);

        if ($stmt->execute())
        {
            response(false, "Wallet name updated successfully");
            return;
        } else 
        {
            response(false, "Error:" . $this->mysqli->error);
            return;
        }
    }
    
    public function deleteWallet($walletId)
    {
        $sql = "DELETE FROM wallets WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $walletId);

        if ($stmt->execute()) 
        {
            response(false, "Wallet deleted successfully");
        } else 
        {
            response(false, "Error" . $this->mysqli->error);
            return;
        }
    }
}
?>
 