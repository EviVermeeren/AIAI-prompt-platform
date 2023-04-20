<?php

function canLogin($email, $password)
{ // check if user can login
	$conn = Db::getInstance(); // get database connection

	$statement = $conn->prepare("SELECT id, password, activated FROM users WHERE email = :email"); // get password, activated and user_id from database
	$statement->bindValue(":email", $email); // bind email to query
	$statement->execute(); // execute query
	$result = $statement->fetch(PDO::FETCH_ASSOC); // fetch data from query

	if (!$result) { // if email is not found
		throw new Exception("Email not found"); // throw exception
	} else { // if email is found 
		$hash = $result['password']; // get password
		$activated = $result['activated']; // get activated
		if ($activated == 0) { // if account is not activated
			throw new Exception("Account not activated"); // throw exception
		} else { // if account is activated
			if (password_verify($password, $hash)) { // if password is correct
				return $result['id']; // return user_id
			} else {  // if password is incorrect
				throw new Exception("Incorrect password"); // throw exception
			}
		}
	}
}

function checkVerifyToken($verification_code) // check if verification code is valid
{
	$conn = Db::getInstance(); // get database connection
	$statement = $conn->prepare("SELECT * FROM users WHERE verification_code = :verification_code"); // get user from database
	$statement->bindValue(":verification_code", $verification_code); // bind verification code to query
	$statement->execute(); // execute query 
	$result = $statement->fetch(); // fetch data from query
	return $result; // return result
}

function getProfileImages($banner, $picture)
{ // get profile images
	$banner_src = !empty($banner) ? $banner : "../media/achtergrond.jpg"; // if banner is not empty, return banner, else return default banner
	$picture_src = !empty($picture) ? $picture : "../media/pickachu.png"; // if picture is not empty, return picture, else return default picture	 
	return array('banner' => $banner_src, 'picture' => $picture_src); // return array with banner and picture
}

function getUserID($email)
{
	$conn = Db::getInstance(); // get database connection
	$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
	$stmt->execute([$email]);

	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$result) {
		return null;
	}

	return $result['id'];
}
