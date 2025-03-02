<?php
include_once(__DIR__ . "/../connection/connection.php");
include_once(__DIR__ . "/../utils/utils.php");


class Card
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }
   
    public function createCard($userId, $walletId)
    {
        $checkSql = "SELECT is_verified FROM users WHERE id = ?";
        $checkStmt = $this->mysqli->prepare($checkSql);
        $checkStmt->bind_param("i", $userId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
    
        if ($checkResult->num_rows == 0) {
            response(false, "User not found");
        }
    
        $user = $checkResult->fetch_assoc();
        if ($user["is_verified"] == 0) {
            response(false, "You must verify your account before creating a card");
        }
        
        do {
            $cardNb = str_pad(mt_rand(1000000000000000, 9999999999999999), 16, '0', STR_PAD_LEFT);
            $checkSql = "SELECT id FROM cards WHERE card_nb = ?";
            $checkStmt = $this->mysqli->prepare($checkSql);
            $checkStmt->bind_param("s", $cardNb);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
        } while ($checkResult->num_rows > 0);

        $cvv = mt_rand(100, 999);
        $expiryDate = date('Y-m-d', strtotime('+3 years'));

        $sql = "INSERT INTO cards (card_nb, expiries_at, cvv, wallet_id, user_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssiii", $cardNb, $expiryDate, $cvv, $walletId, $userId);

        if ($stmt->execute()) {
            response(true, ["message" => "Card created successfully", "card_nb" => $cardNb]);
        } else {
            response(false, "Error" .$this->mysqli->error );
        }
    }

    public function viewCard($walletId)
    {
        $sql = "SELECT id, card_nb, expiries_at, cvv FROM cards WHERE wallet_id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $walletId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0)
        {
            response(false,"No card found");
        }

        return ["success" => true, "card" => $result->fetch_assoc()];
    }

    public function deleteCard($walletId)
    {
        $sql = "DELETE FROM cards WHERE wallet_id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $walletId);

        if ($stmt->execute())
        {
            return response(true,"card deleted successfully");
        }else
        {
            return response(false,"Error: " . $this->mysqli->error);
        }
    }
}


?>
 