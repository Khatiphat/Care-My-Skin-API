<?php
error_reporting(E_ERROR | E_PARSE);
//error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = 'add product success';


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
    // echo '<pre>';
    // print_r($entry);

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

// 4. เขียนคำสั่ง sql insert ข้อมูล
$sql = "
INSERT INTO product (
    productid,
    categoryid,
    productname,
    productimage,
    productbrand
)
VALUES (
    '" . $entry['productid'] . "',
    '" . $entry['categoryid'] . "',
    '" . $entry['productname'] . "',
    '" . $imageUrl . "',
    '" . $entry['productbrand'] . "'
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