<?php
include(__DIR__ . "/../../connection/connection.php");

$sql= "CREATE TABLE admins(
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) UNIQUE NOT NULL,
        admin_password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      )";

if ($mysqli->query($sql) === TRUE) {
    echo "Admins table created successfully";
} else {
    echo "Error: " . $mysqli->error;
}
?>
