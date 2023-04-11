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
    <title>Homepage</title>
    <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css">
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
  <?php include_once("nav.inc.php"); ?>

    <div class="header">
      <div>
        <div>
          <h1>Stable Diffusion, Lexica, DALL-E & Midjourney</h1>

          <h2>Prompt tradingplace</h2>

          <h3>
            Upload and find prompts to get better results to save time and
            money.
          </h3>
        </div>
        <div class="buttondiv">
          <a class="button" href="marketplace.php">Find a prompt</a>
          <a class="button" href="upload.php">Upload a prompt</a>
        </div>
      </div>

      <div class="imgheader"></div>
    </div>

    <?php include_once("foot.inc.php"); ?>
  </body>
</html>
