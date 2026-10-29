<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    // session isn't started
    session_start();
}
include_once "src/functions.php";
include_once "src/db.php";

$conn = dbConnect();
$attendees = getAttendees();


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if  ($_GET['action'] == "create") {
        $eventTitle = $_POST['title'];
        $eventLocation = $_POST['location'];
        $eventDescription = $_POST['description'];
        $eventCategory = $_POST['category'];
        $eventMaxAttendees = (isset($_POST['max_attendees']) && $_POST['max_attendees'] !== '') ? intval($_POST['max_attendees']) : 0;
        $eventDateTime = $_POST['event_datetime'];
        $eventFull = 0;
    
        $sql = "INSERT INTO events (EventTitle, EventLocation, EventDescription, EventCategory, EventMaxAttendees, EventDateTime, EventHostId, EventFull) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssisii", $eventTitle, $eventLocation, $eventDescription, $eventCategory, $eventMaxAttendees, $eventDateTime, $_SESSION['user_id'], $eventFull);
    
            if (mysqli_stmt_execute($stmt)) {
                echo $_SESSION['user_id'];
                echo "upload succesful!!";
            }
        }
    } 
}
else if ($_GET['action'] == "SignUp") {
    $eventId = $_GET['event'];
    $userId = $_SESSION['user_id'];

    $alreadyAttending = isAlreadyAttending();

    if (!$alreadyAttending) {
        $sql = "INSERT INTO event_attendees (EventId, UserId) VALUES (?, ?)";
    
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $eventId, $userId);
    
            if (mysqli_stmt_execute($stmt)) {
                echo $eventId;
                echo $userId;
                echo "signed up succesfully!";
            }
        }
    
        $events = getEvents();
        foreach($events as $event) {
            if ($event['id'] == $eventId && $event['EventCategory'] == "limited spaces") {
                $newSpaces = $event['EventMaxAttendees'] - 1;
    
                $sql = "UPDATE events SET EventMaxAttendees = ? WHERE id = ?";
    
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ii", $newSpaces, $eventId);
    
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<br> updated succesfully";
                    }
                }
            } 
        }
    }
}