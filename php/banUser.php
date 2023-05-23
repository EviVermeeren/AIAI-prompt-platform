<?php
require_once "../inc/bootstrap.php";

// Get database connection instance
$conn = Db::getInstance();

// Check if connection was successful
if (!$conn) {
    echo "Error: Failed to connect to database";
    exit;
}
// Get the user ID from the GET request
$userId = $_GET['id'];

// Prepare the SQL statement to update the reported and blocked columns in the users table
$stmt = $conn->prepare("UPDATE users SET reported = 0, blocked = 1 WHERE id = :userId");
$stmt->bindParam(":userId", $userId);

// Execute the SQL statement
if ($stmt->execute()) {
  // Return a success message
  echo "success";
} else {
  // Return an error message
  echo "error";
}