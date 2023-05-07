<?php

include_once("../inc/bootstrap.php");
include_once("../inc/functions.inc.php");

if (isset($_GET['verification_code'])) {
    $verification_code = $_GET['verification_code'];
    $conn = Db::getInstance(); // Create a database connection
    $verification = new User(); // Create a new User object
    $verification->setConnection($conn); // Set the database connection
    $verification->setVerificationCode($verification_code); // Set the verification code
    if ($verification->verify($verification_code)) {
        $_SESSION['message'] = "Your account has been verified. Please log in.";
        header("Location: ../php/login.php");
    } else {
        echo "Invalid verification code";
    }
}
