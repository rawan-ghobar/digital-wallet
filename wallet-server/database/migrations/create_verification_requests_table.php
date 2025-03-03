<?php
include(__DIR__ . "/../../connection/connection.php");

$sql = "CREATE TABLE verification_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    national_id VARCHAR(255) NOT NULL,
    verification_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($mysqli->query($sql) === TRUE) {
    echo "Verification Requests table created successfully";
} else {
    echo "Error: " . $mysqli->error;
}
?>
