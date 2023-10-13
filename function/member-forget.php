<?php

error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['memberForgetPassword'] = null;

$connection = mysqli_connect("localhost", "root", "", "caremyskin");

try {
    mysqli_query($connection, 'set names utf8');

    if (!$connection) {
        $apiResponse['status'] = "error";
        $apiResponse['message'] = "can not connect database";
        // echo "can not connect database";
        echo json_encode($apiResponse);
        exit;
    }
} catch (\Throwable $th) {
    $apiResponse['status'] = "error";
    $apiResponse['message'] = "can not connect database";
    echo json_encode($apiResponse);
    exit;
}

$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

$sql = "SELECT *   FROM forgetpassword WHERE memberid = '" . $entry['memberid'] . "'";

$result = $connection->query($sql);

if (!$result) {
    $apiResponse['status'] = "error";
    $apiResponse['message'] = "wrong sql command";
    echo json_encode($apiResponse);
    // echo "wrong sql command";
    exit;
}

$member = $result->fetch_assoc();
$apiResponse['memberForgetPassword'] = $member;

echo json_encode($apiResponse);
exit;
?>