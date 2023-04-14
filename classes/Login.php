<?php 

include_once("../inc/bootstrap.php");
include_once("../inc/functions.inc.php");

class Login
{
    private $email;
    private $password;
    private $error;

    public function __construct($email, $password)
    {
        $this->email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $this->password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
    }

    public function doLogin()
    {
        try {
            if (canLogin($this->email, $this->password)) {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $this->email;

                header('Location: ../php/index.php');
                exit();
            }
        } catch (Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function getError()
    {
        return $this->error;
    }
}