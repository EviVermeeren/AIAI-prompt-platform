<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Account</title>
    <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
  <?php include_once("nav.inc.php"); ?>

    <div class="profile">

        <div class="profileimg">
            <img class="banner2" src="/achtergrond.jpg" alt="">
            <img class="pfp2" src="/pickachu.png" alt="">
        </div>
        
    <form class="editAccount">

        <label for="title">First name</label><br>
        <input class="inputfield" type="text" id="title" name="title" required><br><br>

        <label for="title">Last name</label><br>
        <input class="inputfield" type="text" id="title" name="title" required><br><br>

        <label for="title">Username</label><br>
        <input class="inputfield" type="text" id="title" name="title" required><br><br>

        <label for="title">Email</label><br>
        <input class="inputfield" type="text" id="title" name="title" required><br><br>
      
        <label for="title">Current password</label><br>
        <input class="inputfield" type="text" id="title" name="title" required><br><br>

        <label for="title">New password</label><br>
        <input class="inputfield" type="text" id="title" name="title" required><br><br>

        <label for="title">Repeat new password</label><br>
        <input class="inputfield" type="text" id="title" name="title" required><br><br>
      
        <input class="submitbtn" type="submit" value="Save profile">

    </form>

    <?php include_once("foot.inc.php"); ?>

    </body>
</html>