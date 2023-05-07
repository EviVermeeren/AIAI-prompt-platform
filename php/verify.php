<?php

include_once("../inc/bootstrap.php"); // include bootstrap file
include_once("../inc/functions.inc.php"); // include functions file

if (isset($_GET['verification_code'])) { // if verification code is set
    $verification_code = $_GET['verification_code']; // get verification code 
    $verification = new User($email, $conn, $verification_code); // create new verification object
    if ($verification->verify()) { // if verification is successful
        $_SESSION['message'] = "Your account has been verified. Please log in."; // set message
        header("Location: ../php/login.php"); // redirect to login page
    } else { // if verification is not successful
        echo "Invalid verification code"; // display error message
    }
}
