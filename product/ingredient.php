<?php

error_reporting(E_ERROR | E_PARSE);
//error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['ingredients'] = null;

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
ingredientid,
ingredientname

FROM ingredient
ORDER BY ingredientname
";

$result = $connection->query($sql);

if(!$result) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrog sql command";
    echo json_encode($apiResponse);
    exit;
}

// echo "run sql command success";

$ingredients = $result->fetch_all(MYSQLI_ASSOC);
$apiResponse['ingredients'] = $ingredients;

echo json_encode($apiResponse);

// foreach ($members as $member) {
//     echo "<pre>";
//     print_r($member);
// }

?>