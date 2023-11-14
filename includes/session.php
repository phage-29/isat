<?php
require_once "conn.php";

session_start();

if (isset($_SESSION["Username"]) || !empty($_SESSION["Username"])) {
    if ($_SESSION["Role"] == $Role) {
        $query = "SELECT * FROM `users` WHERE `Username`=? and `Role`=?";
        $result = $conn->execute_query($query, [$_SESSION["Username"], $_SESSION["Role"]]);
        $acc = $result->fetch_object();
        $_SESSION['id'] = $acc->id;
        if ($_SESSION['Role'] == 'Student') {
            $query = "SELECT * FROM `students` WHERE `UserID`=?";
            $result = $conn->execute_query($query, [$acc->id]);
            $stud = $result->fetch_object();
        }
    } else {
        header("Location: dashboard.php");
    }
} else {
    header("Location: ../Homepage");
}

function generatePaymentNumber()
{
    // Prefix for the payment number (e.g., "PAY")
    $prefix = "PAY";

    // Current date in the format YYYYMMDD
    $date = date("Ymd");

    // Unique identifier (random number)
    $uniqueId = mt_rand(1000, 9999);

    // Combine the elements to create the payment number
    $paymentNumber = $prefix . $date . $uniqueId;

    return $paymentNumber;
}