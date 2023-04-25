<?php

include_once("../inc/bootstrap.php"); // include bootstrap file
$config = parse_ini_file('../config/config.ini', true); // include config file
$key = $config['keys']['sendgridapikey']; // get sendgrid api key

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // if form is submitted
  $email = htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8'); // get email from form, and sanitize it to prevent XSS attacks 
  $firstname = htmlspecialchars($_POST["firstname"], ENT_QUOTES, 'UTF-8'); // get firstname from form, and sanitize it to prevent XSS attacks
  $lastname = htmlspecialchars($_POST["lastname"], ENT_QUOTES, 'UTF-8'); // get lastname from form, and sanitize it to prevent XSS attacks
  $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8'); // get username from form, and sanitize it to prevent XSS attacks

  $passwordVerifier = new PasswordVerifier($_POST['password']);
  if (!$passwordVerifier->isStrong()) { // if password does not meet strength requirements
    $error = $passwordVerifier->getError(); // set error message
  } else {
    $password = $passwordVerifier->getHashedPassword(); // get hashed password
    $options = [ // set options for password hashing
      'cost' => 12, // set cost to 12
    ];

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT, $options); // hash password

    try {

      $conn = Db::getInstance(); // Connect to database

      // Check if email or username is already in use
      $query = $conn->prepare("SELECT (SELECT COUNT(*) FROM users WHERE email = :email) as email_count, (SELECT COUNT(*) FROM users WHERE username = :username) as username_count"); // prepare query
      $query->bindValue(":email", $email, PDO::PARAM_STR); // bind email to query, and set type to string, to prevent SQL injection
      $query->bindValue(":username", $username, PDO::PARAM_STR); // bind username to query, and set type to string, to prevent SQL injection
      $query->execute(); // execute query
      $result = $query->fetch(PDO::FETCH_ASSOC); // get result

      $email_count = $result['email_count'];
      $username_count = $result['username_count'];

      // If email and username are not in use and are valid, create a new user
      if ($email_count == 0 && $username_count == 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) { // if email and username are not in use and are valid, create a new user, and set type to string, to prevent SQL injection

        $verification_code = uniqid(); // Generate verification code for email verification 

        // Insert user into database with verification code
        $query = $conn->prepare("INSERT INTO users (email, password, firstname, lastname, username, profile_picture, profile_banner, verification_code) VALUES (:email, :password, :firstname, :lastname, :username, '../media/pickachu.png', '../media/achtergrond.jpg', :verification_code)"); // prepare query
        $query->bindValue(":email", $email, PDO::PARAM_STR); // bind email to query, and set type to string, to prevent SQL injection
        $query->bindValue(":password", $password, PDO::PARAM_STR); // bind password to query, and set type to string, to prevent SQL injection
        $query->bindValue(":firstname", $firstname, PDO::PARAM_STR); // bind firstname to query, and set type to string, to prevent SQL injection
        $query->bindValue(":lastname", $lastname, PDO::PARAM_STR); // bind lastname to query, and set type to string, to prevent SQL injection
        $query->bindValue(":username", $username, PDO::PARAM_STR); // bind username to query, and set type to string, to prevent SQL injection
        $query->bindValue(":verification_code", $verification_code, PDO::PARAM_STR); // bind verification code to query, and set type to string, to prevent SQL injection
        $query->execute(); // execute query

        // Send email to user
        require '../vendor/autoload.php'; // include sendgrid library

        $email = new \SendGrid\Mail\Mail(); // create new email
        $email->setFrom("evivermeeren@hotmail.com", "PromptSwap"); // set sender 
        $email->setSubject("Verify your email address"); // set subject
        $email->addTo($_POST['email'], $_POST['username']); // set recipient
        $email->addContent("text/plain", "Hi $username! Please activate your email. Here is the activation link http://localhost/AIAI-prompt-platform-main/php/verify.php?verification_code=$verification_code"); // set content
        $email->addContent(
          "text/html",
          "Hi $username! Please activate your email. <strong>Here is the activation link:</strong> http://localhost/AIAI-prompt-platform-main/php/verify.php?verification_code=$verification_code"
        ); // set content
        $sendgrid = new \SendGrid($key); // create new sendgrid object
        try { // try to send email
          $response = $sendgrid->send($email);
          print $response->statusCode() . "\n";
          print_r($response->headers());
          print $response->body() . "\n";
        } catch (Exception $e) { // if email could not be sent, print error
          echo 'Caught exception: ' . $e->getMessage() . "\n";
        }

        // Redirect to login page
        header('Location: ../php/emailsent.php');
        exit;
      } else { // if email or username is already in use, set error message
        $error_messages = array();
        if ($email_count > 0) {
          $error_messages[] = "Email is already in use.";
        }
        if ($username_count > 0) {
          $error_messages[] = "Username is already in use.";
        }
        $error = implode(" ", $error_messages);
      }
    } catch (PDOException $e) { // if database error, set error message
      $error = "Database error: Please try again.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up</title>
  <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
  <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" />
</head>

<body>
  <?php include_once("../inc/nav.inc.php"); ?> <!-- Include navigation bar -->

  <div id="app">
    <h1 class="titlelogin">PromptSwap</h1>
    <nav class="nav--login">
      <a href="../php/login.php" id="tabLogin">Log in</a>
      <a href="#" id="tabSignIn" class="active">Sign up</a>
    </nav>

    <?php if (isset($error)) : ?> <!-- If error, show error message -->
      <div class="form__error">
        <p><?php echo $error; ?></p>
      </div>
    <?php endif; ?>

    <div class="form form--login">
      <form method="post">
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" required />

        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" required />

        <label for="username">Username</label>
        <input type="text" id="username" name="username" required />

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />

        <input type="submit" value="Sign Up" id="btnsignup" class="btn btn--primary" />
      </form>
    </div>
  </div>

  <?php include_once("../inc/foot.inc.php"); ?> <!-- Include footer -->
</body>

</html>