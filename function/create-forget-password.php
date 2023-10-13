<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['memberForgetPass'] = null;

$connection = mysqli_connect("localhost", "root", "", "caremyskin");
mysqli_query($connection, 'set names utf8');


if (!$connection) {
    $apiResponse['status'] = "error";
    $apiResponse['message'] = "can not connect database";
    // echo "can not connect database";
    echo json_encode($apiResponse);
    exit;
}
//รับค่าจาก ionic
$rawData = file_get_contents("php://input");
$entry = json_decode($rawData, true);
// echo "<pre>";
// print_r($entry);

//คำสั่ง SQL insert ข้อมูล
$sql = "
INSERT INTO forgetpassword(
    memberid,
    email,
    username
    )
    VALUES(
        '" . $entry['memberid'] . "',
        '" . $entry['email'] . "',
        '" . $entry['username'] . "'
        )
";
// echo $sql;

//run sql command
if ($connection->query($sql) === false) { //ถ้า run ไม่สำเร็จ
    $connection->close();
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = 'wrong sql command';
    echo json_encode($apiResponse);
    exit;
}

echo json_encode($apiResponse);
exit;
?>