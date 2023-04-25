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
        $sql = "SELECT prompts.* FROM prompts INNER JOIN favorites ON prompts.id = favorites.prompt_id WHERE favorites.user_id = $user_id ORDER BY date DESC";
        $result = $this->conn->query($sql); // Execute the query
        return $result;
    }
}
