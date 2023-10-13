<?php
error_reporting(E_ERROR | E_PARSE);
//error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = 'add member success';

//1.คอนเนคไปยังดาต้าเบส
$connection = mysqli_connect("localhost","root","","caremyskin");
mysqli_query($connection, 'set names utf8');

//2.เช็ค connection database ว่าสำเร็จหรือไม่
if (!$connection){
    echo "can not connect database!!";
    exit;
}
//3.การรับค่าจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);

$sql = " 
SELECT 
memberid,
email,
password,
username,
firstname,
lastname,
phonenumber,
profileimage,
status

FROM member
WHERE email = '" .$entry ['email']."' AND 
password = '" .$entry ['password']."'
";

//5.RUN SQL command
$result = $connection->query($sql);

if ($result === false) {
    $connection->close();
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrog sql command";
    echo json_encode($apiResponse);
    exit;
}

$member = $result->fetch_assoc();
if($member == NULL) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "data not found";
    echo json_encode($apiResponse);
    exit;
}

$apiResponse['member'] = $member;

echo json_encode($apiResponse);
exit;
?>