<?php 

include_once("../inc/bootstrap.php"); // include bootstrap file
include_once("../inc/functions.inc.php"); // include functions file

class Login
{
    private $email; // email
    private $password; // password
    private $error; // error message

    public function __construct($email, $password) // constructor
    {
        $this->email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); // set email, and sanitize it to prevent XSS
        $this->password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8'); // set password, and sanitize it to prevent XSS
    }

    public function doLogin() // do login
    {
        try { // try to login
            if (canLogin($this->email, $this->password)) { // if login is successful
                session_start(); // start session
                $_SESSION['loggedin'] = true; // set loggedin to true
                $_SESSION['email'] = $this->email; // set email

                header('Location: ../php/index.php'); // redirect to index page
                exit(); // exit script
            }
        } catch (Throwable $e) { // if login is not successful
            $this->error = $e->getMessage(); // set error message
        } 
    }

    public function getError() // get error message
    {
        return $this->error; // return error message
    }
}