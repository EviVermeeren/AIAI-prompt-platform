<?php
include_once("../inc/bootstrap.php");

$message = "";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../php/login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>upload your prompt</title>
    <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" />
</head>

<body>
    <?php include_once("../inc/nav.inc.php"); ?> <!-- This is the nav bar -->


    <h1 class="messagefail">Your prompt has been uploaded succesfully, and will now be reviewed by one of our moderators. Thank you!</h1>

    <?php include_once("../inc/foot.inc.php"); ?> <!-- This is the footer -->
</body>

</html>