<?php

include_once("../inc/bootstrap.php");
include_once("../inc/functions.inc.php");

if (isset($_GET['verification_code'])) {
    $verification_code = $_GET['verification_code'];
    $conn = Db::getInstance();
    $verification = new User();
    $verification->setConnection($conn);
    $verification->setVerificationCode($verification_code);
    if ($verification->verify($verification_code)) {
        $_SESSION['message'] = "Your account has been verified. Please log in.";
        header("Location: ../php/login.php");
    } else {
        echo "Invalid verification code";
    }
}
