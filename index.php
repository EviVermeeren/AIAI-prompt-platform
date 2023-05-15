<?php

include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Sanitizer.php");
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Prompt.php");

session_start();

$user = new User();
if (!$user->isAuthenticated()) {
  $user->redirectToLogin();
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
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>" />
</head>

<body>
  <?php

  $conn = Db::getInstance(); // connect to the database

  if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
  }
  $sql = "SELECT admin FROM users WHERE id = :user_id"; // SQL with parameters
  $admin = $conn->prepare($sql); // prepare the query
  $admin->bindParam(':user_id', $user_id); // bind the parameter
  $admin->execute(); // execute the query 
  $row = $admin->fetch(PDO::FETCH_ASSOC);  // fetch the row

  $searchTerm = isset($_GET["q"]) ? trim($_GET["q"]) : ""; // get the search term from the URL
  ?>

  <link rel="stylesheet" href="/css/style.css">


  <nav>
    <div class="logo">
      <a href="/AIAI-prompt-platform-main/php/marketplace.php">PromptSwap</a>
    </div>

    <div>
      <form class="nav-form" method="get" action="searchPrompt.php">
        <label for="search-data"></label>
        <input class="search-data" type="search" id="search-data" name="q" placeholder="Search here..." required value="<?= htmlspecialchars($searchTerm) ?>"> <!-- Display the search term in the search bar -->
        <button class="search-button" type="submit" id="search-button" name="submit">Go</button>
      </form>
    </div>

    <div class="nav-items">
      <li><a href="/AIAI-prompt-platform-main/php/marketplace.php">Marketplace</a></li>
      <li><a href="/AIAI-prompt-platform-main/php/upload.php">Upload</a></li>
      <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) : ?> <!-- if user is not logged in -->
        <li><a href="/AIAI-prompt-platform-main/php/login.php">Login</a></li>
      <?php else : ?> <!-- if user is logged in -->
        <li><a href="/AIAI-prompt-platform-main/php/account.php">Profile</a></li>
        <li><a href="/AIAI-prompt-platform-main/php/logout.php">Logout</a></li>

        <?php if ($row["admin"] == 1) :
        ?> <!-- if user is an admin -->
          <li><a href="/AIAI-prompt-platform-main/php/approvalList.php">Approvals</a></li>
          <li><a href="/AIAI-prompt-platform-main/php/report.php">Reports</a></li>
        <?php endif;
        ?>
      <?php endif; ?>


    </div>
  </nav>

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
        <a class="button" href="/AIAI-prompt-platform-main/php/marketplace.php">Find a prompt</a>
        <a class="button" href="/AIAI-prompt-platform-main/php/upload.php">Upload a prompt</a>
      </div>
    </div>

    <div class="imgheader"></div>
  </div>

  <br><br><br><br><br><br>

  <footer>
    <div class="footer">
      <div class="footer-item-1">
        <h4>PromptSwap</h4>
        <p>
          Stable diffusion, Lexica, DALL-E and midjourney tradingplace. Upload
          and find prompts to get better results and save time and money.
        </p>
      </div>

      <div class="footer-item-2">
        <h5>Menu</h5>
        <ul>
          <li><a href="/AIAI-prompt-platform-main/php/marketplace.php">Marketplace</a></li>
          <li><a href="/AIAI-prompt-platform-main/php/upload.php">Upload</a></li>
          <li><a href="/AIAI-prompt-platform-main/php/account.php">Account</a></li>
          <li><a href="/AIAI-prompt-platform-main/php/users.php">Find other users</a></li>
        </ul>
      </div>

      <div class="footer-item-2">
        <h5>Socials</h5>
        <ul>
          <li><a href="#">Facebook</a></li>
          <li><a href="#">Instagram</a></li>
          <li><a href="#">YouTube</a></li>
        </ul>
      </div>

      <div class="footer-item-3">
        <h5 class="h7">Subscribe to newsletter</h5>
        <form action="#">
          <input type="email" class="email" placeholder="Your email..." required />
          <button type="submit" class="fas fa-search">Subscribe</button>

      </div>
  </footer>
</body>

</html>