<?php
session_start();
include(__DIR__ . "/../../connection/connection.php");
include(__DIR__ . "/../../utils/utils.php");

header("Content-Type: application/json"); //this is to tell the browser that the response will be in json format


if($_SERVER["REQUEST_METHOD"] !== "POST") 
{
    response(false, "Invalid request method");
    exit;
}

$data = getJsonRequestData();


if(!isset($data["login"]))
{
    response(false,"Please enter your email/phone number");
}
else if (!isset($data["user_password"]))
{
    response(false,"Please enter your password");
    return;
}

$login = $data["login"];
$password = $data["user_password"];


// write an if statement to check if the user is sending his email or phone number
if (filter_var($login, FILTER_VALIDATE_EMAIL))
{
    $sql = "SELECT id, email, phonenb, is_verified, user_password FROM users WHERE email = ?"; // the ? is a placeholder for a value thet will be bound down
}
else
{
    $sql = "SELECT id, email, phonenb, is_verified, user_password FROM users WHERE phonenb = ?";
}


$stmt = $mysqli->prepare($sql);
if (!$stmt)
{
    response(false, "Database error: " . $mysqli->error);
    exit;
}

$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1)
{
    response(false, "User not found");
    return;
}
$user = $result->fetch_assoc();
    
    if (password_verify($password, $user["user_password"]))
    {   
         $_SESSION["user_id"] = $user["id"];
         response(true, [
            "message" => "Login successful",
            "session_id" => session_id(),  // Print the session ID
            "stored_session" => $_SESSION  // Print all session variables
        ]);
    
    }
    else
    {
        response(false, "Wrong Password");
    }

?>