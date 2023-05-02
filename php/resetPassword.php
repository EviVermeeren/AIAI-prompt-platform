<?php

require '../vendor/autoload.php'; // include sendgrid library
include_once("../classes/User.php"); // include head
include_once("../classes/Db.php"); // include head

$config = parse_ini_file('../config/config.ini', true);

$key = $config['keys']['sendgridapikey'];

if (!empty($_POST)) {
    $email = $_POST['email'];
    $user = new User($email);
    try {
        $correctEmail = $user->checkEmail($email);
        if ($correctEmail == true) {
            $user->setEmail($email);
            $user->setResetToken(bin2hex(openssl_random_pseudo_bytes(32)));
            $user->saveResetToken();
            $user->sendResetMail($key);
        }
    } catch (Throwable $e) {
        $emailError = $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <nav>
        <div class="logo">
            <a href="/index.html">PromptSwap</a>
        </div>
    </nav>

    <div id="app">
        <h1 class="titlelogin">PromptSwap</h1>
        <nav class="nav--login"></nav>

        <h4>An e-mail will be sent to you with
            instructions on how to reset your password.
        </h4>

        <form method="post">
            <input type="text" name="email" placeholder="Enter your e-mail address">
            <div class="flex flex-col items-center mb-10">
                <button class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 px-4 rounded mb-2" style="padding-left: 4.25rem; padding-right: 4.25rem;">Send mail</button>
            </div>
        </form>
    </div>
    <?php include_once("../inc/foot.inc.php"); ?>
</body>

</html>