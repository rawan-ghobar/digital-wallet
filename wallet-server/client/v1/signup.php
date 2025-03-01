<?php

include_once(__DIR__ . "/../../connection/connection.php");
include_once(__DIR__ . "/../../utils/utils.php");
include_once(__DIR__ . "/../../models/user.php");

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

if ($password !== $confirmpassword)
{
    response(false, "Passwords do not match");
    return;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    response(false, "Invalid email format");
    return;
}  

$user = new User($mysqli);

$result = $user->createUser($email, $fname, $lname,  $phonenb, $password);

echo json_encode($result);

?>