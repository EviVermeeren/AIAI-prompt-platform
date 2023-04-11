<?php
include_once("../inc/bootstrap.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../php/login.php');
    exit;
}

// Connect to database
$conn = Db::getInstance();


// Query the database to get the prompts
$sql = "SELECT name, model, price FROM prompts ORDER BY date DESC";
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
    <link rel="stylesheet" href="../css/style.css" />
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

        <div class="allprompts">
            <div>
                <h1>All prompts</h1>
            </div>

            <div class="promptflex"> <!-- Hier ga je éénmaal een prompt nemen in html en daarover lussen met een foreach in php vanuit uw database, dus niet de html aanpassen-->
                
            <?php
                // assuming $data is the array of data from your database
                foreach ($result as $row) {
                    // extract the relevant data from the row
                    $name = $row['name'];
                    $model = $row['model'];
                    $price = $row['price'];
                ?>
                    <a href="../php/detail.php">
                        <div class="prompt">
                            <p class="modelboxtitle"><?php echo $model ?></p>
                            <p class="promptboxtitle"><?php echo $name ?> <span class="span"><?php echo $price ?></span></p>
                        </div>
                    </a>
                <?php
                }
                ?>
            </div>

        </div>  

    </div>


    <?php include_once("../inc/foot.inc.php"); ?>
  </body>
</html>
