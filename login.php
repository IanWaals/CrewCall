<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
}
include "src/functions.php";

$loginType = ($_GET['option'] === 'register') ? "register" : "login";

?>

<!DOCTYPE html>
<html>
    <?php htmlHead($loginType); ?>
    <body>
        <?php htmlPageHeader("index"); ?>
        <main style="margin-top: 30vh;" class="index">
            <h1 class="jaro"><?php echo ucfirst($_GET['option']) ?></h1>
            <?php
            if ($loginType == "login") {
                loginForm();
            } elseif ($loginType == "register") {
                registerForm();
            }
            ?>
        </main>
    </body>
</html>