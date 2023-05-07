<?php

$config = parse_ini_file('../config/config.ini', true);

class User
{
    private $email;
    private string $resetToken;
    private string $password;
    private $conn;
    private $firstname;
    private $lastname;
    private $username;
    private $verification_code;
    private $error;
    private $key;
    private $hashedPassword;

    public function isAuthenticated()
    {
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    }

    public function redirectToLogin()
    {
        header('Location: ../php/login.php');
        exit;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        $this->verifyPassword();
    }

    public function isStrong()
    {
        return empty($this->error);
    }

    public function getHashedPassword()
    {
        return $this->hashedPassword;
    }

    private function verifyPassword()
    {
        $uppercase = preg_match('@[A-Z]@', $this->password);
        $lowercase = preg_match('@[a-z]@', $this->password);
        $number = preg_match('@[0-9]@', $this->password);
        $specialChars = preg_match('@[^\w]@', $this->password);

        if (!$uppercase) {
            $this->error = "Password should include at least one upper case letter.";
        } elseif (!$lowercase) {
            $this->error = "Password should include at least one lower case letter.";
        } elseif (!$number) {
            $this->error = "Password should include at least one number.";
        } elseif (!$specialChars) {
            $this->error = "Password should include at least one special character.";
        } elseif (strlen($this->password) < 8) {
            $this->error = "Password should be at least 8 characters in length.";
        } else {
            $options = [
                'cost' => 12,
            ];
            $this->hashedPassword = password_hash($this->password, PASSWORD_DEFAULT, $options);
        }
    }

    public function setKey($key)
    {
        $this->key = $key;
        require_once('../vendor/autoload.php');
    }

    public function sendEmail($from, $to, $subject, $text_content, $html_content)
    {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom($from);
        $email->setSubject($subject);
        $email->addTo($to);
        $email->addContent("text/plain", $text_content);
        $email->addContent("text/html", $html_content);
        $sendgrid = new \SendGrid($this->key);
        try {
            $response = $sendgrid->send($email);
            return $response->statusCode();
        } catch (Exception $e) {
            return false;
        }
    }

    public function setConnection($conn)
    {
        $this->conn = $conn;
    }

    public function initialize($conn)
    {
        $this->setConnection($conn);
    }

    public function doLogin()
    {
        try {
            if (canLogin($this->email, $this->password)) {
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $this->email;
                $_SESSION['user_id'] = getUserId($this->email);

                header('Location: ../php/index.php');
                exit();
            }
        } catch (Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function getError(): ?string // get error message with return type declaration
    {
        return $this->error; // return error message
    }

    public function verify($verification_code)
    {
        $result = checkVerifyToken($verification_code); // check if verification code is valid
        if ($result) { // if verification code is valid
            $statement = $this->conn->prepare("UPDATE users SET activated = 1 WHERE verification_code = :verification_code"); // update database
            $statement->bindValue(":verification_code", $verification_code); // bind verification code to query
            $statement->execute(); // execute query
            return true; // return true
        } else {
            return false; // return false
        }
    }

    public function checkEmailAndUsername($email, $username)
    {
        $query = $this->conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
        $query->bindValue(":email", $email);
        $query->bindValue(":username", $username);
        $query->execute();
        $rows = $query->rowCount();
        return $rows === 0;
    }

    public function insert($email, $password, $firstname, $lastname, $username, $verification_code)
    {
        $query = $this->conn->prepare("INSERT INTO users (email, password, firstname, lastname, username, profile_picture, profile_banner, verification_code) VALUES (:email, :password, :firstname, :lastname, :username, '../media/pickachu.png', '../media/achtergrond.jpg', :verification_code)");
        $query->bindValue(":email", $email);
        $query->bindValue(":password", $password);
        $query->bindValue(":firstname", $firstname);
        $query->bindValue(":lastname", $lastname);
        $query->bindValue(":username", $username);
        $query->bindValue(":verification_code", $verification_code);
        $query->execute();
        $this->setEmail($email); // set email after insert
        $this->setFirstName($firstname); // set firstname after insert
        $this->setLastName($lastname); // set lastname after insert
        $this->setUsername($username); // set username after insert
        $this->setVerificationCode($verification_code); // set verification code after insert
    }

    public function setFirstName($firstname)
    {
        $this->firstname = $firstname;
    }

    public function setLastName($lastname)
    {
        $this->lastname = $lastname;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setVerificationCode($verification_code)
    {
        $this->verification_code = $verification_code;
    }

    public function save()
    {
        $this->insert($this->email, $this->password, $this->firstname, $this->lastname, $this->username, $this->verification_code);
    }

    public function getProfilePicture()
    { // get profile picture
        $conn = Db::getInstance(); // get database connection
        $query = $conn->prepare("SELECT profile_picture FROM users WHERE email = :email"); // get profile picture from database
        $query->bindValue(":email", $this->email); // bind email to query
        $query->execute(); // execute query
        $profile_picture = $query->fetchColumn(); // fetch data from query
        return !empty($profile_picture) ? $profile_picture : "../media/pickachu.png"; // return profile picture or default profile picture
    }

    public function getProfileBanner()
    { // get profile banner
        $conn = Db::getInstance(); // get database connection
        $query = $conn->prepare("SELECT profile_banner FROM users WHERE email = :email"); // get profile banner from database
        $query->bindValue(":email", $this->email); // bind email to query
        $query->execute(); // execute query 
        $profile_banner = $query->fetchColumn(); // fetch data from query
        return !empty($profile_banner) ? $profile_banner : "../media/achtergrond.jpg"; // return profile banner or default profile banner
    }

    public function getId()
    { // get id
        $conn = Db::getInstance(); // get database connection
        $query = $conn->prepare("SELECT id FROM users WHERE email = :email"); // get id from database
        $query->bindValue(":email", $this->email); // bind email to query
        $query->execute(); // execute query
        return $query->fetchColumn(); // fetch data from query
    }

    public function getBio()
    { // get bio
        $conn = Db::getInstance(); // get database connection
        $query = $conn->prepare("SELECT bio FROM users WHERE email = :email"); // get bio from database
        $query->bindValue(":email", $this->email); // bind email to query
        $query->execute(); // execute query
        return $query->fetchColumn(); // fetch data from query
    }

    public function getUsername()
    { // get username
        $conn = Db::getInstance(); // get database connection
        $query = $conn->prepare("SELECT username FROM users WHERE email = :email"); // get username from database
        $query->bindValue(":email", $this->email); // bind email to query
        $query->execute(); // execute query
        return $query->fetchColumn(); // fetch data from query
    }

    public function checkEmail($email)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        //if result is 1, email is already in use, else email is not in use
        if ($result) {
            return true;
        } else {
            throw new Exception("Email is not in use.");
        }
    }


    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception("Email is not valid.");
        }
    }

