<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
}
include "src/functions.php";
$user = getSpecificUser();

?>

<!DOCTYPE html>
<html>
    <?php htmlHead("Account"); ?>
    <body>
        <?php htmlPageHeader("regular", "account"); ?>
        <main class="typeMain jaro">
            <h1>Acount settings & information</h1>
            <div class="accountTopParent">
                <div class="accountTopRow">
                    <div class="accountLeftChild">
                        <img src="src/images/pfps/<?php echo $user['UserProfilePic']; ?>" alt="Users Profile Picture">
                        <div>
                            <p><?php echo $user['UserName']; ?></p>
                            <form action="src/uploadPfp.php" method="post" enctype="multipart/form-data">
                                <label for="myFile" class="uploadBtn">Change profile <br> picture</label>
                                <input type="file" id="myFile" name="pfp" accept="image/*" hidden
                                    onchange="this.form.requestSubmit()">
                            </form>
                        </div>
                    </div>
                    <div class="accountRightChild">
                        <p><span class="accountLabel">Email:</span> <?php echo htmlspecialchars($user['UserEmail']); ?></p>
                        <p><span class="accountLabel">Password:</span> ********</p>
                        <a href="changePassword.php" class="orangeButton accountSmallBtn">Change Password</a>
                    </div>
                </div>

                <form action="src/updateBio.php" method="post" class="accountBioForm">
                    <label for="bio">Bio:</label>
                    <textarea id="bio" name="bio" class="accountBio" maxlength="500"><?php echo htmlspecialchars($user['UserBio'] ?? ''); ?></textarea>
                    <button type="submit" class="orangeButton accountWideBtn">Update bio</button>
                </form>

                <a href="src/logout.php" class="orangeButton accountLogout">Log out</a>
            </div>
        </main>
    </body>
</html>