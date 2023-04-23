<?php
include_once("../inc/bootstrap.php"); // include bootstrap file

$promptsPerPage = 15; // Set the number of prompts to display per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Get the current page number from the query string, or default to 1
$offset = ($page - 1) * $promptsPerPage; // Calculate the offset for the current page

$conn = Db::getInstance(); // Connect to database

// Query the database to get the total number of prompts
$sql = "SELECT COUNT(*) AS count FROM prompts"; // Get the total number of prompts
$result = $conn->query($sql); // Execute the query
$row = $result->fetch(); // Fetch the result
$totalPrompts = $row['count']; // Get the total number of prompts

$totalPages = ceil($totalPrompts / $promptsPerPage); // Calculate the total number of pages

// Query the database to get the prompts for the current page
$sql = "SELECT * FROM prompts WHERE approved=1 ORDER BY date DESC LIMIT $promptsPerPage OFFSET $offset";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Marketplace</title>
    <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" />
</head>

<body>
    <?php include_once("../inc/nav.inc.php"); ?> <!-- This is the nav bar -->

    <div class="marketplacefilter">

        <div class="filterlist"> <!-- Ik denk dat we hier ipv alle categoriën apart te benoemen, kunnen werken met een lijstje in php die zichzelf loopt over de categoriën en modellen uit de database -->
            <a class="clearfilter" href="#">Clear filters</a>
            <p class="filter">Sort by</p>
            <form action="/action_page.php">
                <input type="checkbox" class="popularity" name="popularity" value="popularity"> <!-- in database staan parameters zoals populariteit, model, categorie..., hierop filteren. -->
                <label for="popularity"> Popularity </label><br>
                <input type="checkbox" id="date" name="date" value="date">
                <label for="date"> Date</label><br>
                <input type="checkbox" id="name" name="name" value="name">
                <label for="name"> Name A-Z</label><br><br>
            </form>

            <p class="filter">Model</p>
            <form action="/action_page.php">
                <input type="checkbox" id="all" name="all" value="all">
                <label for="all"> All </label><br>
                <input type="checkbox" id="dalle" name="dalle" value="dalle">
                <label for="dalle"> DALL-E</label><br>
                <input type="checkbox" id="midjourney" name="midjourney" value="midjourney">
                <label for="midjourney"> Midjourney</label><br>
                <input type="checkbox" id="stablediffusion" name="stablediffusion" value="stablediffusion">
                <label for="stablediffusion"> Stable Diffusion</label><br>
                <input type="checkbox" id="lexica" name="lexica" value="lexica">
                <label for="lexica"> Lexica</label><br>
            </form>

            <p class="filter">Category</p>
            <form action="/action_page.php">
                <input type="checkbox" id="all" name="all" value="all">
                <label for="all"> All </label><br>
                <input type="checkbox" id="animals" name="animals" value="animals">
                <label for="animals"> Animals</label><br>
                <input type="checkbox" id="3D" name="3D" value="3D">
                <label for="3D"> 3D</label><br>
                <input type="checkbox" id="space" name="space" value="space">
                <label for="space"> Space</label><br>
                <input type="checkbox" id="game" name="game" value="game">
                <label for="game"> Game</label><br>
                <input type="checkbox" id="car" name="car" value="car">
                <label for="car"> Car</label><br>
                <input type="checkbox" id="nature" name="nature" value="nature">
                <label for="nature"> Nature</label><br>
                <input type="checkbox" id="portrait" name="portrait" value="portrait">
                <label for="portrait"> Portrait</label><br>
                <input type="checkbox" id="anime" name="anime" value="anime">
                <label for="anime"> Anime</label><br>
                <input type="checkbox" id="interior" name="interior" value="interior">
                <label for="interior"> Interior</label><br>
                <input type="checkbox" id="realistic" name="realistic" value="realistic">
                <label for="realistic"> Realistic</label><br>
                <input type="checkbox" id="geek" name="geek" value="geek">
                <label for="geek"> Geek</label><br>
                <input type="checkbox" id="building" name="building" value="building">
                <label for="building"> Building</label><br>
            </form>
        </div>

        <div class="allprompts">
            <div>
                <h1>All prompts</h1>
            </div>

            <div class="promptflex">
                <?php
                foreach ($result as $row) { // Loop through the result and display the prompts

                    $name = $row['name']; // Get the name of the prompt
                    $model = $row['model']; // Get the model of the prompt
                    $price = $row['price']; // Get the price of the prompt
                    $pictures = $row['pictures']; // Get the pictures of the prompt
                ?>
                    <a href="../php/detail.php?id=<?php echo $row['id']; ?>"> <!-- Link to detailpage -->
                        <div class="prompt" style="background-image: url('../media/<?php echo $pictures; ?>')"> <!-- Display the prompt -->
                            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) : ?> <!-- If the user is logged in, display the price -->
                                <p class="modelboxtitle"><?php echo $model ?></p> <!-- Display the model -->
                                <p class="promptboxtitle"><?php echo $name ?> <span class="span">💶<?php echo $price ?></span></p> <!-- Display the name and price -->
                            <?php elseif (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) : ?> <!-- If the user is not logged in, don't display the price -->
                                <p class="promptboxtitle"><?php echo $name ?></p> <!-- Display the name -->
                            <?php endif; ?>
                        </div>
                    </a>
                <?php
                }
                ?>
            </div>

            <!-- Add pagination links -->
            <div class="pagination"> <!-- Pagination -->
                <?php if ($page > 1) : ?> <!-- If the page is higher than 1, display the previous button -->
                    <a href="?page=<?php echo ($page - 1); ?>">Previous</a> <!-- Link to the previous page -->
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?> <!-- Loop through the total amount of pages -->
                    <?php if ($i == $page) : ?> <!-- If the current page is the same as the page number, display the page number -->
                        <span class="current-page"><?php echo $i; ?></span>
                    <?php else : ?> <!-- If the current page is not the same as the page number, display the page number as a link -->
                        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                <?php if ($page < $totalPages) : ?> <!-- If the page is lower than the total amount of pages, display the next button -->
                    <a href="?page=<?php echo ($page + 1); ?>">Next</a> <!-- Link to the next page -->
                <?php endif; ?>
            </div>

        </div>

    </div>


    <?php include_once("../inc/foot.inc.php"); ?> <!-- Include the footer -->
</body>

</html>