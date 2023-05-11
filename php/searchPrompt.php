<?php
// Include necessary files
require_once "../inc/bootstrap.php";
require_once "../inc/functions.inc.php";

// Establish a database connection
try {
    $conn = Db::getInstance(); // Establish a database connection using Db::getInstance()
} catch (PDOException $e) {
    // If there's an error connecting to the database, log the error and return a 500 status code
    http_response_code(500); // Set the HTTP response code to 500
    error_log("Failed to connect to database: " . $e->getMessage()); // Log the error message to the error log
    echo "Error: Failed to connect to database"; // Display an error message to the user
    exit; // Stop executing the script
}

// Retrieve the search term from the query string
$searchTerm = isset($_GET["q"]) ? trim($_GET["q"]) : "";

// Build the SQL query to retrieve relevant prompts
$sql = "SELECT name FROM prompts WHERE name LIKE :searchTerm LIMIT 10"; // Build the SQL query to search the "prompts" table for names that match the search term

// Prepare the statement and bind the parameter
$stmt = $conn->prepare($sql); // Prepare the SQL statement
$stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR); // Bind the search term to the SQL statement

// Execute the query and fetch the results as an associative array
$stmt->execute(); // Execute the SQL statement
$searchResults = $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch the results of the SQL statement as an associative array

// Retrieve the product details based on the search result
$productStmt = $conn->prepare("SELECT * FROM prompts WHERE name = :name"); // Build the SQL query to retrieve the details of the product with the specified name

if (isset($_GET['submit']) && isset($_GET['q'])) {
    // process search results
} else {
    // show search form
}

$searchTerm = isset($_GET["q"]) ? trim($_GET["q"]) : ""; // Retrieve the search term from the query string again and assign it to the $searchTerm variable

if (isset($_GET['q'])) {
    // process search results
} else {
    // show search form
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css">
    <link rel="stylesheet" href="../css/style.css?v=<?= time(); ?>">
    <script defer src="../js/script.js"></script>
</head>

<body>
    <?php include_once("../inc/nav.inc.php"); ?>

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



    <div>
        <!-- Display search results -->
        <h2>Search Results for '<?= htmlspecialchars($searchTerm); ?>':</h2>
        <?php if (empty($searchResults)): ?>
            <p>No results found</p>
        <?php else: ?>
            <ul id="search-results">
                <?php foreach ($searchResults as $result): ?>
                    <?php 
                        // Get the details for each product based on the search result
                        $productStmt->execute(['name' => $result]);
                        $product = $productStmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <li>
                        <!-- Create a link to the detail page for each product -->
                        <a href="../php/detail.php?id=<?= $product['id'] ?>">
                            <!-- Display product information in a box with an image -->
                            <div class="prompt" style="background-image: url('../media/<?= htmlspecialchars($product['pictures']) ?>')">
                                <p class="modelboxtitle"><?= htmlspecialchars($product['model']) ?></p>
                                <p class="promptboxtitle"><?= htmlspecialchars($product['name']) ?> <span class="span"><?= htmlspecialchars($product['price']) ?></span></p>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <?php include_once("../inc/foot.inc.php"); ?>
</body>
</html>
