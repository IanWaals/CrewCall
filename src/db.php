<?php

function dbConnect() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "crewcall";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error){
        die("connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function getUsers() {
    $conn = dbConnect();
    $sql = "SELECT * FROM users";
    $resource = $conn->query($sql) or die($conn->error);
    $users = $resource->fetch_all(MYSQLI_ASSOC);
    return $users;
}
function getSpecificUser() {
    $conn = dbConnect();

    $stmt = $conn->prepare("SELECT * from users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();

    $result = $stmt->get_result();

    $user = $result->fetch_assoc();

    return $user;
}

function getProfilePicture() {
    // Get your MySQLi database connection
    $conn = dbConnect();
    
    // Prepare the statement
    $stmt = $conn->prepare("SELECT UserProfilePic FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']); // "i" specifies the variable type is an integer
    $stmt->execute();
    
    // Get the result set from MySQLi
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $profilePic = (!empty($user['UserProfilePic'])) ? htmlspecialchars($user['UserProfilePic']) : 'default-icon.jpg';
    } else {
        $profilePic = 'default-icon.jpg';
    };
    return $profilePic;
}

?>