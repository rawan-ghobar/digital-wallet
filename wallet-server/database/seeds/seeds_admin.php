<?php

include(__DIR__ . "/../../connection/connection.php");
$password = password_hash("rawan", PASSWORD_DEFAULT);

$sql= "INSERT INTO admins(email, admin_password) VALUES ('rawan2ghobar@gmail.com','$password')";

if ($mysqli->query($sql) === TRUE) {
    echo "New user created successfully!";
} else {
    echo "Error: " . $mysqli->error;
}
?>