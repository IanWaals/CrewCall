<?php


include "src/db.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = dbconnect();

if ($_GET['option'] == "login") {
    $users = getUsers();

    $authenticatedUser = null;

    foreach ($users as $user) {
        if ($user['UserName'] === trim($_POST['userEmail']) || $user['UserEmail'] === trim($_POST['userEmail'])) {
            
            if (password_verify($_POST['password'], $user['UserPassword'])) {
                $authenticatedUser = $user;
                break;
            }
        }
    }

    if ($authenticatedUser) {
        $_SESSION['user_id'] = $authenticatedUser['id'];
        $_SESSION['username'] = $authenticatedUser['UserName'];
        $_SESSION['loggedIn'] = true;
        
        echo "Login successful! Welcome back, " . htmlspecialchars($authenticatedUser['UserName']);

        header("location: homepage.php");
    } else {
        // Login failed
        echo "Invalid username/email or password.";

        HEADER("location: login.php?option=login&status=failed");
    }

} elseif ($_GET['option'] == "register") {
    $sql = "INSERT INTO users (userEmail, userName, userPassword)
    VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        mysqli_stmt_bind_param($stmt, "sss", $email, $username, $password);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Registration successful!";
            header("location: homepage.php");
        } else {
            echo "Error: " . mysqli_stmt_error($stmt);
        }
        
        mysqli_stmt_close($stmt);
    }
}

?>