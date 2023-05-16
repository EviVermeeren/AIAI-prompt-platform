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

$favoritesManager->removeFavorite($id, $user_id);

$response = array(
    "success" => true
);
header('Content-Type: application/json');
echo json_encode($response);
?>
```