    /**
     * Get the value of resetToken
     */
    public function getResetToken()
    {
        return $this->resetToken;
    }

    /**
     * Set the value of resetToken
     *
     * @return  self
     */
    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;
    }

    public function saveResetToken()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET reset_token = :token, tstamp= :tstamp WHERE email = :email");
        $statement->bindValue(":token", $this->resetToken);
        $statement->bindValue(":tstamp", time());
        $statement->bindValue(":email", $this->email);
        $result = $statement->execute();
        return $result;
    }
    //$email->addContent("text/plain", "Hi! Please reset your password. Here is the reset link http://localhost/AIAI-prompt-platform-main-echte/create-new-password.php?token=$token");
    public function sendResetMail($key)
    {
        $token = $this->resetToken;


        // send an email to the user
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("evivermeeren@hotmail.com", "PromptSwap");
        $email->setSubject("Reset email");
        $email->addTo($this->email);
        $email->addContent("text/plain", "Hi! Please reset your password. Here is the reset link http://localhost/AIAI-prompt-platform-main/php/reset.php?token=$token");
        $email->addContent(
            "text/html",
            "Hi! Please reset your password. <strong>Here is the reset link :</strong> http://localhost/AIAI-prompt-platform-main/php/reset.php?token=$token"
        );

        $sendgrid = new \SendGrid($key);

        try {
            $response = $sendgrid->send($email);
            return true;
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
            return false;
        }

        exit();
    }
    public function checkResetToken()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE reset_token = :token");
        $statement->bindValue(":token", $this->resetToken);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        //if result is 1, token is valid, else token is not valid
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function checkTimestamp()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT tstamp FROM users WHERE reset_token = :token");
        $statement->bindValue(":token", $this->resetToken);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $result = $result['tstamp'];

        //if result is 1, token is valid, else token is not valid
        if (time() - $result < 86400) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }



    public function updatePassword()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET password = :password, reset_token = NULL, tstamp = NULL WHERE reset_token = :token");
        $statement->bindValue(":password", $this->password); // hier ga je die op null zetten dan is het cleaner
        $statement->bindValue(":token", $this->resetToken);
        $result = $statement->execute();
        return $result;
    }

    public static function getDefaultPictures()
    { // get default pictures
        return array( // return array
            "../media/default1.jpg",
            "../media/default2.jpg",
            "../media/default3.jpg",
            "../media/default4.jpg",
            "../media/default5.jpg",
            "../media/pickachu.png"
        );
    }
}
