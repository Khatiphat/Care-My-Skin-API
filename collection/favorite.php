<?php

error_reporting(E_ERROR | E_PARSE);
//error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['favorites'] = null;

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

$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);
// echo "connect success";

$sql = "
SELECT
    f.favoriteid,
    f.productid,
    f.memberid,
    p.productimage,
    p.productname,
    p.productbrand
FROM favorite AS f
LEFT JOIN product AS p ON f.productid = p.productid
";

// echo $sql;
// exit;

$result = $connection->query($sql);

if(!$result) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrog sql command";
    echo json_encode($apiResponse);
    exit;
}

$favorites = $result->fetch_all(MYSQLI_ASSOC);

$apiResponse['favorites'] = $favorites;

echo json_encode($apiResponse);
?>