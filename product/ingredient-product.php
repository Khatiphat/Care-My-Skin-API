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

$product = $result->fetch_all(MYSQLI_ASSOC);
$apiResponse['product'] = $product;

foreach ($product AS $index => $item) {
    $sqlIngredientionProduct = "
        SELECT 
            ip.ingredientproductid
            , ip.productid
            , ip.ingredientid
            , i.ingredientname

        FROM ingredientproduct AS ip
        LEFT JOIN product AS p ON p.productid = ip.productid 
        LEFT JOIN ingredient AS i ON i.ingredientid = ip.ingredientid
        WHERE ip.productid = ".$entry['productid']."
        ORDER BY i.ingredientname
    ";
    $resultIngredientProduct = $connection->query($sqlIngredientionProduct);
    $ingredientProduct = $resultIngredientProduct->fetch_all(MYSQLI_ASSOC);

    $product[$index]['product_ingredient'] = $ingredientProduct;
}

$apiResponse['product'] = $product;

echo json_encode($apiResponse);
exit;

?>