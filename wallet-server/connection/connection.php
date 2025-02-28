<?php

$host= "localhost";
$user= "root";
$password= "";
$dbname= "digital-wallet";

$mysqli = new mysqli ($host, $user, $password, $dbname);

if ($mysqli->connect_error)
{
    echo "Connection failed" .$mysqli->connect_error;
}
else echo "Connection successful";

?>