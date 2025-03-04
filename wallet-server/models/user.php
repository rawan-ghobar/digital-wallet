<?php
include_once(__DIR__ . "/../connection/connection.php");
include_once(__DIR__ . "/../utils/utils.php");
include_once(__DIR__ . '/activitylog.php');



class User
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }
    
    public function createUser($email, $fname, $lname, $phonenb, $password)
    {
        $sql = "SELECT id FROM users WHERE email = ? OR phonenb = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ss", $email, $phonenb);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0)
        {
            response(false, "User already exists, please login");
            return;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (email, fname, lname, phonenb, user_password, is_verified) VALUES (?, ?, ?, ?, ?, 0)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("sssss", $email, $fname, $lname, $phonenb, $hashed_password);

        if ($stmt->execute())
        {
            response(true, "User registered successfully");
            
        }
        else
        {
            response(false, "Error: " . $this->mysqli->error);
        }
    }

    public function readUser($userId)
    {
        $sql = "SELECT id, email, fname, lname, phonenb, is_verified FROM users WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0)
        {
            response(false, "User not found");
            return;
        }

        $userData = $result->fetch_assoc();
        response(true, ["user" => $userData]);
    }
    public function updateUser($userId, $fname, $lname, $phonenb, $email)
    {
        $checkSql = "SELECT id FROM users WHERE (email = ? OR phonenb = ?) AND id != ?";
        $checkStmt = $this->mysqli->prepare($checkSql);
        $checkStmt->bind_param("ssi", $email, $phonenb, $userId);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0)
        {
            response(false, "Email/Phone number already in use.");
            return;
        }

        $sql = "UPDATE users SET fname = ?, lname = ?, phonenb = ?, email = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssssi", $fname, $lname, $phonenb, $email, $userId);

        if ($stmt->execute())
        {
            response(false, "User updated successfully");
            return;
        } else {
            response(false, "Error:" . $this->mysqli->error);
            return;
        }
    }
    
    public function deleteUser($userId)
    {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) 
        {
            response(false, "Userdeleted successfully");
        } else 
        {
            response(false, "Error" . $this->mysqli->error);
            return;
        }
    }
}
?>
