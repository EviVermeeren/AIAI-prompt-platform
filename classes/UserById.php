<?php
class UserById
{
    private $id;
    private $email;
    private $username;
    private $profile_picture;
    private $profile_banner;
    private $bio;

    public function __construct($id)
    {
        $conn = Db::getInstance();
        $query = "SELECT * FROM users WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!$user) {
            throw new Exception("User not found.");
        }

        $this->id = $id;
        $this->email = $user['email'];
        $this->username = $user['username'];
        $this->profile_picture = $user['profile_picture'];
        $this->profile_banner = $user['profile_banner'];
        $this->bio = $user['bio'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getProfilePicture()
    {
        return $this->profile_picture;
    }

    public function getProfileBanner()
    {
        return $this->profile_banner;
    }

    public function getBio()
    {
        return $this->bio;
    }
}
