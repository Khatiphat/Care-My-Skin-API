<?php

error_reporting(E_ERROR | E_PARSE);
//error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['collection'] = null;

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

$sql = "
SELECT
    c.collectionid,
    c.memberid,
    c.collectionname,
    c.routinetimeid,
    r.routinetime,
    m.profileimage,
    m.username
FROM collection AS c
LEFT JOIN member AS m ON c.memberid = m.memberid
LEFT JOIN routinetime AS r ON c.routinetimeid = r.routinetimeid
";

$result = $connection->query($sql);

if(!$result) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrog sql command";
    echo json_encode($apiResponse);
    exit;
}

$collection = $result->fetch_all(MYSQLI_ASSOC);

foreach ($collection AS $index => $item) {
    $sqlCollectionProduct = "
        SELECT 
            cp.collectionproductid
            , cp.productid
            , cp.collectionid
            , p.productname
            , p.productimage
            , p.productbrand
            , p.ratescore
        FROM collectionproduct AS cp
        LEFT JOIN product AS p ON cp.productid = p.productid 
        WHERE cp.collectionid = ".$item['collectionid']."
    ";
    $resultCollectionProduct = $connection->query($sqlCollectionProduct);
    $collectionProduct = $resultCollectionProduct->fetch_all(MYSQLI_ASSOC);

    $collection[$index]['collection_detail'] = $collectionProduct;
}

$apiResponse['collection'] = $collection;

echo json_encode($apiResponse);

// foreach ($members as $member) {
//     echo "<pre>";
//     print_r($member);
// }

?>