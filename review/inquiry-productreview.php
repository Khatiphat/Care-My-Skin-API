<?php

error_reporting(E_ERROR | E_PARSE);
//error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['members'] = null;

$connection = mysqli_connect("localhost", "root", "", "caremyskin");
try {
    mysqli_query($connection, 'set names utf8');

if (!$connection) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "can not connect database";

    echo json_encode($apiResponse);
    exit;
}
} catch (\Throwable $th) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "can not connect database";

    echo json_encode($apiResponse);
    exit;
}

// echo "connect success";

$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

$sql = "
SELECT
productname,
topicreview,
description,
score
FROM product
JOIN review ON review.productid = product.productid
WHERE product.productid = '" . $entry['productid'] . "'
";

$result = $connection->query($sql);

if(!$result) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrog sql command";
    echo json_encode($apiResponse);
    exit;
}

// echo "run sql command success";

$product = $result->fetch_all(MYSQLI_ASSOC);
$apiResponse['product'] = $product;

echo json_encode($apiResponse);

// foreach ($members as $member) {
//     echo "<pre>";
//     print_r($member);
// }

?>