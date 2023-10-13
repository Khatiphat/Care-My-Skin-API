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
    productid,
    categoryid,
    productname,
    productimage,
    productbrand,
    ratescore
    FROM product
    WHERE productid = '" . $entry['productid'] . "'
    ";
    $result = $connection->query($sql);

if ($result === false) {
    $connection->close();
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrog sql command";
    echo json_encode($apiResponse);
    exit;
}

$product = $result->fetch_assoc();
$apiResponse['product'] = $product;

echo json_encode($apiResponse);
exit;

?>