<?php

class Prompt {
  private $title;
  private $description;
  private $model;
  private $user;
  private $filename;

  public function __construct($title, $description, $model, $user, $filename) {
    $this->title = $title;
    $this->description = $description;
    $this->model = $model;
    $this->user = $user;
    $this->filename = $filename;
  }

  public function getTitle() {
    return $this->title;
  }

  public function getDescription() {
    return $this->description;
  }

  public function getModel() {
    return $this->model;
  }

  public function getUser() {
    return $this->user;
  }

  public function getFilename() {
    return $this->filename;
  }

    public function save() {
        $db = Db::getInstance();
        $stmt = $db->prepare("INSERT INTO prompt (title, description, model, user, filename) VALUES (:title, :description, :model, :user, :filename)");
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':model', $this->model);
        $stmt->bindParam(':user', $this->user);
        $stmt->bindParam(':filename', $this->filename);
        return $stmt->execute();
    }
}