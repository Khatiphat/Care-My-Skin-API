<?php
error_reporting(E_ERROR | E_PARSE);
//error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = 'update member success';

$connection = mysqli_connect("localhost", "root", "", "caremyskin");
mysqli_query($connection, 'set names utf8');

if (!$connection){
    echo "can not connect database";
    exit;
}

// รับคำสั่งจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

$sql = "
UPDATE member
SET password = '" . $entry['password'] . "'
WHERE memberid  = '" . $entry['memberid'] . "'
";


if ($connection->query($sql) === false) {
    $connection->close();
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrog sql command";
    echo json_encode($apiResponse);
    exit;
}

echo json_encode($apiResponse);
exit;
?>