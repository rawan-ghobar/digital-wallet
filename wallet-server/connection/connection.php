<?php

header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$host= "localhost";
$user= "root";
$password= "";
$dbname= "digital-wallet";

$mysqli = new mysqli ($host, $user, $password, $dbname);



?>