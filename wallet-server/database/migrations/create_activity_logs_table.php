<?php
include(__DIR__ . "/../../connection/connection.php");

$sql= "CREATE TABLE activity_logs(
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT DEFAULT NULL,
        admin_id INT DEFAULT NULL,
        user_action TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
      )";

if ($mysqli->query($sql) === TRUE) {
    echo "Activity Logs table created successfully";
} else {
    echo "Error: " . $mysqli->error;
}
?>
