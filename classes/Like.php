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

    // public function getAllPrompts(){
    //     $conn = Db::getInstance();
    //     $statement = $conn->prepare("select * from prompts");
    //     $statement->execute();
    //     $result = $statement->fetchAll();
    //     return $result;
    // }

}

?>