<?php

include(__DIR__ . "/../../connection/connection.php");


$sql= " CREATE TABLE transactions(
        id int AUTO_INCREMENT PRIMARY KEY,
        transaction_type varchar(100) NOT NULL,
        done_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        user_id int NOT NULL,
        wallet_id int, 
        card_id int,
        amount decimal(10,2) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE,
        FOREIGN KEY (card_id) REFERENCES cards(id) ON DELETE CASCADE
        )";

if ($mysqli->query($sql) === TRUE)
{
    echo "Transactions table created successfully";
}
else{
    echo "error" .$mysqli->error;
}

?>