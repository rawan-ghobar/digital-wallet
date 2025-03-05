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
            return response(false, "pin must be exactly 4 digits");
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
        
        $hashed_password = password_hash($wallet_pin, PASSWORD_DEFAULT);

        $sql = "INSERT INTO wallets (wallet_name, wallet_pin, user_id) VALUES (?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssi", $wallet_name, $hashed_password, $userId);

        if ($stmt->execute())
        {
            response(true, "Wallet added successfully");
            /*$log = new ActivityLog($mysqli);
            $log->logActivity($_SESSION["user_id"], null, "User created a wallet: $wallet_name");*/

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
 