<?php

$server = "localhost";
$user = "root";
$pass = "1001ouo";
$db = "test";

$conn = new mysqli($server,$user,$pass,$db);
if($conn->connect_error){
    die("connect error : " . $conn);
}



?>