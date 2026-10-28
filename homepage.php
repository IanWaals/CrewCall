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

// Friend modal: opened with homepage.php?modal=friend&user=<id>
// Only friends of the logged in user can be shown.
$modalUser = null;
$modalUserId = null;
if (($_GET['modal'] ?? '') === 'friend' && isset($_GET['user'])) {
    $requestedId = (string) $_GET['user'];
    if (in_array($requestedId, array_map('strval', $userFriendships), true)) {
        $modalUserId = $requestedId;
        $modalUser = getSpecificUser($modalUserId);
    }
}

?>

<!DOCTYPE html>
<html>
    <?php htmlHead("Home"); ?>
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
                        <a href="homepage.php?modal=friend&amp;user=<?php echo urlencode($id); ?>">
                            <div class="friend">
                                <img src="src/images/pfps/<?php echo getProfilePicture($id); ?>" alt="">
                                <p><?php echo $user['UserName']; ?></p>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </section>
            <br>
            <section class="homeSection">
                <h2>Trending events</h2>
            </section>
        </main>

        <?php createEventButton("homepage"); ?>

        <?php if ($modalUser): ?>
        <!-- Friend profile modal (opened via ?modal=friend&user=<id>, closes by linking back to homepage.php) -->
        <div class="modalOverlay open">
            <div class="modalBox friendModalBox jaro">
                <a href="homepage.php" class="modalClose friendModalClose" aria-label="Close">&times;</a>
                <div class="friendModalTop">
                    <img src="src/images/pfps/<?php echo getProfilePicture($modalUserId); ?>" alt="">
                    <div class="friendModalInfo">
                        <h2><?php echo $modalUser['UserName']; ?></h2>
                        <p class="friendStatus">Your friend</p>
                    </div>
                </div>
                <p class="friendModalBio">
                    <?php echo $modalUser['UserBio']; ?>
                </p>
                <h3>Attending events:</h3>
                <!-- <div class="eventCard">
                    <p class="eventTitle">Looking for 5 people for 3x3 basketall</p>
                    <p class="eventLocation">
                        <svg viewBox="0 0 24 24" width="36" height="36" aria-hidden="true">
                            <path fill="currentColor" d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/>
                        </svg>
                        You know where
                    </p>
                    <p>Sport: Basketball</p>
                    <p>Category: Limited spaces</p>
                    <a href="#" class="orangeButton eventMoreInfo">More info</a>
                </div> -->
            </div>
        </div>
        <?php endif; ?>
    </body>
</html>