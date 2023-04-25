<?php
include_once("../inc/bootstrap.php");

class FavoritesManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function addFavorite($prompt_id, $user_id)
    {
        $stmt = $this->conn->prepare("INSERT INTO favorites (prompt_id, user_id) VALUES (:prompt_id, :user_id)");
        $stmt->bindParam(":prompt_id", $prompt_id);
        $stmt->bindParam(":user_id", $user_id);

        if ($stmt->execute()) {
            echo "Prompt added to favorites!";
        } else {
            echo "Error: Failed to add prompt to favorites";
        }
    }

    public function removeFavorite($prompt_id, $user_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM favorites WHERE prompt_id = :prompt_id AND user_id = :user_id");
        $stmt->bindParam(":prompt_id", $prompt_id);
        $stmt->bindParam(":user_id", $user_id);

        if ($stmt->execute()) {
            echo "Prompt removed from favorites!";
        } else {
            echo "Error: Failed to remove prompt from favorites";
        }
    }
}
