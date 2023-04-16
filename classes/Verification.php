<?php

include_once("../inc/bootstrap.php"); // include bootstrap file
include_once("../inc/functions.inc.php"); // include functions file

class Verification // verification class
{
    private $verification_code; // verification code

    public function __construct($verification_code) // constructor
    {
        $this->verification_code = $verification_code; // set verification code
    }

    public function verify() // verify account
    {
        $result = checkVerifyToken($this->verification_code); // check if verification code is valid
        if ($result) { // if verification code is valid
            $conn = Db::getInstance(); // get database connection
            $statement = $conn->prepare("UPDATE users SET activated = 1 WHERE verification_code = :verification_code"); // update database
            $statement->bindValue(":verification_code", $this->verification_code); // bind verification code to query
            $statement->execute(); // execute query
            return true; // return true
        } else {
            return false; // return false
        }
    }
}