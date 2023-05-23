<?php
include_once("../classes/Db.php");
include_once("../classes/Comment.php");



if (!empty($_POST)) {
    //new comment
    $c = new Comment();
    $c->setText($_POST["text"]);
    $c->setPostId($_POST["postId"]);
    $c->setUserId($_SESSION["userId"]);


    // save()
    $c->save();


    // success
    $response = [ // je schrijft een array met een status een body en een message ==> dit is een json object
        "status" => "success",
        // anders "body" => htmlspecialchars($c->getText()),
        "body" => htmlspecialchars($_POST["text"]),
        "message" => "Comment saved"
    ];

    header("Content-Type: application/json"); // hier ga je zeggen dat je een json object gaat terugsturen
    echo json_encode($response); // hier gaat er eigenlijk een response terug komen die hierboven staat hier ga je een json object van maken

}
