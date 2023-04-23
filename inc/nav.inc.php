<?php

$conn = Db::getInstance(); // connect to the database
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
}
// Query to check if the user is an admin
$sql = "SELECT admin FROM users WHERE id = :user_id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
  // User exists in the database
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<nav>
  <div class="logo">
    <a href="../php/marketplace.php">PromptSwap</a>
  </div>

  <div>
    <form action="#">
      <input type="search" class="search-data" placeholder="Search here..." required />
      <button type="submit" class="fas fas-search">→</button>
    </form>
  </div>

  <div class="nav-items">
    <li><a href="../php/marketplace.php">Marketplace</a></li>
    <li><a href="../php/upload.php">Upload</a></li>
    <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) : ?> <!-- if user is not logged in -->
      <li><a href="../php/login.php">Login</a></li>
    <?php else : ?> <!-- if user is logged in -->
      <li><a href="../php/account.php">Profile</a></li>
      <li><a href="../php/logout.php">Logout</a></li>
    <?php endif; ?>

    <?php if ($row["admin"] == 1) : ?> <!-- if user is an admin -->
      <li><a href="../php/approvalList.php">Approvals</a></li>
    <?php endif; ?>
  </div>
</nav>