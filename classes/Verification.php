<?php

include_once("../inc/bootstrap.php");
include_once("../inc/functions.inc.php");

class Verification
{
    private $verification_code;

    public function __construct($verification_code)
    {
        $this->verification_code = $verification_code;
    }

    public function verify()
    {
        $result = checkVerifyToken($this->verification_code);
        if ($result) {
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET activated = 1 WHERE verification_code = :verification_code");
            $statement->bindValue(":verification_code", $this->verification_code);
            $statement->execute();
            return true;
        } else {
            return false;
        }
    }
}