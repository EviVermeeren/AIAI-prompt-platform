<?php
require_once "../inc/bootstrap.php";
require_once "../inc/functions.inc.php";
$conn = Db::getInstance(); // connect to the database

if (!$conn) {
    echo "Error: Failed to connect to database";
    exit;
}

$sql = "SELECT * FROM prompts WHERE reported = 1"; // SQL statement to select all prompts that have not been approved
$stmt = $conn->query($sql); // Execute the SQL statement and return the result set

$sql2 = "SELECT * FROM `users` WHERE reported = 1"; // SQL statement to select all prompts that have not been approved
$stmt2 = $conn->query($sql2); // Execute the SQL statement and return the result set

$sql3 = "SELECT * FROM `users` WHERE blocked = 1"; // SQL statement to select all prompts that have not been approved
$stmt3 = $conn->query($sql3); // Execute the SQL statement and return the result set

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
    <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css"> <!-- Import the font -->
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>"> <!-- Import the CSS file -->
    <script src="../css/script.js"></script> <!-- Import the JavaScript file -->
    <link rel="icon" type="image/x-icon" href="../media/favicon.ico">
</head>

<body>
    <?php include_once("../inc/nav.inc.php"); ?>

    <div id="headerApproval">
        <h1>Report List</h1> <!-- Display the page heading -->
    </div>

    <div id="headerApproval">
        <h2>Reported prompts:</h2> <!-- Display the page heading -->
    </div>

    <div id="approvalList">
        <?php if ($stmt->rowCount() > 0) : ?> <!-- Check if there are any prompts needing approval -->
            <table id="approveList"> <!-- Create a table to display the prompts -->
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
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?> <!-- Loop through the result set and display each prompt --> <!-- Loop through each prompt needing approval -->
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
                            <button class="approvePromtList" onclick="approvePrompt(<?php echo $row['id']; ?>)"> Free prompt 🥐 </button> <!-- Button to approve the prompt Approve 👍 -->
                            <button class="deletePromtList" onclick="deletePrompt(<?php echo $row['id']; ?>)">Delete prompt 💀</button> <!-- Button to reject the prompt Reject 👎 -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else : ?>
            <p>No prompts needing a review.</p> <!-- Display a message if there are no prompts needing approval -->
        <?php endif; ?>
    </div>


    <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->


    <div id="headerApproval">
        <h2>Reported Users:</h2> <!-- Display the page heading -->
    </div>

    <div id="approvalList">
        <?php if ($stmt2->rowCount() > 0) : ?> <!-- Check if there are any prompts needing approval -->
            <table id="approveList"> <!-- Create a table to display the prompts -->
                <tr>
                    <th>Username</th>
                    <th> Email</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) : ?> <!-- Loop through the result set and display each prompt --> <!-- Loop through each prompt needing approval -->
                    <tr>
                        <td><?php echo isset($row["username"]) ? $row["username"] : "" ?></td> <!-- Display the name for the prompt (if it exists) -->
                        <td><?php echo isset($row["email"]) ? $row["email"] : "" ?></td> <!-- Display the characteristics for the prompt (if it exists) -->
                        <td>
                            <button class="approvePromtList" onclick="freeUser('<?php echo $row['id']; ?>')"> Free user 🥐 </button> <!-- Button to free the user 👍 -->
                            <button class="deletePromtList" onclick="banUser(<?php echo $row['id']; ?>)">Ban user 💀</button> <!-- Button to ban the user 👎 -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else : ?>
            <p>No users or needing a review.</p> <!-- Display a message if there are no prompts needing approval -->
        <?php endif; ?>
    </div>


    <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->


    <div id="headerApproval">
        <h2>Blocked Users:</h2> <!-- Display the page heading -->
    </div>

    <div id="approvalList">
        <?php if ($stmt3->rowCount() > 0) : ?> <!-- Check if there are any blocked users -->
            <table id="approveList"> <!-- Create a table to display the blocked users -->
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $stmt3->fetch(PDO::FETCH_ASSOC)) : ?> <!-- Loop through the result set and display each blocked user -->
                    <tr>
                        <td><?php echo isset($row["username"]) ? $row["username"] : "" ?></td> <!-- Display the username for the blocked user (if it exists) -->
                        <td><?php echo isset($row["email"]) ? $row["email"] : "" ?></td> <!-- Display the email for the blocked user (if it exists) -->
                        <td>
                            <button class="unblockUser" onclick="unBlock(<?php echo $row['id']; ?>)">Unblock user 🥐</button> <!-- Button to unblock the user -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else : ?>
            <p>No blocked users.</p> <!-- Display a message if there are no blocked users -->
        <?php endif; ?>
    </div>





    <?php include_once("../inc/foot.inc.php"); ?>
</body>

</html>