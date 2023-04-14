<?php

include_once("../inc/bootstrap.php");
include_once("../inc/functions.inc.php");

if (isset($_GET['verification_code'])) {
    $verification_code = $_GET['verification_code'];
    $verification = new Verification($verification_code);
    if ($verification->verify()) {
        header("Location: ../php/login.php");
    } else {
        echo "Invalid verification code";
    }
}

?>