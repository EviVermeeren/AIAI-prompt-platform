<?php

include_once("../inc/bootstrap.php");

class User {
    private $email;

    public function __construct($email) {
        $this->email = $email;
    }

    public function getProfilePicture() {
        $conn = Db::getInstance();
        $query = $conn->prepare("SELECT profile_picture FROM users WHERE email = :email");
        $query->bindValue(":email", $this->email);
        $query->execute();
        $profile_picture = $query->fetchColumn();
        return !empty($profile_picture) ? $profile_picture : "../media/pickachu.png";
    }

    public function getProfileBanner() {
        $conn = Db::getInstance();
        $query = $conn->prepare("SELECT profile_banner FROM users WHERE email = :email");
        $query->bindValue(":email", $this->email);
        $query->execute();
        $profile_banner = $query->fetchColumn();
        return !empty($profile_banner) ? $profile_banner : "../media/achtergrond.jpg";
    }

    public function getBio() {
        $conn = Db::getInstance();
        $query = $conn->prepare("SELECT bio FROM users WHERE email = :email");
        $query->bindValue(":email", $this->email);
        $query->execute();
        return $query->fetchColumn();
    }

    public function getUsername() {
        $conn = Db::getInstance();
        $query = $conn->prepare("SELECT username FROM users WHERE email = :email");
        $query->bindValue(":email", $this->email);
        $query->execute();
        return $query->fetchColumn();
    }
}