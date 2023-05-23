<?php
include_once("../inc/bootstrap.php");

$conn = Db::getInstance();
$user_id = $_SESSION['user_id'];

$favorite_prompts = new Prompt();
$favorite_prompts->setConnection($conn);
$favorites = $favorite_prompts->getFavorites($user_id);

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

    <h1>Your favorite prompts</h1>
    <div class="promptflex">
        <?php
        foreach ($favorites as $row) { // Loop through the result and display the prompts

            $name = $row['name']; // Get the name of the prompt
            $model = $row['model']; // Get the model of the prompt
            $price = $row['price']; // Get the price of the prompt
            $pictures = $row['pictures']; // Get the pictures of the prompt
        ?>
            <a href="../php/detail.php?id=<?php echo $row['id']; ?>"> <!-- Link to detailpage -->
                <div class="prompt" style="background-image: url('../media/<?php echo $pictures; ?>')"> <!-- Display the prompt -->
                    <p class="modelboxtitle"><?php echo $model ?></p> <!-- Display the model -->
                    <p class="promptboxtitle"><?php echo $name ?> <span class="span"><?php echo $price ?></span></p> <!-- Display the name and price -->

                </div>
            </a>
        <?php
        }
        ?>
    </div>

    <?php include_once("../inc/foot.inc.php"); ?> <!-- Include footer -->

</body>

</html>