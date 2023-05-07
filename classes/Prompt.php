<?php

include_once("../inc/bootstrap.php"); // include bootstrap file

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
  private $conn;

  public function setId($id)
  {
    $this->id = $id;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  public function setUser($user)
  {
    $this->user = $user;
  }

  public function setRating($rating)
  {
    $this->rating = $rating;
  }

  public function setDescription($description)
  {
    $this->description = $description;
  }

  public function setPrice($price)
  {
    $this->price = $price;
  }

  public function setCharacteristics($characteristics)
  {
    $this->characteristics = $characteristics;
  }

  public function setModel($model)
  {
    $this->model = $model;
  }

  public function setPrompt($prompt)
  {
    $this->prompt = $prompt;
  }

  public function setPictures($pictures)
  {
    $this->pictures = $pictures;
  }

  public function setDate($date)
  {
    $this->date = $date;
  }

  public function setTags($tags)
  {
    $this->tags = $tags;
  }

  public function setConnection($conn)
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


  public function getPrompts($promptsPerPage, $page)
  {
    $offset = ($page - 1) * $promptsPerPage; // Calculate the offset for the current page

    // Query the database to get the total number of prompts
    $sql = "SELECT COUNT(*) AS count FROM prompts"; // Get the total number of prompts
    $result = $this->conn->query($sql); // Execute the query
    $row = $result->fetch(); // Fetch the result
    $totalPrompts = $row['count']; // Get the total number of prompts

    $totalPages = ceil($totalPrompts / $promptsPerPage); // Calculate the total number of pages

    // Query the database to get the prompts for the current page
    $sql = "SELECT * FROM prompts WHERE approved=1 ORDER BY date DESC LIMIT :limit OFFSET :offset";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':limit', $promptsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll();

    return array('prompts' => $result, 'totalPages' => $totalPages);
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

  public function getPromptsByUser($email)
  {
    $conn = Db::getInstance();
    $stmt = $conn->prepare("SELECT * FROM prompts WHERE user=:user");
    $stmt->bindParam(":user", $email);
    $stmt->execute();
    $prompts = $stmt->fetchAll();

    return $prompts;
  }

  public function getPromptById($id)
  {
    $conn = Db::getInstance();
    $stmt = $conn->prepare("SELECT * FROM prompts WHERE id=:id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $prompt = $stmt->fetch();
    return $prompt;
  }

  public function getUsernameByEmail($email)
  {
    $stmt = $this->conn->prepare("SELECT username FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $user_row['username'];
    return $username;
  }

  public function getAllByUser($email)
  {
    $query = $this->conn->prepare("SELECT * FROM prompts WHERE user = :email");
    $query->bindValue(":email", $email);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  public function handleFileUpload($file)
  {
    $file_name = $file["name"];
    $file_temp_name = $file["tmp_name"];
    $file_size = $file["size"];
    $file_error = $file["error"];

    if ($file_error !== UPLOAD_ERR_OK) {
      $error = "Upload failed with error code $file_error.";
      exit;
    }

    if ($file_size > 1000000) {
      $error = "File is too big"; // set error message
      header("Location: ../php/upload.php?error=" . urlencode($error)); // redirect to edit account page
      exit;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+\.[a-zA-Z0-9]{3,4}$/', $file_name)) {
      $error = "File name is not correct"; // set error message
      header("Location: ../php/upload.php?error=" . urlencode($error)); // redirect to edit account page
      exit;
    }

    $uploads_dir = "../media/";
    $file_path = $uploads_dir . $file_name;
    if (!move_uploaded_file($file_temp_name, $file_path)) {
      $error = "Failed to move uploaded file.";
      exit;
    }

    return $file_name;
  }

  public function createPrompt(
    $name,
    $email,
    $description,
    $model,
    $file_name,
    $selected_categories,
    $price,
    $prompt_text,
    $tags
  ) {
    // Your create logic goes here, using the provided arguments

    // Perform the database insertion
    $conn = Db::getInstance();
    $query = $conn->prepare("INSERT INTO prompts (name, user, description, model, pictures, characteristics, price, prompt, tags, date) VALUES (:name, :email, :description, :model, :pictures, :categories, :price, :prompt, :tags, :date)");
    $query->bindValue(":name", $name);
    $query->bindValue(":email", $email);
    $query->bindValue(":description", $description);
    $query->bindValue(":model", $model);
    $query->bindValue(":pictures", $file_name);
    $query->bindValue(":categories", implode(", ", $selected_categories));
    $query->bindValue(":price", $price);
    $query->bindValue(":prompt", $prompt_text);
    $query->bindValue(":tags", $tags);
    $query->bindValue(":date", date("Y-m-d H:i:s"));
    $query->execute();
  }

  public function getPromptsByEmail($email)
  {
    $conn = Db::getInstance(); // Connect to the database
    $email = $conn->quote($email);
    return $conn->query("SELECT * FROM prompts WHERE user=$email")->fetchAll();
  }

  public function getDetailPromptByID($id)
  {
    $conn = Db::getInstance();
    $sql = "SELECT * FROM prompts WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      return $row;
    } else {
      return false;
    }
  }
}
