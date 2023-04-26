<?php
include_once("../inc/bootstrap.php"); // include bootstrap file

class FavoritePrompts
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getFavorites($user_id)
    {
        // Query the database to get the prompts in the user's favorites
        $sql = "SELECT prompts.* FROM prompts INNER JOIN favorites ON prompts.id = favorites.prompt_id WHERE favorites.user_id = :user_id ORDER BY date DESC";
        $stmt = $this->conn->prepare($sql); // Prepare the statement
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); // Bind the parameter
        $stmt->execute(); // Execute the query
        $result = $stmt->fetchAll(); // Get the result set
        return $result;
    }
}
