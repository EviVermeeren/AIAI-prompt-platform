<?php

class Prompt
{
  private $id;
  private $name;
  private $user;
  private $rating;
  private $description;
  private $price;
  private $characteristics;
  private $model;
  private $prompt;
  private $pictures;
  private $date;
  private $tags;

  public function __construct($id, $name, $user, $rating, $description, $price, $characteristics, $model, $prompt, $pictures, $date, $tags)
  {
    $this->id = $id;
    $this->name = $name;
    $this->user = $user;
    $this->rating = $rating;
    $this->description = $description;
    $this->price = $price;
    $this->characteristics = $characteristics;
    $this->model = $model;
    $this->prompt = $prompt;
    $this->pictures = $pictures;
    $this->date = $date;
    $this->tags = $tags;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getUser()
  {
    return $this->user;
  }

  public function getRating()
  {
    return $this->rating;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function getPrice()
  {
    return $this->price;
  }

  public function getCharacteristics()
  {
    return $this->characteristics;
  }

  public function getModel()
  {
    return $this->model;
  }

  public function getPrompt()
  {
    return $this->prompt;
  }

  public function getPictures()
  {
    return $this->pictures;
  }

  public function getDate()
  {
    return $this->date;
  }

  public function getTags()
  {
    return $this->tags;
  }

  public function isFavorite($user_id)
  {
    $db = Db::getInstance();
    $stmt = $db->prepare("SELECT * FROM favorites WHERE user_id = :user_id AND prompt_id = :prompt_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':prompt_id', $this->id);
    $stmt->execute();
    return $stmt->rowCount() > 0;
  }

  public function save()
  {
    $db = Db::getInstance();
    $stmt = $db->prepare("INSERT INTO prompts (name, user, rating, description, price, characteristics, model, prompt, pictures, date, tags) VALUES (:name, :user, :rating, :description, :price, :characteristics, :model, :prompt, :pictures, :date, :tags)");
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':user', $this->user);
    $stmt->bindParam(':rating', $this->rating);
    $stmt->bindParam(':description', $this->description);
    $stmt->bindParam(':price', $this->price);
    $stmt->bindParam(':characteristics', $this->characteristics);
    $stmt->bindParam(':model', $this->model);
    $stmt->bindParam(':prompt', $this->prompt);
    $stmt->bindParam(':pictures', $this->pictures);
    $stmt->bindParam(':date', $this->date);
    $stmt->bindParam(':tags', $this->tags);
    $stmt->execute();
  }
}
