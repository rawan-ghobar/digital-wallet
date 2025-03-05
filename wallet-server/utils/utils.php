<?php

function response($success, $message, $data = null)
{
    $responseArray = ["success" => $success,"message" => $message];

    if ($data !== null) {
        $responseArray["data"] = $data;
    }
    echo json_encode($responseArray);
    exit;
}
function getJsonRequestData() //This function gets the JSOn  data sent from the user, and turns them into a PHP associative array
{
    return json_decode(file_get_contents("php://input"), true);
}

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getUserIdFromToken() 
{
    $headers = getallheaders();
    if (!isset($headers["Authorization"])) {
        response(false, "No authorization header found");
        exit;
    }

    $authHeader = $headers["Authorization"];
    list($bearer, $jwtToken) = explode(' ', $authHeader, 2);
    if ($bearer !== 'Bearer') {
        response(false, "Invalid Authorization header format");
        exit;
    }

    $secretKey = "YOUR_SUPER_SECRET_KEY"; 
    try {
        $decoded = JWT::decode($jwtToken, new Key($secretKey, 'HS256'));
        $userId = $decoded->user_id; 
    } catch (Exception $e) {
        response(false, "Invalid or expired token: " . $e->getMessage());
        exit;
    }
    return $userId;
}


?>