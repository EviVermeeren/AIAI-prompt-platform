<?php
  include_once("../inc/bootstrap.php");
  include_once("../inc/functions.inc.php");

  if(isset($_POST['email']) && isset($_POST['password'])){
      $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
      $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
      
      try {
            if(canLogin($email, $password)){
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;

                header('Location: ../php/index.php');
                exit();
            } 
      } catch (Throwable $e) {
          $error = $e->getMessage();
      }
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
          <a href="../php/signup.php" id="tabSignIn">Sign up</a>
      </nav>
      
      <?php if(isset($error)): ?>
        <div class="alert"><?php echo $error ?></div>
      <?php endif; ?>

      <form class="form form--login" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="email">Email</label>
        <input type="text" id="email" name="email">

        <label for="password">Password</label>
        <input type="password" id="password" name="password">

        <button type="submit" class="btn" id="btnSubmit">Log In</button>
      </form>
      
    </div>


    <?php include_once("../inc/foot.inc.php"); ?>
  </body>
</html>
