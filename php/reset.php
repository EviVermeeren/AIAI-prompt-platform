<?php
ini_set('display_errors', 1);
include_once("../inc/bootstrap.php");
try {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $user = new User("oepsConstructor");
        $user->setResetToken($token);
        $resetToken = $user->checkResetToken();
        $timestamp = $user->checkTimestamp();
        if ($resetToken && $timestamp) {
            if (!empty($_POST)) {
                try {
                    $password = $_POST['password'];
                    $user->setPassword($password);
                    $user->updatePassword();
                    $result = "Password has been reset";
                    header("Location: ../php/login.php");
                } catch (Throwable $e) {
                    $passwordError = $e->getMessage();
                }
            }
        } else {
            throw new Exception("Token is not valid");
        }
    } else {
        throw new Exception("Token not found");
    }
} catch (Throwable $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" type="image/x-icon" href="../media/favicon.ico">
</head>

<body>
    <?php
    if (isset($error)) : ?>
        <p><?php echo $error ?></p>
    <?php else : ?>
        <form action="" method="post">
            <input type="password" name="password" placeholder="New password">

            <button type="submit">Reset password</button>
        </form>
    <?php endif ?>

</body>

</html>