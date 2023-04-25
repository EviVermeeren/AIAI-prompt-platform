<?php
class InsertUser
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function insert($email, $password, $firstname, $lastname, $username, $verification_code)
    {
        $query = $this->conn->prepare("INSERT INTO users (email, password, firstname, lastname, username, profile_picture, profile_banner, verification_code) VALUES (:email, :password, :firstname, :lastname, :username, '../media/pickachu.png', '../media/achtergrond.jpg', :verification_code)");
        $query->bindValue(":email", $email, PDO::PARAM_STR);
        $query->bindValue(":password", $password, PDO::PARAM_STR);
        $query->bindValue(":firstname", $firstname, PDO::PARAM_STR);
        $query->bindValue(":lastname", $lastname, PDO::PARAM_STR);
        $query->bindValue(":username", $username, PDO::PARAM_STR);
        $query->bindValue(":verification_code", $verification_code, PDO::PARAM_STR);
        $query->execute();
    }
}
