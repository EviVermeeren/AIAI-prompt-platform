<?php 
include_once("../inc/bootstrap.php");

session_destroy();
header('Location: ../php/login.php');

?>