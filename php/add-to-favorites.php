<?php
include_once("../inc/bootstrap.php");

if (!isset($_POST['id'])) {
    echo "Error: ID parameter not set";
    exit;
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo "Error: no user id found";
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$user_id = $_SESSION['user_id'];

$conn = Db::getInstance();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
