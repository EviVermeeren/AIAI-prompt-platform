<?php
include_once("../inc/bootstrap.php");
$user_id = $_SESSION['user_id'];

$user = new User();
$user->setDbConnection();
$users = $user->getUsers();

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $profile_url = $user->getProfileUrl($user_id);
} else {
    $profile_url = $user->getProfileUrl("");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" />
    <link rel="icon" type="image/x-icon" href="../media/favicon.ico">
</head>

<body>

    <?php include_once("../inc/nav.inc.php"); ?> <!-- Include navigation -->

    <h1 class="others">Other users on PromptSwap</h1>
    <div class="promptflex">
        <?php
        foreach ($users as $user) { // Loop through the result and display the user

            $name = $user['username'] ?? ''; // Get the name of the user
            $picture = $user['profile_picture'] ?? ''; // Get the picture of the user
            $userId = $user['id'] ?? ''; // Get the user ID

        ?>

            <div class="userdiv" style="background-image: url('../media/<?php echo $picture; ?>')">
                <a class="userbox" href="../php/profile.php?id=<?php echo $userId; ?>"><?php echo $name; ?></a>
            </div>

        <?php
        }
        ?>
    </div>

    <?php include_once("../inc/foot.inc.php"); ?> <!-- Include footer -->

</body>

</html>