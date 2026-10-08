<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
}
include "src/functions.php";

if (!empty($_SESSION['loggedIn'])) {
    header("location: homepage.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
    <?php htmlHead("Not Logged In!"); ?>
    <body>
        <!-- header -->
        <?php htmlPageHeader("index"); ?>

        <!-- body -->   
        <main class="index">
            <h1 class="jaro">Oops, it looks like you're not logged in!</h1>
            <p class="jaro">To be able to meet up with like-minded athletes it’s useful to be identifiable. <br>Log in or create an account to get on with your cooperative sports journey!</p>
            <a href="login.php?option=login" class="orangeButton jaro">Login</a>
            <a href="login.php?option=register" class="orangeButton jaro">Register</a>
        </main>
    </body>
</html>