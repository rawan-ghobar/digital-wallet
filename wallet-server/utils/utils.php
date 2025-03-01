<?php

function response($success, $message)// This function handles the resonses
{
    echo json_encode(["success" => $success, "message" => $message]);// json_encode converts form PHP to JSON
}

function getJsonRequestData() //This function gets the JSOn  data sent from the user, and turns them into a PHP associative array
{
    return json_decode(file_get_contents("php://input"), true);
}


?>