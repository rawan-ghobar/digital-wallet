<?php

include(__DIR__ . "/../../connection/connection.php");


$sql= " CREATE TABLE wallets(
        id int AUTO_INCREMENT PRIMARY KEY,
        wallet_name varchar(100) NOT NULL,
        wallet_pin varchar(250) NOT NULL,
        wallet_balance decimal(10,2) DEFAULT 0.00,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        user_id int,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";

if ($mysqli->query($sql) === TRUE)
{
    echo "Wallets table created successfully";
}

else
{
    echo "error" .$mysqli->error;
}
?>