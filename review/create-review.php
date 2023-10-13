<?php
error_reporting(E_ERROR | E_PARSE);
//error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = 'add member success';


$connection = mysqli_connect("localhost", "root", "", "caremyskin");
mysqli_query($connection, 'set names utf8');

if (!$connection) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "can not connect database";

    echo json_encode($apiResponse);
    exit;
}

// 3.รับค่าจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

if (
    $entry['memberid'] == null || $entry['memberid'] == ""
    || $entry['productid'] == null || $entry['productid'] == ""
    || $entry['topicreview'] == null || $entry['topicreview'] == ""
    || $entry['description'] == null || $entry['description'] == ""
) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "invalid parameter";
    echo json_encode($apiResponse);
    exit;
}
$sql = "
INSERT INTO review (
    memberid,
    productid,
    topicreview,
    description,
    score,
    created
)
VALUES (
    '" . $entry['memberid'] . "',
    '" . $entry['productid'] . "',
    '" . $entry['topicreview'] . "',
    '" . $entry['description'] . "',
    '" . $entry['score'] . "',
    NOW()
)
";
// echo $sql;

if ($connection->query($sql) === false) {
    $connection->close();
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrog sql command";
    echo json_encode($apiResponse);
    exit;
}

echo json_encode($apiResponse);
?>