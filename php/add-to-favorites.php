<?php
include_once("../inc/bootstrap.php");

$user = new User();
if (!$user->isAuthenticated()) {
    $user->redirectToLogin();
}

$id = $_POST['id'];
$user_id = $_SESSION['user_id'];

$conn = Db::getInstance();

$favoritesManager = new Prompt();
$favoritesManager->setConnection($conn);

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

$favoritesManager->addFavorite($id, $user_id);

$response = array(
    "success" => true
);

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
