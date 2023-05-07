<?php
include_once("../inc/bootstrap.php"); // include the bootstrap file

if (!isset($_POST['id'])) { // check if the id parameter is set
    echo "Error: ID parameter not set"; // if not, display an error message
    exit; // and exit the script
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo "Error: no user id found";
    exit;
}

$id = $_POST['id']; // if the id parameter is set, store it in a variable
$user_id = $_SESSION['user_id']; // get the ID of the currently logged-in user
var_dump($user_id);

$conn = Db::getInstance(); // connect to the database
if (!$conn) { // check if the connection was successful
    echo "Error: Failed to connect to database"; // if not, display an error message
    exit; // and exit the script
}

$favoritesManager = new Prompt($id, $name, $user, $rating, $description, $price, $characteristics, $model, $prompt, $pictures, $date, $tags, $conn); // create a new instance of the FavoritesManager class
$favoritesManager->removeFavorite($id, $user_id);
