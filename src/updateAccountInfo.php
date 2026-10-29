<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once "db.php";
$conn = dbConnect();
$user = getSpecificUser($_SESSION['user_id']);

if ($_GET['option'] == "pfp") {
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_FILES['pfp'])) {
        $tmp = $_FILES['pfp']['tmp_name'];
    
        $info = getimagesize($tmp);
        $allowed = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif', IMAGETYPE_WEBP => 'webp'];
    
        if ($info === false || !isset($allowed[$info[2]]) || $_FILES['pfp']['size'] > 2 * 1024 * 1024) {
            die("Invalid image (must be jpg/png/gif/webp, max 2MB).");
        }
    
        $filename = uniqid('pfp_', true) . '.' . $allowed[$info[2]];
    
        if (!move_uploaded_file($tmp, __DIR__ . "/images/pfps/" . $filename)) {
            die("Could not save the uploaded file.");
        }
    
        $sql = "UPDATE users SET UserProfilePic = ? WHERE id = ?";
    
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $filename, $_SESSION['user_id']);
    
            if (mysqli_stmt_execute($stmt)) {
                echo $_SESSION['user_id'];
                echo "upload successful!";
            }
        }
    }
} else if ($_GET['option'] == "password") {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        
        //check old password
        $userEntry = $_POST['current_password'];
        $userHashedPassword = $user['UserPassword'];

        if(password_verify($userEntry, $userHashedPassword)) {
            $sql = "UPDATE users SET UserPassword = ? WHERE id = ?";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", (password_hash($_POST['new_password'], PASSWORD_DEFAULT)), $_SESSION['user_id']);

                if (mysqli_stmt_execute($stmt)) {
                    echo $_SESSION['user_id'];
                    echo "upload succesful!!";
                }
            }
        }

    }
} else if ($_GET['option'] == "biography") {
    echo "AAAAAAAAA";
    echo  $_SERVER['REQUEST_METHOD'];
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $newBio = $_POST['bio'];
        $sql = "UPDATE users SET UserBio = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $newBio, $_SESSION['user_id']);

            if (mysqli_stmt_execute($stmt)) {
                echo $_SESSION['user_id'];
                echo "upload succesful!!";
            }
        }
    }
}

header("Location: ../account.php");
exit();