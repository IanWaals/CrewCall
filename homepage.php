<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
}
include "src/functions.php"

?>

<!DOCTYPE html>
<html>
    <?php htmlHead("Account"); ?>
    <body>
        <?php htmlPageHeader("regular", "homepage"); ?>
        <p></p>
    </body>
</html>