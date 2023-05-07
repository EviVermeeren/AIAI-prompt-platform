<?php

include_once("../inc/bootstrap.php");
include_once("../inc/functions.inc.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: ../php/login.php');
  exit;
}

$conn = "";
$verification_code = "";
$password = "";

$email = $_SESSION["email"];
$user = new User($email, $conn, $verification_code, $password, $plainTextPassword);
$profile_picture = $user->getProfilePicture();
$profile_banner = $user->getProfileBanner();
$bio = $user->getBio();
$username = $user->getUsername();

$user_id = $user->getId();
$share_url = "http://localhost/AIAI-prompt-platform-main/php/account.php?id=$user_id";

// get all prompts uploaded by the user
$conn = Db::getInstance();
$stmt = $conn->prepare("SELECT * FROM prompts WHERE user=:user");
$stmt->bindParam(":user", $email);
$stmt->execute();
$prompts = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Account</title>
  <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
  <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" />
</head>

<body>

  <?php include_once("../inc/nav.inc.php"); ?> <!-- Include navigation -->

  <div class="profile">

    <div class="profileimg">
      <img class="banner" src="../media/<?php echo $profile_banner ?>" alt="">
      <img class="pfp" src="../media/<?php echo $profile_picture ?>" alt="">
    </div>

    <div class="profilename">
      <h2 class="nameuser"><?php echo $username ?></h2> <!-- Here we display the username of the user -->
      <div class="likeandfollow">
        <a class="btnfollow" href="#">Follow</a> <!-- This button will be used to follow the user -->

        <a class="btnfollow" href="#">Flag 🚩</a> <!-- This button will be used to flag the user -->
        <a class="btnfollow" href="../php/editAccount.php">Edit Account</a> <!-- This button will be used to edit the account -->
        <a class="btnfollow" id="share-btn" href="javascript:void(0)" onclick="copyToClipboard('<?php echo $share_url ?>')">Share</a> <!-- This button will be used to share the account -->

        <?php if (isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) : ?> <!-- if user is  logged in -->

          <a class="btnfollow" href="../php/favorites.php">Favorites ⭐</a>
        <?php endif; ?>

      </div>
    </div>

    <div class="profilebio">
      <p class="biotext"><?php echo $bio ?></p> <!-- Here we display the bio of the user -->
    </div>
  </div>

  <div class="allprompts">
    <div>
      <h1>All prompts by <span><?php echo $username ?></span></h1> <!-- Here we display the username of the user -->
    </div>

    <div class="promptflex">
      <?php if (count($prompts) == 0) : ?> <!-- If there are no prompts, display the message -->
        <h3 style="margin-top: 50px">You don't have any prompts yet!</h3>
      <?php else : ?> <!-- Otherwise, display the prompts -->
        <?php foreach ($prompts as $prompt) { // Loop through the result and display the prompts

          $name = $prompt['name']; // Get the name of the prompt
          $model = $prompt['model']; // Get the model of the prompt
          $price = $prompt['price']; // Get the price of the prompt
          $pictures = $prompt['pictures']; // Get the pictures of the prompt
        ?>
          <a href="../php/detail.php?id=<?php echo $prompt['id']; ?>"> <!-- Link to detailpage -->
            <div class="prompt" style="background-image: url('../media/<?php echo $pictures; ?>')"> <!-- Display the prompt -->
              <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) : ?> <!-- If the user is logged in, display the price -->
                <p class="modelboxtitle"><?php echo $model ?></p> <!-- Display the model -->
                <p class="promptboxtitle"><?php echo $name ?> <span class="span">💶<?php echo $price ?></span></p> <!-- Display the name and price -->
              <?php elseif (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) : ?> <!-- If the user is not logged in, don't display the price -->
                <p class="promptboxtitle"><?php echo $name ?></p> <!-- Display the name -->
              <?php endif; ?>
            </div>
          </a>
        <?php } ?>
      <?php endif; ?>
    </div>

    <?php include_once("../inc/foot.inc.php"); ?> <!-- Include footer -->
    <script src="../css/script.js"></script> <!-- Include script -->
</body>

</html>