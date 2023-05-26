<?php
    require_once("../bootstrap.php");
    require_once("../classes/Like.php");
    
    session_start();

    // Check if the user is authenticated
    if (!isset($_SESSION['userId'])) {
    $response = [
        'status' => 'error',
        'message' => 'User not authenticated.'
    ];
    echo json_encode($response);
    exit;
    }

    // Assuming you have the necessary database connection and setup
    $postId = $_POST['postId'];
    $userId = $_SESSION['userId'];

    // Save the like in the database
    // Your code to insert the like into the database

    if ($result) {
    // Like successfully saved
    $response = [
        'status' => 'success',
        'message' => 'Post liked!'
    ];
    } else {
    // Error occurred while saving the like
    $response = [
        'status' => 'error',
        'message' => 'Error liking the post.'
    ];
    }

    echo json_encode($response);
?>

   
