<?php
require_once "../inc/bootstrap.php";

// Get database connection instance
$conn = Db::getInstance();

// Check if connection was successful
if (!$conn) {
    echo "Error: Failed to connect to database";
    exit;
}

// Get the ID of the prompt to be deleted from the GET request
$id = $_GET["id"];

// Prepare the SQL statement to update the prompt with the given ID
$stmt = $conn->prepare("UPDATE prompts SET approved = 1 WHERE id = :id");

// Bind the ID parameter to the prepared statement
$stmt->bindParam(':id', $id);

// Execute the prepared statement to delete the prompt from the database
if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
