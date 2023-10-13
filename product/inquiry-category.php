<?php
error_reporting(E_ERROR | E_PARSE);
//error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = 'inquiry product success';

$connection = mysqli_connect("localhost", "root", "", "caremyskin");
mysqli_query($connection, 'set names utf8');

if (!$connection) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "can not connect database";

    echo json_encode($apiResponse);
    exit;
}

$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);
    //  echo "<pre>";
    //  print_r($entry);

    $sql = "
    SELECT
    categoryid,
    categoryname,
    categoryimage
    FROM category
    WHERE categoryid = '" . $entry['categoryid'] . "'
    ";
    $result = $connection->query($sql);

if ($result === false) {
    $connection->close();
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrog sql command";
    echo json_encode($apiResponse);
    exit;
}

$category = $result->fetch_assoc();
$apiResponse['category'] = $category;

echo json_encode($apiResponse);
exit;

?>