<?php 
include_once("../inc/bootstrap.php"); // This is the bootstrap file

if (session_status() === PHP_SESSION_ACTIVE) { // if session is active
  session_destroy(); // destroy session, so you logout
}

header('Location: ../php/login.php'); // redirect to login page
exit(); // exit script
?>