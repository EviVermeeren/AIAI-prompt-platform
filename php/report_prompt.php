<?php
require_once "../inc/bootstrap.php";

// Get database connection instance
$conn = Db::getInstance();

// Check if connection was successful
if (!$conn) {
    echo "Error: Failed to connect to database";
    exit;
}

$id = $_GET['id'];

// update the reported column
$sql = "UPDATE prompts SET reported=1 WHERE id=$id";
$result = mysqli_query($conn, $sql);

if ($result) {
  // success, return HTTP 200 OK status
  http_response_code(200);
} else {
  // error, return HTTP 500 Internal Server Error status
  http_response_code(500);
}
/// Deze code kan misschien weg