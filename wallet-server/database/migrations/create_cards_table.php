<?php

include(__DIR__ . "/../../connection/connection.php");


$sql= " CREATE TABLE cards(
        id int AUTO_INCREMENT PRIMARY KEY,
        card_nb varchar(20) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        expiries_at DATE NOT NULL,
        cvv int NOT NULL,
        wallet_id int NOT NULL,
        user_id int NOT NULL,
        FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";

if ($mysqli->query($sql) === TRUE)
{
    echo "Cards table created successfully";
}
else
{
    echo "error".$mysqli->error;
}
?>