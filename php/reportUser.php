<?php
require_once "../inc/bootstrap.php";

// Get database connection instance
$conn = Db::getInstance();

// Check if connection was successful
if (!$conn) {
    echo "Error: Failed to connect to database";
    exit;
}

// Get the ID of the user to flag from the query string
$id = $_GET['id'];

// Update the reported column of the user with the given ID
$stmt = $conn->prepare("UPDATE users SET reported = 1 WHERE id = :userId");
$stmt->bindParam(":userId", $id);

// Execute the SQL statement
if ($stmt->execute()) {
    // Return a success message
    echo "success";
} else {
    // Return an error message
    echo "error";
}
