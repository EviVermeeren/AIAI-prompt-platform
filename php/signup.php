<?php

include_once("../inc/bootstrap.php"); // include bootstrap file
$config = parse_ini_file('../config/config.ini', true); // include config file
$key = $config['keys']['sendgridapikey']; // get sendgrid api key

$email_value = isset($_POST["email"]) ? htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8') : '';
$firstname_value = isset($_POST["firstname"]) ? htmlspecialchars($_POST["firstname"], ENT_QUOTES, 'UTF-8') : '';
$lastname_value = isset($_POST["lastname"]) ? htmlspecialchars($_POST["lastname"], ENT_QUOTES, 'UTF-8') : '';
$username_value = isset($_POST["username"]) ? htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8') : '';

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

      $userChecker = new UserChecker($conn);
      if ($userChecker->checkEmailAndUsername($email, $username)) {

        $verification_code = uniqid(); // Generate verification code for email verification 

        $insertUser = new InsertUser($conn);
        $insertUser->insert($email, $password, $firstname, $lastname, $username, $verification_code);

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
      } else {
        $error = "Email or username already exists.";
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
        <input type="text" id="firstname" name="firstname" value="<?php echo $firstname_value; ?>" required />

        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo $lastname_value; ?>" required />

        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo $username_value; ?>" required />

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo $email_value; ?>" required />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />

        <input type="submit" value="Sign Up" id="btnsignup" class="btn btn--primary" />
      </form>
    </div>
  </div>

  <?php include_once("../inc/foot.inc.php"); ?> <!-- Include footer -->
</body>

</html>