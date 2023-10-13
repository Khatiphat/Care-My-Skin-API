<?php
error_reporting(E_ERROR | E_PARSE);
//error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = 'update product success';

$connection = mysqli_connect("localhost", "root", "", "caremyskin");
mysqli_query($connection, 'set names utf8');

if (!$connection){
    echo "can not connect database";
    exit;
}

// รับคำสั่งจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

//1
$productImage = $entry['productimage'];
$imageName = uniqid();
$imageUrl = "";

//2
try {
    $productImageNohavePrefix = substr($productImage, 5);

    $splited1 = explode(",", $productImageNohavePrefix);
    $mimeType1 = $splited1[0];
    $imageData1 = $splited1[1];

    $splited2 = explode(';', $mimeType1, 2);
    $mimeType2 = $splited2[0];

    $splited3 = explode('/', $mimeType2, 2);
    $fileExtension = $splited3[1];

    $imageName = $imageName . "." . $fileExtension;
    $imagePath = "../uploads/" . $imageName;

    file_put_contents($imagePath, base64_decode($imageData1));

    $imageUrl = "http://localhost/caremyskindb/uploads/" . $imageName;
    // echo $imageName;
    // exit;
} catch (\Throwable $th) {

}


$sql = "
UPDATE product
SET productid = '" . $entry['productid'] . "',
    categoryid = '" . $entry['categoryid'] . "',
    productname =  '" . $entry['productname'] . "', 
    productimage = '" . $imageUrl . "', 
    productbrand = '" . $entry['productbrand'] . "',
    ratescore = '" . $entry['ratescore'] . "'
WHERE productid  = '" . $entry['productid'] . "'
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