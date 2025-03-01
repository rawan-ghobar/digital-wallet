<?php

include(__DIR__ . "/../../connection/connection.php");


$sql= " CREATE TABLE qrCodes(
        id int AUTO_INCREMENT PRIMARY KEY,
        qr_code varchar(255) NOT NULL,
        amount decimal (10,2) NOT NULL,
        transaction_id int,
        done_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE
        )";

if ($mysqli->query($sql) === TRUE)
{
    echo "qrCodes table created successfully";
}

else
{
    echo "error" .$mysqli->error;
}
?>