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

    <li><a href="../php/approvalList.php">Approvals</a></li> <!-- Lijntje code Brend -->
  </div>
</nav>