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
  <title>Homepage</title>
  <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css">
  <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" />
</head>

<body>
  <?php include_once("../inc/nav.inc.php"); ?>

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
        <a class="button" href="../php/marketplace.php">Find a prompt</a>
        <a class="button" href="../php/upload.php">Upload a prompt</a>
      </div>
    </div>

    <div class="imgheader"></div>
  </div>

  <?php include_once("../inc/foot.inc.php"); ?> <!-- include the footer -->
</body>

</html>