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
        if (!ctype_digit($walletPin) || strlen($walletPin) !== 4) {
            return response(false, "pin must be exactly 4 digits");
        }
        
        $hashed_pin = password_hash($wallet_pin, PASSWORD_DEFAULT);

        $sql = "INSERT INTO wallets (wallet_name, wallet_pin) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ss", $wallet_name, $hashed_password);

        if ($stmt->execute())
        {
            response(true, "Wallet added successfully");
        }
        else
        {
            response(false, "Error: " . $this->mysqli->error);
        }
    }

    public function readWallet($walletId)
    {
        $sql = "SELECT wallet_name, wallet_balance FROM wallets WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $walletId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0)
        {
            response(false, "Wallet not found");
            return;
        }

        $walletData = $result->fetch_assoc();
        response(true, ["wallet" => $walletData]);
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
 