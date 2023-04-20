<?php

include_once("../inc/bootstrap.php"); // include bootstrap file
include_once("../inc/functions.inc.php"); // include functions file

class Login
{
    private $email; // email
    private $password; // password
    private $error; // error message

    public function __construct(string $email, string $password) // constructor with type declarations
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
                $_SESSION['user_id'] = getUserId($this->email); // set user id

                header('Location: ../php/index.php'); // redirect to index page
                exit(); // exit script
            }
        } catch (Throwable $e) { // if login is not successful
            $this->error = $e->getMessage(); // set error message
        }
    }

    public function getError(): ?string // get error message with return type declaration
    {
        return $this->error; // return error message
    }
}

if (isset($_POST['email']) && isset($_POST['password'])) { // if email and password are set
    $login = new Login($_POST['email'], $_POST['password']); // create new login object
    $login->doLogin(); // do login
    $error = $login->getError(); // get error message
}

if (isset($_SESSION['message'])) { // if message is set
    $worked = $_SESSION['message']; // set message
    unset($_SESSION['message']); // clear the message so it only displays once
}
