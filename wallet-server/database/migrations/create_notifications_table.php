<?php
include(__DIR__ . "/../../connection/connection.php");

$sql= "CREATE TABLE notifications(
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        notification_message TEXT NOT NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
      )";

if ($mysqli->query($sql) === TRUE) {
    echo "Notifications table created successfully";
} else {
    echo "Error: " . $mysqli->error;
}
?>
