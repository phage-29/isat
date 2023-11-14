<?php
$servername = "localhost";
$username = "root";
$password = "Password@123!";
$database = "isatdb";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>