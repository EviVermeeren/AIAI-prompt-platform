<?php
    include_once("../classes/Db.php");
    include_once("../classes/Comment.php");
    include_once("../classes/User.php");

class Like{

    private $userId;
    private $postId;

    /**
     * Get the value of userId
     */ 
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     *
     * @return  self
     */ 
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of postId
     */ 
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * Set the value of postId
     *
     * @return  self
     */ 
    public function setPostId($postId)
    {
        $this->postId = $postId;

        return $this;
    }

    public function save(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("insert into likes (user_id, post_id, data_created) values (:user_id, :post_id, NOW())");
        $statement->bindValue(":userId", $this->getUserId());
        $statement->bindValue(":postId", $this->getPostId());
        $result = $statement->execute();
        return $result;
    }

    public static function updateLikes($promptId, $likes) {
        try {
            $conn = Db::getInstance();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $query = $conn->prepare("UPDATE prompts SET likes = :likes WHERE id = :promptId");
            $query->bindValue(":likes", $likes, PDO::PARAM_INT);
            $query->bindValue(":promptId", $promptId, PDO::PARAM_INT);
            $query->execute();
    
            return true;
        } catch (PDOException $e) {
            $message = "Try again later: " . $e->getMessage();
            exit;
        }
    }

}

?>