<?php

include_once("../inc/bootstrap.php");
include_once("../inc/functions.inc.php");

$user = new User();
if (!$user->isAuthenticated()) {
  $user->redirectToLogin();
}

$email = $_SESSION["email"];
$user->setEmail($email);

$conn = Db::getInstance();
$prompt = new Prompt();
$prompt->setConnection($conn);
$prompts = $prompt->getPromptsByUser($email);

$profile_picture = $user->getProfilePicture();
$profile_banner = $user->getProfileBanner();
$bio = $user->getBio();
$username = $user->getUsername();

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

  <?php include_once("../inc/nav.inc.php"); ?>

  <div class="profile">

    <div class="profileimg">
      <img class="banner" src="../media/<?php echo $profile_banner ?>" alt="">
      <img class="pfp" src="../media/<?php echo $profile_picture ?>" alt="">
    </div>

    <div class="profilename">
      <h2 class="nameuser"><?php echo $username ?></h2>
      <div class="likeandfollow">
        <a class="btnfollow" href="#">Follow</a>

        <a class="btnfollow" href="#">Flag 🚩</a>
        <a class="btnfollow" href="../php/editAccount.php">Edit Account</a>
        <a class="btnfollow" id="share-btn" href="javascript:void(0)" onclick="copyToClipboard('<?php echo $share_url ?>')">Share</a>

        <?php if (isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) : ?>

          <a class="btnfollow" href="../php/favorites.php">Favorites ⭐</a>
        <?php endif; ?>

      </div>
    </div>

    <div class="profilebio">
      <p class="biotext"><?php echo $bio ?></p>
    </div>
  </div>

  <div class="allprompts">
    <div>
      <h1>All prompts by <span><?php echo $username ?></span></h1>
    </div>

    <div class="promptflex">
      <?php if (count($prompts) == 0) : ?>
        <h3 style="margin-top: 50px">You don't have any prompts yet!</h3>
      <?php else : ?>
        <?php foreach ($prompts as $prompt) {

          $name = $prompt['name'];
          $model = $prompt['model'];
          $price = $prompt['price'];
          $pictures = $prompt['pictures'];
        ?>
          <a href="../php/detail.php?id=<?php echo $prompt['id']; ?>">
            <div class="prompt" style="background-image: url('../media/<?php echo $pictures; ?>')">
              <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) : ?>
                <p class="modelboxtitle"><?php echo $model ?></p>
                <p class="promptboxtitle"><?php echo $name ?> <span class="span">💶<?php echo $price ?></span></p>
              <?php elseif (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) : ?>
                <p class="promptboxtitle"><?php echo $name ?></p>
              <?php endif; ?>
            </div>
          </a>
        <?php } ?>
      <?php endif; ?>
    </div>

    <?php include_once("../inc/foot.inc.php"); ?>
    <script src="../css/script.js"></script>
</body>

</html>