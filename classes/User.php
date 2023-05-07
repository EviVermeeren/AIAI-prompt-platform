<?php

class User
{ // class User
    private $email; // email
    private string $resetToken; // reset token
    private string $password; // password
    private $conn;
    private $firstname;
    private $lastname;
    private $username;
    private $verification_code;

    public function __construct($email, $conn)
    { // constructor
        $this->email = $email; // set email
        $this->conn = Db::getInstance();
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
        $this->setPassword($password); // set password after insert
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
        //check if email is valid
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;

            return $this;
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

        return $this;
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

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        $this->password = $password;

        return $this;
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
}
