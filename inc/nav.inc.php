<?php
include_once("../inc/bootstrap.php");
include_once("../inc/functions.inc.php");

$conn = Db::getInstance(); // connect to the database

if (isset($_SESSION['user_id'])) { // if the user is logged in
  $user_id = $_SESSION['user_id']; // get the user ID from the session
}

// Query to check if the user is an admin
$sql = "SELECT admin FROM users WHERE id = :user_id"; // SQL with parameters
$admin = $conn->prepare($sql); // prepare the query
$admin->bindParam(':user_id', $user_id); // bind the parameter
$admin->execute(); // execute the query 
$row = $admin->fetch(PDO::FETCH_ASSOC);  // fetch the row

$searchTerm = isset($_GET["q"]) ? trim($_GET["q"]) : ""; // get the search term from the URL
?>

<link rel="stylesheet" href="../css/style.css">

<nav>
  <div class="logo">
    <a href="../php/marketplace.php">PromptSwap</a>
  </div>

  <div>
  <!-- Create a form for the search bar -->
  <form class="nav-form" method="get" action="searchPrompt.php">
    <label for="search-data"></label>
    <input class="search-data" type="search" id="search-data" name="q" placeholder="Search here..." required value="<?= htmlspecialchars($searchTerm) ?>"> <!-- Display the search term in the search bar -->
    <button class="search-button" type="submit" id="search-button" name="submit">Go</button>
  </form>
</div>


  <div class="nav-items">
    <!-- Navigation menu links -->
    <li><a href="../php/marketplace.php">Marketplace</a></li>
    <li><a href="../php/upload.php">Upload</a></li>
    
    <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) : ?> <!-- if user is not logged in -->
      <li><a href="../php/login.php">Login</a></li>
    <?php else : ?> <!-- if user is logged in -->
      <li><a href="../php/account.php">Profile</a></li>
      <li><a href="../php/logout.php">Logout</a></li>

      <?php if ($row["admin"] == 1) : ?> <!-- if user is an admin -->
        <li><a href="../php/approvalList.php">Approvals</a></li>
        <li><a href="../php/report.php">Reports</a></li>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</nav>
