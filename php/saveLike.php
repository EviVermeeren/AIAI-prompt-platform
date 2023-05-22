<?php
    require_once("../bootstrap.php");
    
    if( !empty($_POST) ) {
        $postId = $_POST['id'];
        $userId = 1;

        $l = new Like();
        $l->setPostId($postId);
        $l->setUserId($userId);
        $l->save();

        $p = new Prompt(); // hier ga je de likes van de post ophalen de betere manier is om te werken met getters en setters
        $p->$id = $postId;
        $likes = $p->getLikes();
        //var_dump($likes); // je moet naar een response gaan kijken en dan krijg je deze vardump te zien
        


        $result = [
            "status" => "success",
            "message" => "Like was saved",
            "likes" => $likes
        ]; 

        echo json_encode($result);

    }