<?php

include(__DIR__ . "/../../connection/connection.php");


$sql= " CREATE TABLE users(
        id int AUTO_INCREMENT PRIMARY KEY,
        email varchar(100) UNIQUE NOT NULL,
        fname varchar(100) NOT NULL,
        lname varchar(100) NOT NULL,
        phonenb varchar(100) NOT NULL,
        user_password varchar(250) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        is_verified boolean,
        verified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

if ($mysqli->query($sql) === TRUE)
{
    echo "Users table created successfully";
}
echo "error" .$mysqli->error;
?>