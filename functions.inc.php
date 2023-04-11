<?php
    
	function canLogin($email, $password){

		$conn = new PDO('mysql:host=localhost;dbname=promptswap', "evi", "12345");

		$statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
		$statement->bindValue(":email", $email);
		$statement->execute();
		$user = $statement->fetch(PDO::FETCH_ASSOC);
		$hash = $user['password'];

		if(password_verify($password, $hash)){
			return true;
		} else {
			return false;
		}

	}
   
?>