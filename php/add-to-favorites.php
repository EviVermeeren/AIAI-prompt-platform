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

$sql = "INSERT INTO favorites (prompt_id, user_id) VALUES (:prompt_id, :user_id)"; // insert the prompt into the favorites table
$stmt = $conn->prepare($sql);
$stmt->bindParam(":prompt_id", $id);
$stmt->bindParam(":user_id", $user_id);
if ($stmt->execute()) { // check if the query was successful
    echo "Prompt added to favorites!"; // if so, display a success message
} else {
    echo "Error: Failed to add prompt to favorites"; // if not, display an error message
}
