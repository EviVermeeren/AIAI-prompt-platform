<?php
include_once("../inc/bootstrap.php");
include_once("../classes/Db.php");
include_once("../classes/Comment.php");

class Comment
{

    private $text;
    private $userId;
    private $postId;


    /**
     * Get the value of text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the value of text
     *
     * @return  self
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

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

    public function save()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO comments (text, userId, postId) VALUES (:text, :userId, :postId)");

        $text = $this->getText();
        $userId = $this->getUserId();
        $postId = $this->getPostId();

        $statement->bindValue(":text", $text);
        $statement->bindValue(":userId", $userId);
        $statement->bindValue(":postId", $postId);

        $result = $statement->execute();
        return $result;
    }

    // code die werkt
    // public static function getAllComments($postId){
    //     $conn = Db::getInstance();
    //     $statement = $conn->prepare("SELECT * FROM comments WHERE postId = :postId");
    //     $statement->bindValue(":postId", $postId);
    //     $statement->execute();
    //     $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    //     return $result;
    // }

    public static function getAll($postId)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM comments WHERE postid = :postId");
        $statement->bindValue(":postId", $postId);
        $result = $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
