<?php
include_once("../inc/bootstrap.php"); // include the bootstrap file

if (!isset($_POST['id'])) { // check if the id parameter is set
    echo "Error: ID parameter not set"; // if not, display an error
    exit; // exit the script
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) { // check if the user is logged in
    echo "Error: no user id found"; // if not, display an error
    exit; // exit the script
}

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT); // get the id from the post request and sanitize it to prevent SQL injection
$user_id = $_SESSION['user_id']; // get the user id from the session

$conn = Db::getInstance(); // connect to the database
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception


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
$favoritesManager->addFavorite($id, $user_id);
