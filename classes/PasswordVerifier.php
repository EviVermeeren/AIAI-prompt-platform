<?php
class PasswordVerifier
{
    private $password;
    private $error;
    private $hashedPassword;

    public function setPassword($password)
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
}
