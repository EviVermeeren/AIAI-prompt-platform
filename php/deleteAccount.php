<?php
// delect an account completely form the database
include_once("../classes/User.php"); // include head
include_once("../classes/Db.php"); // include head

session_start();
if (isset($_SESSION['email'])) {
  $email = $_SESSION['email'];
  $conn = Db::getInstance();
  $statement = $conn->prepare("DELETE FROM users WHERE email = :email");
  $statement->bindValue(":email", $email);
  $statement->execute();
  session_destroy();
  header("Location: ../php/deleteAccount.php");
} else {
  //header("Location: ../php/editAccount.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="icon" type="image/x-icon" href="../media/favicon.ico">
</head>

<body>
  <nav>
    <div class="logo">
      <a href="../php/index.html">PromptSwap</a>
    </div>
  </nav>

  <div id="app">
    <h1 class="titlelogin">PromptSwap</h1>
    <nav class="nav--login"></nav>

    <form action="../php/editAccount.php" method="post">
      <button type="submit" name="delete">Delete account</button>
    </form>
  </div>
  <?php include_once("../inc/foot.inc.php"); ?>
</body>

</html>