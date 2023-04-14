<?php 
include_once("../inc/bootstrap.php");

if (session_status() === PHP_SESSION_ACTIVE) {
  session_destroy();
}

header('Location: ../php/login.php');
exit();
?>