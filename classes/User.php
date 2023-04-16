<?php

include_once("../inc/bootstrap.php"); // include bootstrap file

class User { // class User
    private $email; // email

    public function __construct($email) { // constructor
        $this->email = $email; // set email
    }
    
    public function getProfilePicture() { // get profile picture
        $conn = Db::getInstance(); // get database connection
        $query = $conn->prepare("SELECT profile_picture FROM users WHERE email = :email"); // get profile picture from database
        $query->bindValue(":email", $this->email); // bind email to query
        $query->execute(); // execute query
        $profile_picture = $query->fetchColumn(); // fetch data from query
        return !empty($profile_picture) ? $profile_picture : "../media/pickachu.png"; // return profile picture or default profile picture
    }

    public function getProfileBanner() { // get profile banner
        $conn = Db::getInstance(); // get database connection
        $query = $conn->prepare("SELECT profile_banner FROM users WHERE email = :email"); // get profile banner from database
        $query->bindValue(":email", $this->email); // bind email to query
        $query->execute(); // execute query 
        $profile_banner = $query->fetchColumn(); // fetch data from query
        return !empty($profile_banner) ? $profile_banner : "../media/achtergrond.jpg"; // return profile banner or default profile banner
    }

    public function getId() { // get id
        $conn = Db::getInstance(); // get database connection
        $query = $conn->prepare("SELECT id FROM users WHERE email = :email"); // get id from database
        $query->bindValue(":email", $this->email); // bind email to query
        $query->execute(); // execute query
        return $query->fetchColumn(); // fetch data from query
    }

    public function getBio() { // get bio
        $conn = Db::getInstance(); // get database connection
        $query = $conn->prepare("SELECT bio FROM users WHERE email = :email"); // get bio from database
        $query->bindValue(":email", $this->email); // bind email to query
        $query->execute(); // execute query
        return $query->fetchColumn(); // fetch data from query
    }

    public function getUsername() { // get username
        $conn = Db::getInstance(); // get database connection
        $query = $conn->prepare("SELECT username FROM users WHERE email = :email"); // get username from database
        $query->bindValue(":email", $this->email); // bind email to query
        $query->execute(); // execute query
        return $query->fetchColumn(); // fetch data from query
    }
}