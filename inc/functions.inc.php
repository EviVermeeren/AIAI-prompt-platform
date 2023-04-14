<?php
    
	function canLogin($email, $password) {
		$conn = Db::getInstance();
	
		$statement = $conn->prepare("SELECT password, activated FROM users WHERE email = :email");
		$statement->bindValue(":email", $email);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);
	
		if (!$result) {
			// Email not found in database
			throw new Exception("Email not found");
		} else {
			$hash = $result['password'];
			$activated = $result['activated'];
			if($activated == 0) {
				// Account not activated
				throw new Exception("Account not activated");
			} else {
				// Account activated
				if (password_verify($password, $hash)) {
					return true;
				} else {
					throw new Exception("Incorrect password");
				}
			}
		}
	}

	function checkVerifyToken($verification_code)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE verification_code = :verification_code");
        $statement->bindValue(":verification_code", $verification_code);
        $statement->execute();
        $result = $statement->fetch();
        return $result;
    }
   
?>