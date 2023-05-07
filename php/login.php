<?php
include_once("../inc/bootstrap.php"); // This is the bootstrap file
include_once("../inc/functions.inc.php"); // This is the functions file


if (isset($_POST['email']) && isset($_POST['password'])) { // if email and password are set
    $login = new User(); // create a new instance of the User class
    $login->setEmail($_POST['email']); // set the email using the setEmail method
    $login->setPassword($_POST['password']); // set the password using the setPassword method
    $login->doLogin(); // do login
    $error = $login->getError(); // get error message
}

if (isset($_SESSION['message'])) { // if message is set
    $worked = $_SESSION['message']; // set message
    unset($_SESSION['message']); // clear the message so it only displays once
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" />
</head>

<body>
    <?php include_once("../inc/nav.inc.php"); ?> <!-- This is the nav bar -->

    <div id="app">
        <h1 class="titlelogin">PromptSwap</h1>
        <nav class="nav--login">
            <a href="../php/login.php" id="tabLogin">Log in</a>
            <a href="../php/signup.php" id="tabSignIn">Sign up</a>
        </nav>

        <?php if (isset($error)) : ?> <!-- If there is an error, display it -->
            <div class="alert"><?php echo $error ?></div> <!-- Display the error -->
        <?php endif; ?>

        <?php if (isset($worked)) : ?> <!-- If there is a message, display it -->
            <div class="alert"><?php echo $worked ?></div> <!-- Display the message -->
        <?php endif; ?>

        <form class="form form--login" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"> <!-- Form for logging in -->
            <label for="email">Email</label>
            <input type="text" id="email" name="email">

            <label for="password">Password</label>
            <input type="password" id="password" name="password">
            <a href="resetPassword.php"> Forgot Password?</a>
            <button type="submit" class="btn" id="btnsignup">Log In</button>
        </form>

    </div>


    <?php include_once("../inc/foot.inc.php"); ?> <!-- This is the footer -->
</body>

</html>