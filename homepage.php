<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
}
include_once "src/functions.php";
include_once "src/db.php";

function compileUserFriends(array $friendships, string $userId): array {
    $userFriendships = [];
    foreach ($friendships as $friendship) {
        if ($friendship['isAccepted'] == 1) {
            if ($friendship['friendshipSender'] === $userId) {
                $userFriendships[] = $friendship['friendshipRecipient'];
            } elseif ($friendship['friendshipRecipient'] === $userId) {
                $userFriendships[] = $friendship['friendshipSender'];
            }
        }
    }
    return $userFriendships;
}

$friendships = getFriendships();
$userFriendships = compileUserFriends($friendships, $_SESSION['user_id']);


?>

<!DOCTYPE html>
<html>
    <?php htmlHead("Account"); ?>
    <body>
        <?php htmlPageHeader("regular", "homepage"); ?>
        <main class="homeMain jaro">
            <h1>Welcome to CrewCall, <?php echo $_SESSION['username']; ?></h1>
            <section class="homeSection">
                <h2>Your friends</h2>
                <div class="friendsContainer">
                    <?php
                    foreach ($userFriendships as $id) {
                        $user = getSpecificUser($id);
                    ?>
                        <div class="friend">
                            <img src="src/images/pfps/<?php echo getProfilePicture($id); ?>" alt="">
                            <p><?php echo $user['UserName']; ?></p>
                        </div>
                    <?php } ?>
                </div>
            </section>
        </main>
    </body>
</html>