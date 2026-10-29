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
function getSpecificUser($id) {
    $conn = dbConnect();

    $stmt = $conn->prepare("SELECT * from users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    $user = $result->fetch_assoc();

    return $user;
}
function getProfilePicture($id) {
    // Get your MySQLi database connection
    $conn = dbConnect();
    
    // Prepare the statement
    $stmt = $conn->prepare("SELECT UserProfilePic FROM users WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" specifies the variable type is an integer
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

function getFriendships() {
    $conn = dbConnect();

    $sql = "SELECT * from friendships";
    $resource = $conn->query($sql) or die($conn->error);
    $friendships = $resource->fetch_all(MYSQLI_ASSOC);
    return $friendships;
}

function getEvents() {
    $conn = dbConnect();

    $sql = "SELECT * from events";
    $resource = $conn->query($sql) or die($conn->error);
    $events = $resource->fetch_all(MYSQLI_ASSOC);
    return $events;
}

function getAttendees() {
    $conn = dbConnect();

    $sql = "SELECT * from event_attendees";
    $resource = $conn->query($sql) or die($conn->error);
    $attendees = $resource->fetch_all(MYSQLI_ASSOC);
    return $attendees;
}

?>