<?php
session_start();
include(__DIR__ . "/../../connection/connection.php");
include(__DIR__ . "/../../models/admin.php");
include_once(__DIR__ . "/../../utils/utils.php");

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    response(false, "Invalid request method");
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["email"], $data["password"])) {
    response(false, "Email and password are required");
    exit;
}

$email = $data["email"];
$password = $data["admin_password"];

$admin = new Admin($mysqli);
$admin->loginAdmin($email, $password);
?>
