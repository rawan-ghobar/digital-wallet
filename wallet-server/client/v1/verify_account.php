<?php
session_start();
include(__DIR__ . "/../../connection/connection.php");
include(__DIR__ . "/../../utils/utils.php");

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    response(false, "Unauthorized. Please log in");
    exit;
}

$userId = $_SESSION["user_id"];

if (!isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_FILES["national_id"])) {
    response(false, "Please provide email, password, and upload your National ID");
    exit;
}

$email = $_POST["email"];
$password = $_POST["password"];
$nationalIdFile = $_FILES["national_id"];

$sql = "SELECT user_password FROM users WHERE id = ? AND email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("is", $userId, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    response(false, "Invalid email or password");
}

$user = $result->fetch_assoc();
if (!password_verify($password, $user["user_password"])) {
    response(false, "Invalid credentials");
}

$targetDir = __DIR__ . "/../../uploads/";
$fileName = "national_id_" . $userId . "_" . time() . ".jpg";
$targetFilePath = $targetDir . $fileName;

if (!move_uploaded_file($nationalIdFile["tmp_name"], $targetFilePath)) {
    response(false, "Failed to upload National ID");
}

$sql = "INSERT INTO verification_requests (user_id, national_id) VALUES (?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("is", $userId, $fileName);

if ($stmt->execute()) {
    response(true, "Verification request submitted. Await admin approval.");
} else {
    response(false, "Error: " . $mysqli->error);
}
?>
