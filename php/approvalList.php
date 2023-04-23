<?php

require_once "../inc/bootstrap.php";
require_once "../inc/functions.inc.php";
$conn = Db::getInstance();

if (!$conn) {
    echo "Error: Failed to connect to database";
    exit;
}

$sql = "SELECT * FROM prompts WHERE approved = 0";
$stmt = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
    <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css">
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
    <script>
        function approvePrompt(id) {
            if (confirm("Are you sure you want to approve this prompt?")) {
                var xhttp = new XMLHttpRequest(); // creates a new XMLHttpRequest object
                location.reload(); // reloads the current page
                xhttp.onreadystatechange = function() { // sets a callback function to run when the readyState property changes
                    if (this.readyState == 4 && this.status == 200) { // checks if the request has been completed and the response status is OK
                        if (this.responseText == "success") { // checks if the response text is "success"
                            alert("Prompt approved successfully."); // displays a success message
                            location.reload(); // reloads the current page
                        }
                    }
                };
                xhttp.open("GET", "approvePrompt.php?id=" + id, true); // opens a GET request to the specified URL
                xhttp.send(); // sends the request to the server
            }
        }

        function deletePrompt(id) {
            if (confirm("Are you sure you want to delete this prompt?")) {
                var xhttp = new XMLHttpRequest(); // creates a new XMLHttpRequest object
                xhttp.onreadystatechange = function() { // sets a callback function to run when the readyState property changes
                    if (this.readyState == 4 && this.status == 200) { // checks if the request has been completed and the response status is OK
                        if (this.responseText == "success") { // checks if the response text is "success"
                            alert("Prompt deleted successfully."); // displays a success message
                            location.reload(); // reloads the current page
                        }
                    }
                };
                xhttp.open("GET", "rejectPrompt.php?id=" + id, true); // opens a GET request to the specified URL
                xhttp.send(); // sends the request to the server      
            }
        }
    </script>
</head>

<body>
    <?php include_once("../inc/nav.inc.php"); ?>

    <div id="header">
        <h1>Prompts Approval List</h1>
    </div>

    <div id="approvalList">
        <?php if ($stmt->rowCount() > 0) : ?> <!-- Check if there are any prompts needing approval -->
            <table>
                <tr>
                    <th>Model</th>
                    <th>Name</th>
                    <th>Characteristics</th>
                    <th>Submitted By</th>
                    <th>Date Submitted</th>
                    <th>Price</th>
                    <th>Tags</th>
                    <th>Picture</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?> <!-- Loop through each prompt needing approval -->
                    <tr>
                        <td><?php echo $row["model"] ?></td> <!-- Display the model name for the prompt -->
                        <td><?php echo isset($row["name"]) ? $row["name"] : "" ?></td> <!-- Display the name for the prompt (if it exists) -->
                        <td><?php echo isset($row["characteristics"]) ? $row["characteristics"] : "" ?></td> <!-- Display the characteristics for the prompt (if it exists) -->
                        <td><?php echo $row["user"] ?></td> <!-- Display the user who submitted the prompt -->
                        <td><?php echo $row["date"] ?></td> <!-- Display the date the prompt was submitted -->
                        <td><?php echo $row["price"] ?></td> <!-- Display the price of  the prompt  -->
                        <td><?php echo $row["tags"] ?></td> <!-- Display the tags of the prompt  -->
                        <td> <img src="../media/<?php echo $row["pictures"] ?>" alt="" class="approveimg"></td> <!-- Display the picture of the prompt -->
                        <td>
                            <button onclick="approvePrompt(<?php echo $row['id']; ?>)"> YAS QUEEN 💅</button> <!-- Button to approve the prompt Approve 👍 -->
                            <button onclick="deletePrompt(<?php echo $row['id']; ?>)">Yeet and delete 🖕</button> <!-- Button to reject the prompt Reject 👎 -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else : ?>
            <p>No prompts needing approval.</p> <!-- Display a message if there are no prompts needing approval -->
        <?php endif; ?>
    </div>

    <?php include_once("../inc/foot.inc.php"); ?>
</body>

</html>