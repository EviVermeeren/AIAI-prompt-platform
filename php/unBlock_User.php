<?php
require_once "../inc/bootstrap.php";

// Get database connection instance
$conn = Db::getInstance()->getConnection();

// Check if connection was successful
if (!$conn) {
    echo "Error: Failed to connect to the database";
    exit;
}

// Get the user ID from the GET request
$userId = $_GET['id'];

// Prepare the SQL statement to update the blocked column in the users table
$stmt = $conn->prepare("UPDATE users SET blocked = 0 WHERE id = ?");
$stmt->bind_param("i", $userId);

// Execute the SQL statement
if ($stmt->execute()) {
    // Return a success message
    echo "success";
} else {
    // Return an error message
    echo "error";
}

$stmt->close();
