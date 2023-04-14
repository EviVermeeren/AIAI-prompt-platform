<?php 

include_once("../inc/bootstrap.php");
$config = parse_ini_file('../config/config.ini', true);
$key = $config['keys']['sendgridapikey'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $email = htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
  $firstname = htmlspecialchars($_POST["firstname"], ENT_QUOTES, 'UTF-8');
  $lastname = htmlspecialchars($_POST["lastname"], ENT_QUOTES, 'UTF-8');
  $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');

  // Check if password meets strength requirements
  $password = $_POST['password'];
  $uppercase = preg_match('@[A-Z]@', $password);
  $lowercase = preg_match('@[a-z]@', $password);
  $number    = preg_match('@[0-9]@', $password);
  $specialChars = preg_match('@[^\w]@', $password);

  if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
      $error = "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
  } else {
    $options = [
      'cost' => 12,
    ];

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT, $options);

    try {
      // Connect to database
      $conn = Db::getInstance();

      // Check if email is already in use
      $query = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
      $query->bindValue(":email", $email, PDO::PARAM_STR);
      $query->execute();
      $count = $query->fetchColumn();

      // If email is not in use and is valid, create a new user
      if ($count == 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Generate verification code
        $verification_code = uniqid();

        // Insert user into database with verification code
        $query = $conn->prepare("INSERT INTO users (email, password, firstname, lastname, username, profile_picture, profile_banner, verification_code) VALUES (:email, :password, :firstname, :lastname, :username, '../media/pickachu.png', '../media/achtergrond.jpg', :verification_code)");
        $query->bindValue(":email", $email, PDO::PARAM_STR);
        $query->bindValue(":password", $password, PDO::PARAM_STR);
        $query->bindValue(":firstname", $firstname, PDO::PARAM_STR);
        $query->bindValue(":lastname", $lastname, PDO::PARAM_STR);
        $query->bindValue(":username", $username, PDO::PARAM_STR);
        $query->bindValue(":verification_code", $verification_code, PDO::PARAM_STR);
        $query->execute();

        // Send email to user
        require '../vendor/autoload.php';

        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("evivermeeren@hotmail.com", "Example User");
        $email->setSubject("Verify your email address");
        $email->addTo($_POST['email'], $_POST['username']);
        $email->addContent("text/plain", "Hi $username! Please activate your email. Here is the activation link http://localhost/promptswap/AIAI-prompt-platform-main/php/verify.php?verification_code=$verification_code");
        $email->addContent(
            "text/html", "Hi $username! Please activate your email. <strong>Here is the activation link:</strong> http://localhost/promptswap/AIAI-prompt-platform-main/php/verify.php?verification_code=$verification_code"
        );
        $sendgrid = new \SendGrid($key);
        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }

        // Redirect to login page
        header('Location: ../php/emailsent.php');
        exit;
      } else {
        $error = "Invalid email or email already in use.";
      }
    } catch (PDOException $e) {
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
    <link rel="stylesheet" href="../css/style.css" />
  </head>
  <body>
    <nav>
      <div class="logo">
        <a href="index.php">PromptSwap</a>
      </div>

      <div>
        <form action="#">
          <input
            type="search"
            class="search-data"
            placeholder="Search here..."
            required
          />
          <button type="submit" class="fas fas-search">→</button>
        </form>
      </div>

      <div class="nav-items">
        <li><a href="../php/marketplace.php">Marketplace</a></li>
        <li><a href="../php/upload.php">Upload</a></li>
        <li><a href="../php/login.php">Login</a></li>
      </div>
    </nav>

    <div id="app">
      <h1 class="titlelogin">PromptSwap</h1>
      <nav class="nav--login">
        <a href="../php/login.php" id="tabLogin">Log in</a>
        <a href="#" id="tabSignIn" class="active">Sign up</a>
      </nav>

      <?php if (isset($error)): ?>
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

          <input type="submit" value="Sign Up" class="btn btn--primary" />
        </form>
      </div>
    </div>

    <?php include_once("../inc/foot.inc.php"); ?>
  </body>
</html>