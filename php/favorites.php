<?php
include_once("../inc/bootstrap.php"); // include bootstrap file

$conn = Db::getInstance(); // Connect to database

// Get the ID of the logged-in user
$user_id = $_SESSION['user_id'];

// Query the database to get the prompts in the user's favorites
$sql = "SELECT prompts.* FROM prompts INNER JOIN favorites ON prompts.id = favorites.prompt_id WHERE favorites.user_id = $user_id ORDER BY date DESC";
$result = $conn->query($sql); // Execute the query

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>

    <?php include_once("../inc/nav.inc.php"); ?> <!-- Include navigation -->

    <h1>Your favorite prompts</h1>
    <div class="promptflex">
        <?php
        foreach ($result as $row) { // Loop through the result and display the prompts

            $name = $row['name']; // Get the name of the prompt
            $model = $row['model']; // Get the model of the prompt
            $price = $row['price']; // Get the price of the prompt
            $pictures = $row['pictures']; // Get the pictures of the prompt
        ?>
            <a href="../php/detail.php?id=<?php echo $row['id']; ?>"> <!-- Link to detailpage -->
                <div class="prompt" style="background-image: url('<?php echo $pictures; ?>')"> <!-- Display the prompt -->
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