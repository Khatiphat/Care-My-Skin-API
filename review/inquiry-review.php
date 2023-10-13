<?php

// error_reporting(E_ERROR | E_PARSE);
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['reviews'] = null;

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

$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

// 3. เขียนคำสั่ง SQL
$sql = "
SELECT 
	r.reviewid,
  r.memberid,
  r.productid,
  r.topicreview, 
  r.description,
  r.score,
  r.created,
  m.username,
  m.profileimage
FROM review AS r
LEFT JOIN member AS m ON r.memberid = m.memberid
  WHERE r.productid = " . $entry['productid'] . "
  ORDER BY r.reviewid DESC
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
$reviews = $result->fetch_all(MYSQLI_ASSOC);
$apiResponse['reviews'] = $reviews;

echo json_encode($apiResponse);
exit;

?>