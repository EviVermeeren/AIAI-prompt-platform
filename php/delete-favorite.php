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

// Create a new instance of the Prompt class
$favoritesManager = new Prompt();
$favoritesManager->setConnection($conn); // Set the connection property

// Set the required properties using the setter methods
$favoritesManager->setId($id);
$favoritesManager->setName($name);
$favoritesManager->setUser($user);
$favoritesManager->setRating($rating);
$favoritesManager->setDescription($description);
$favoritesManager->setPrice($price);
$favoritesManager->setCharacteristics($characteristics);
$favoritesManager->setModel($model);
$favoritesManager->setPrompt($prompt);
$favoritesManager->setPictures($pictures);
$favoritesManager->setDate($date);
$favoritesManager->setTags($tags);

// Call the removeFavorite method
$favoritesManager->removeFavorite($id, $user_id);
