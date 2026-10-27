<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
}
include_once "src/functions.php";
include_once "src/db.php";

$user = getSpecificUser($_SESSION['user_id']);
$userProfilePic = getProfilePicture($_SESSION['user_id']);

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
                        <img src="src/images/pfps/<?php echo $userProfilePic; ?>" alt="Users Profile Picture">
                        <div>
                            <p><?php echo $user['UserName']; ?></p>
                            <form action="src/updateAccountInfo.php?option=pfp" method="post" enctype="multipart/form-data">
                                <label for="myFile" class="uploadBtn">Change profile <br> picture</label>
                                <input type="file" id="myFile" name="pfp" accept="image/*" hidden
                                    onchange="this.form.requestSubmit()">
                            </form>
                        </div>
                    </div>
                    <div class="accountRightChild">
                        <p><span class="accountLabel">Email:</span> <?php echo htmlspecialchars($user['UserEmail']); ?></p>
                        <p><span class="accountLabel">Password:</span> ********</p>
                        <a href="account.php?changepassword=true" class="orangeButton accountSmallBtn">Change Password</a>
                    </div>
                </div>

                <form action="src/updateAccountInfo.php?option=biography" method="post" class="accountBioForm">
                    <label for="bio">Bio:</label>
                    <textarea id="bio" name="bio" class="accountBio" maxlength="500"><?php echo htmlspecialchars($user['UserBio'] ?? ''); ?></textarea>
                    <button type="submit" class="orangeButton accountWideBtn">Update bio</button>
                </form>

                <a href="loginhandler.php?option=logout" class="orangeButton accountLogout">Log out</a>
            </div>
        </main>

        <?php $showPwModal = isset($_GET['changepassword']) && $_GET['changepassword'] === 'true'; ?>
        <div class="modalOverlay<?php echo $showPwModal ? ' open' : ''; ?>" id="passwordModal">
            <div class="modalBox jaro" role="dialog" aria-modal="true" aria-labelledby="pwModalTitle">
                <a href="account.php" class="modalClose" aria-label="Close">&times;</a>
                <h2 id="pwModalTitle">Change password</h2>
                <form action="src/updateAccountInfo.php?option=password" method="post">
                    <label for="currentPassword">Current password</label>
                    <input type="password" id="currentPassword" name="current_password" class="inputSmall" required>

                    <label for="newPassword">New password</label>
                    <input type="password" id="newPassword" name="new_password" class="inputSmall" required>

                    <label for="confirmPassword">Confirm new password</label>
                    <input type="password" id="confirmPassword" name="confirm_password" class="inputSmall" required>

                    <?php if (($_GET['error'] ?? '') === 'passwordmismatch'): ?>
                        <p class="critical jaro">Passwords do not match.</p>
                    <?php endif; ?>

                    <p id="passwordMessage" style="color: red; display: none;" class="jaro">Passwords do not match.</p>
                    <div style="display: flex; justify-content: space-between;">
                        <button id="registerButton" type="submit" style="width: 45%;" class="orangeButton accountWideBtn">Save password</button>
                        <a href="account.php" style="width: 45%;" class="orangeButton accountWideBtn">Cancel</a>
                    </div>
                </form>

                <script>
                    const pw      = document.getElementById('newPassword');
                    const pwc     = document.getElementById('confirmPassword');
                    const button  = document.getElementById('registerButton');
                    const message = document.getElementById('passwordMessage');

                    function checkMatch() {
                        const match = pw.value !== '' && pw.value === pwc.value;
                        button.disabled = !match;
                        // Only show the warning once the user has started typing in the confirm field
                        message.style.display = (pwc.value !== '' && !match) ? 'block' : 'none';
                    }

                    pw.addEventListener('input', checkMatch);
                    pwc.addEventListener('input', checkMatch);
                </script>
            </div>
        </div>
    </body>
</html>