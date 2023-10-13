<?php

error_reporting(E_ERROR | E_PARSE);
//error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$apiResponse = array();
$apiResponse['status'] = 'success';
$apiResponse['message'] = null;
$apiResponse['members'] = null;

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
$profileImage = $entry['profile_picture'];
echo $profileImage;
exit;

// echo "connect success";

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
status,
create_at
FROM member
";

$result = $connection->query($sql);

if(!$result) {
    $apiResponse['status'] = 'error';
    $apiResponse['message'] = "wrog sql command";
    echo json_encode($apiResponse);
    exit;
}

// echo "run sql command success";

$members = $result->fetch_all(MYSQLI_ASSOC);
$apiResponse['members'] = $members;

echo json_encode($apiResponse);

// foreach ($members as $member) {
//     echo "<pre>";
//     print_r($member);
// }

?>