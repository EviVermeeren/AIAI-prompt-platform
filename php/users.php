<?php
include_once("../inc/bootstrap.php"); // include bootstrap file

$conn = Db::getInstance(); // Connect to database

// Get all users from the database
$query = $conn->query("SELECT * FROM users");
$users = $query->fetchAll();

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $profile_url = "../php/profile.php?user_id=" . $user_id;
} else {
    $profile_url = "../php/profile.php?user_id=";
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
</head>

<body>

    <?php include_once("../inc/nav.inc.php"); ?> <!-- Include navigation -->

    <h1 class="others">Other users on PromptSwap</h1>
    <div class="promptflex">
        <?php
        foreach ($users as $user) { // Loop through the result and display the user

            $name = $user['username']; // Get the name of the user
            $picture = $user['profile_picture']; // Get the picture of the user
        ?>

            <div class="userdiv" style="background-image: url('../media/<?php echo $picture; ?>')">
                <a class="userbox" href="<?php echo $profile_url . $user['id']; ?>"><?php echo $user['username']; ?></a>
            </div>
            </a>
        <?php
        }
        ?>
    </div>

    <?php include_once("../inc/foot.inc.php"); ?> <!-- Include footer -->

</body>

</html>