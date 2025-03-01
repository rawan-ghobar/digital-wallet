<?php

include(__DIR__ . "/../../connection/connection.php");
$password = password_hash("rawan", PASSWORD_DEFAULT);

$sql= "INSERT INTO users(email,fname,lname,phonenb,user_password,is_verified) VALUES ('rawan1ghobar@gmail.com', 'rawan', 'ghobar', '12445678','$password', 0)";

if ($mysqli->query($sql) === TRUE) {
    echo "New user created successfully!";
} else {
    echo "Error: " . $mysqli->error;
}
?>