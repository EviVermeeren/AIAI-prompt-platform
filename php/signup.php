<?php

include_once("../inc/bootstrap.php"); // include bootstrap file
$config = parse_ini_file('../config/config.ini', true); // include config file
$key = $config['keys']['sendgridapikey']; // get sendgrid api key

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // if form is submitted

  $email = $_POST["email"];
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $username = $_POST["username"];

  $passwordVerifier = new PasswordVerifier($_POST['password']);
  if (!$passwordVerifier->isStrong()) { // if password does not meet strength requirements
    $error = $passwordVerifier->getError(); // set error message
  } else {
    $password = $passwordVerifier->getHashedPassword(); // get hashed password
    $options = [ // set options for password hashing
      'cost' => 12, // set cost to 12
    ];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT, $options); // hash password

  }

  try {
    $conn = Db::getInstance(); // Connect to database

    $user = new User($email, $conn, $password, $firstname, $lastname, $username);

    if ($user->checkEmailAndUsername($email, $username)) {
      $verification_code = uniqid(); // Generate verification code for email verification 

      $user->setEmail($email);
      $user->setFirstName($firstname);
      $user->setLastName($lastname);
      $user->setUsername($username);
      $user->setVerificationCode($verification_code);
      $user->setPassword($password);
      $user->save();

      $emailSender = new EmailSender($key);
      $emailSender->sendEmail(
        "evivermeeren@hotmail.com",
        $_POST['email'],
        "Verify your email address",
        "Hi {$_POST['username']}! Please activate your email. Here is the activation link http://localhost/AIAI-prompt-platform-main/php/verify.php?verification_code=$verification_code",
        "Hi {$_POST['username']}! Please activate your email. <strong>Here is the activation link:</strong> http://localhost/AIAI-prompt-platform-main/php/verify.php?verification_code=$verification_code"
      );

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
        <input type="text" id="firstname" name="firstname" value="<?php echo isset($_POST['firstname']) ? Sanitizer::sanitize($_POST['firstname']) : ''; ?>" required />

        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo isset($_POST['lastname']) ? Sanitizer::sanitize($_POST['lastname']) : ''; ?>" required />

        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? Sanitizer::sanitize($_POST['username']) : ''; ?>" required />

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? Sanitizer::sanitize($_POST['email']) : ''; ?>" required />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />

        <input type="submit" value="Sign Up" id="btnsignup" class="btn btn--primary" />
      </form>
    </div>
  </div>

  <?php include_once("../inc/foot.inc.php"); ?> <!-- Include footer -->
</body>

</html>