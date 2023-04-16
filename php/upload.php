<?php

include_once("../inc/bootstrap.php"); // include bootstrap file

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { // if user is not logged in
  header('Location: ../php/login.php'); // redirect to login page
    exit; // exit script
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>upload your prompt</title>
    <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
    <link rel="stylesheet" href="../css/style.css" />
  </head>
  <body>
  <?php include_once("../inc/nav.inc.php"); ?> <!-- This is the nav bar -->

    
    <form class="uploadform">
      <h2>Upload a new prompt</h2>
    
      <label for="title">Title</label><br>
      <input class="inputfield" type="text" id="title" name="title" required><br><br>
    
      <label for="description">Description</label><br>
      <textarea style="resize: none;" class="inputfield2" id="description" name="description" required></textarea><br><br>
    
      <label for="model-type">Model</label><br>
      <select class="inputfield3" id="model-type" name="model-type">
        <option value="dalle">DALL-E</option>
        <option value="midjourney">Midjourney</option>
        <option value="lexica">Lexica</option>
        <option value="stablediffusion">Stable Diffusion</option>
      </select><br><br>
    
      <label for="image-upload">Image</label>
      <input class="inputfield4" type="file" id="image-upload" name="image-upload" accept="image/*" required><br><br>
    
      <input class="submitbtn" type="submit" value="Submit">
    </form>


    <?php include_once("../inc/foot.inc.php"); ?> <!-- This is the footer -->
  </body>
</html>
