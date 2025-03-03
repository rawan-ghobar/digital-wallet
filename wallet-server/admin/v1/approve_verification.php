<?php
session_start();
include(__DIR__ . "/../../connection/connection.php");
include(__DIR__ . "/../../utils/utils.php");


header("Content-Type: application/json");

// ✅ Ensure admin is logged in
if (!isset($_SESSION["admin_id"])) {
    response(false, "Unauthorized. Admin access only");
    exit;
}

// ✅ Validate inputs
if (!isset($_POST["request_id"]) || !isset($_POST["action"])) {
    response(false, "Missing parameters");
    exit;
}

$requestId = (int) $_POST["request_id"];
$action = $_POST["action"];

if ($action !== "approved" && $action !== "rejected") {
    response(false, "Invalid action");
}

// ✅ Get user_id from request
$sql = "SELECT user_id FROM verification_requests WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $requestId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    response(false, "Verification request not found");
}

$request = $result->fetch_assoc();
$userId = $request["user_id"];

// ✅ If approved, update users table
if ($action === "approved") {
    $sql = "UPDATE users SET is_verified = 1, verified_at = NOW() WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}

// ✅ Delete the verification request
$sql = "DELETE FROM verification_requests WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $requestId);
$stmt->execute();

response(true, "Verification request " . $action);
?>
