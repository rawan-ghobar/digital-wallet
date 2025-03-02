<?php
session_start();
include(__DIR__ . "/../../connection/connection.php");
include(__DIR__ . "/../../models/activitylog.php");
include_once(__DIR__ . "/../../utils/utils.php");

header("Content-Type: application/json");

if (!isset($_SESSION["admin_id"])) {
    response(false, "Unauthorized. Admin access only");
    exit;
}

$log = new ActivityLog($mysqli);
$log->getAllLogs();
?>
