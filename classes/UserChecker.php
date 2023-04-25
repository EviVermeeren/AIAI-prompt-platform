<?php

class UserChecker
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function checkEmailAndUsername($email, $username)
    {
        $query = $this->conn->prepare("SELECT (SELECT COUNT(*) FROM users WHERE email = :email) as email_count, (SELECT COUNT(*) FROM users WHERE username = :username) as username_count");
        $query->bindValue(":email", $email, PDO::PARAM_STR);
        $query->bindValue(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        $email_count = $result['email_count'];
        $username_count = $result['username_count'];

        return ($email_count == 0 && $username_count == 0 && filter_var($email, FILTER_VALIDATE_EMAIL));
    }
}
