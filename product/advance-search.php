<?php

// error_reporting(E_ERROR | E_PARSE);
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['products'] = null;

$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);
$searchKey = $entry['searchkey'];
$category = $entry['categoryid'];
$ingredient1 = $entry['ingredient1'];
$ingredient2 = $entry['ingredient2'];
$ingredient3 = $entry['ingredient3'];
$exception1 = $entry['exception1'];
$exception2 = $entry['exception2'];
$exception3 = $entry['exception3'];

// HOWTO :: php connect mysql database
// 1. connect ไปยัง database
$connection = mysqli_connect("localhost", "root", "", "caremyskin");
mysqli_query($connection, 'set names utf8');

// 2. check connection database ว่าสำเร็จหรือไม่
if (!$connection) {
  $apiResponse['status'] = 'error';
  $apiResponse['message'] = "can not connect database";
  // echo "can not connect database";
  echo json_encode($apiResponse);
  exit;
}

// echo "connect database success";
$join = "";
$where = " WHERE 1 = 1 ";

if ($searchKey != '') {
    $where .= " AND (p.productname LIKE '%" . $searchKey . "%' OR p.productbrand LIKE '%" . $searchKey . "%') ";
}

if ($category != '') {
    $where .= " AND p.categoryid = '".$category."' ";
}

if ($ingredient1 != '') {
    $join .= " INNER JOIN ingredientproduct AS ip2 ON (ip2.productid = ip.productid AND ip2.ingredientid = '".$ingredient1."') ";
}

if ($ingredient2 != '') {
    $join .= " INNER JOIN ingredientproduct AS ip3 ON (ip3.productid = ip.productid AND ip3.ingredientid = '".$ingredient2."') ";
}

if ($ingredient3 != '') {
    $join .= " INNER JOIN ingredientproduct AS ip4 ON (ip4.productid = ip.productid AND ip4.ingredientid = '".$ingredient3."') ";
}

if ($exception1 != '') {
    $join .= " LEFT JOIN ingredientproduct AS ip5 ON (ip5.productid = ip.productid AND ip5.ingredientid = '".$exception1."') ";
    $where .= " AND ip5.ingredientid IS NULL ";
}

if ($exception2 != '') {
    $join .= " LEFT JOIN ingredientproduct AS ip6 ON (ip6.productid = ip.productid AND ip6.ingredientid = '".$exception2."') ";
    $where .= " AND ip6.ingredientid IS NULL ";
}

if ($exception3 != '') {
    $join .= " LEFT JOIN ingredientproduct AS ip7 ON (ip7.productid = ip.productid AND ip7.ingredientid = '".$exception3."') ";
    $where .= " AND ip7.ingredientid IS NULL ";
}

$sql = "
SELECT
    DISTINCT ip.productid,
    p.categoryid,
    p.productname,
    p.productimage,
    p.productbrand,
    p.ratescore,
    i.ingredientname
    FROM ingredientproduct AS ip
    ".$join."
    LEFT JOIN product AS p ON ip.productid = p.productid
    LEFT JOIN ingredient AS i ON i.ingredientid = ip.ingredientid
    ".$where."
    GROUP BY ip.productid
";

// 4. run คำสั่ง sql
$result = $connection->query($sql);

// 5. check run คำสั่ง sql error
if (!$result) {
  $apiResponse['status'] = 'error';
  $apiResponse['message'] = "wrong sql command";
  echo json_encode($apiResponse);
  exit;
}

// echo "run sql command success";

// 6. ดึงข้อมูลออกมาแสดง
// $member = $result->fetch_assoc();
$products = $result->fetch_all(MYSQLI_ASSOC);
$apiResponse['products'] = $products;

echo json_encode($apiResponse);
exit;

?>