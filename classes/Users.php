<?php
// User.php
class Users
{
    private $conn;
    private $email;
    private $password;
    private $firstname;
    private $lastname;
    private $username;
    private $verification_code;

    public function __construct($conn)
    {
        $this->conn = $conn;
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

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
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
}
