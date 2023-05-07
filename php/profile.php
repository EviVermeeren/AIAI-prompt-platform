<?php
include_once("../inc/bootstrap.php");
include_once("../inc/functions.inc.php");

$id = $_GET['id'] ?? '';

$users = new User();
$user = $users->getUserDataByID($id);

$email = $user['email'];

if ($user) {
    $username = $user['username'];
    $profile_banner = $user['profile_banner'];
    $profile_picture = $user['profile_picture'];
    $bio = $user['bio'];
    $new = new Prompt();
    $prompts = $new->getPromptsByUser($email); // Retrieve prompts associated with the user
}

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
                <a class="btnfollow" id="share-btn" href="javascript:void(0)" onclick="copyToClipboard('<?php echo $share_url ?>')">Share</a> <!-- This button will be used to share the account -->
            </div>
        </div>

        <div class="profilebio">
            <p class="biotext"><?php echo $bio ?></p> <!-- Here we display the bio of the user -->
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
        <?php include_once("../inc/foot.inc.php"); ?> <!-- Include footer -->
        <script src="../css/script.js"></script> <!-- Include script -->
</body>

</html>