<?php
// PasswordVerifier class
class PasswordVerifier
{
    private $password;
    private $error;
    private $hashedPassword;

    public function __construct($password)
    {
        $this->password = $password;
        $this->verifyPassword();
    }

    public function isStrong()
    {
        return empty($this->error);
    }

    public function getError()
    {
        return $this->error;
    }

    public function getHashedPassword()
    {
        return $this->hashedPassword;
    }

    private function verifyPassword()
    {
        $uppercase = preg_match('@[A-Z]@', $this->password); // check if password contains uppercase letter 
        $lowercase = preg_match('@[a-z]@', $this->password); // check if password contains lowercase letter
        $number    = preg_match('@[0-9]@', $this->password); // check if password contains number
        $specialChars = preg_match('@[^\w]@', $this->password); // check if password contains special character

        // if password does not meet strength requirements
        if (!$uppercase) { // if password does not contain uppercase letter
            $this->error = "Password should include at least one upper case letter.";
        } elseif (!$lowercase) { // if password does not contain lowercase letter
            $this->error = "Password should include at least one lower case letter.";
        } elseif (!$number) { // if password does not contain number
            $this->error = "Password should include at least one number.";
        } elseif (!$specialChars) { // if password does not contain special character
            $this->error = "Password should include at least one special character.";
        } elseif (strlen($this->password) < 8) { // if password is shorter than 8 characters
            $this->error = "Password should be at least 8 characters in length.";
        } else {
            $this->hashedPassword = $this->password;
        }
    }
}
