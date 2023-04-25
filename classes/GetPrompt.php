<?php
include_once("../inc/bootstrap.php"); // include bootstrap file

class GetPrompt
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getPrompts($promptsPerPage, $page)
    {
        $offset = ($page - 1) * $promptsPerPage; // Calculate the offset for the current page

        // Query the database to get the total number of prompts
        $sql = "SELECT COUNT(*) AS count FROM prompts"; // Get the total number of prompts
        $result = $this->conn->query($sql); // Execute the query
        $row = $result->fetch(); // Fetch the result
        $totalPrompts = $row['count']; // Get the total number of prompts

        $totalPages = ceil($totalPrompts / $promptsPerPage); // Calculate the total number of pages

        // Query the database to get the prompts for the current page
        $sql = "SELECT * FROM prompts WHERE approved=1 ORDER BY date DESC LIMIT $promptsPerPage OFFSET $offset";
        $result = $this->conn->query($sql);

        return array('prompts' => $result, 'totalPages' => $totalPages);
    }
}
