<?php

include(__DIR__ . "/../../connection/connection.php");
include(__DIR__ . "/../../utils/utils.php");
include(__DIR__ . "/../../utils/jwt-loader.php");
include_once(__DIR__ . "/../../models/activitylog.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


header("Content-Type: application/json"); // Tell browser that response is in JSON format

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    response(false, "Invalid request method");
    exit;
}

$data = getJsonRequestData();

if (!isset($data["login"])) {
    response(false, "Please enter your email/phone number");
    exit;
}

if (!isset($data["user_password"])) {
    response(false, "Please enter your password");
    exit;
}

$login    = $data["login"];
$password = $data["user_password"];

if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
    $sql = "SELECT id, email, phonenb, is_verified, user_password 
            FROM users 
            WHERE email = ?";
} else {
    $sql = "SELECT id, email, phonenb, is_verified, user_password 
            FROM users 
            WHERE phonenb = ?";
}

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    response(false, "Database error: " . $mysqli->error);
    exit;
}

$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    response(false, "User not found");
    exit;
}

$user = $result->fetch_assoc();

if (password_verify($password, $user["user_password"])) {
   
    $fetchedUserId = $user["id"]; 
    $secretKey     = "YOUR_SUPER_SECRET_KEY"; 
    
    $issuedAt = time();           
    $expire   = $issuedAt + 3600; 

    $payload = [
        'user_id' => $fetchedUserId,
        'iat'     => $issuedAt,  
        'exp'     => $expire     
    ];

    $jwt = JWT::encode($payload, $secretKey, 'HS256');

    response(true, "Login successful", ["token" => $jwt]);

    // $log = new ActivityLog($mysqli);
    // $log->logActivity($user["id"], null, "User logged in with email: " . $user["email"]);
} else 
{
    response(false, "Wrong Password");
}
