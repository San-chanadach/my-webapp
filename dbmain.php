<?php
$hostname = "192.168.0.70";
$username = "root";
$password = "supal898";
$database = "mydim";
$port = "3306";

//$hostname = "localhost";
//$username = "root";
//$password = "";
//$database = "mydim";
//$port = "3306";

mysqli_report(MYSQLI_REPORT_OFF);
$connection = mysqli_connect($hostname, $username, $password, $database, $port);

if (!$connection) {
    die("การเชื่อมต่อล้มเหลว: " . mysqli_connect_error());
} else {
    echo "เชื่อมต่อสำเร็จ";
}

//mysqli_close($connection);
?>
