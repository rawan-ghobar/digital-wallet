<?php

include(__DIR__ . "/../../connection/connection.php");
include(__DIR__ . "/../../utils/utils.php");

header("Content-Type: application/json");


if($_SERVER["REQUEST_METHOD"] !== "POST") 
{
    response(false, "Invalid request method");
    exit;
}

$data = getJsonRequestData() ;

if(!isset($data["fname"]))
{
    response(false,"Please enter your first name");
}
else if (!isset($data["lname"]))
{
    response(false,"Please enter your last name");
}
else if (!isset($data["email"]))
{
    response(false,"Please enter your email");
}
else if (!isset($data["phonenb"]))
{
    response(false,"Please enter your phone number");
}
else if (!isset($data["user_password"]))
{
    response(false,"Please enter your password");
}
else if (!isset($data["confirm_password"]))
{
    response(false,"Please confirm password");
}


$fname= $data["fname"];
$lname = $data["lname"];
$email = $data["email"];
$phonenb = $data["phonenb"];
$password = $data["user_password"];
$confirmpassword = $data["confirm_password"];

$sql= "SELECT id FROM users WHERE email = ? OR phonenb = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $email, $phonenb);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0)
{
    response(false, "User already exists, please login");
}

if ($password !== $confirmpassword) {
    response(false, "Passwords do not match");
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (email, fname, lname, phonenb, user_password, is_verified) VALUES (?, ?, ?, ?, ?, 0)";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssss", $email, $fname, $lname, $phonenb, $hashed_password);

if ($stmt->execute())
{
    response(true, "User registered successfully");
}
else
{
    response(false, "Error: " . $mysqli->error);
}

?>