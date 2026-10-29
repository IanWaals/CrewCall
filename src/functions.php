<?php

include_once "db.php";

function htmlHead($pageTitle) {
    ?>
    <head>
        <title><?php echo $pageTitle; ?></title>
        <link rel="stylesheet" href="StyleSheets/style.css">
        <link rel="icon" type="image/x-icon" href="src/images/Logo.png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jaro:opsz@6..72&display=swap" rel="stylesheet">
    </head>
    <?php
}

function htmlPageHeader($pageType, $activePage = "") {
    if (isset($_SESSION['user_id'])) {
        $profilePic = getProfilePicture($_SESSION['user_id']);
    }

    ?>
    <header class="<?php echo $pageType; ?>">
        <img src="src/images/logo.png" alt="Company logo" class="<?php echo $pageType; ?>">
        <?php
        if ($pageType == "regular") {
            ?>
            <nav>
                <a class="navText jaro<?php echo $activePage == 'homepage' ? ' active' : ''; ?>" href="homepage.php">Homepage</a>
                <a class="navText jaro<?php echo $activePage == 'browse' ? ' active' : ''; ?>" href="browse.php">Browse</a>
                <a href="account.php"><img class="profilePic<?php echo $activePage == 'account' ? ' active' : ''; ?>" src="src/images/pfps/<?php echo $profilePic; ?>" alt="Users Profile Picture"></a>
            </nav>
            <?php
        }
        ?>
    </header>
    <?php
}

function loginForm() {
    ?>
    <form action="loginhandler.php?option=login" method="POST">
        <div class="jaro">
            <label for="userEmail">Username or Email</label><br>
            <input type="text" name="userEmail" class="inputSmall" placeholder="Enter Here..." required><br>
        </div>
        <div class="jaro">
            <label for="password">Password</label><br>
            <input type="password" name="password" class="inputSmall" placeholder="Enter Here..." required>
        </div>
        <?php
        if (isset($_GET['status']) && $_GET['status'] == "failed") {
            ?> <p class="critical jaro">Invalid login credentials</p> <?php
        }
        ?>
        <p class="jaro">Don't have an account yet? click <a style="color: white;" href="login.php?option=register">Here</a></p>
        <input type="submit" class="orangeButton jaro">
    </form>
    <?php
}

function registerForm() {
    ?>
    <form action="loginhandler.php?option=register" method="POST" id="registerForm">
        <div class="registerParent jaro">
            <div>
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" class="inputSmallest" placeholder="Enter Here..." required><br>
            </div>
            <div>
                <label for="username">Username</label><br>
                <input type="text" id="username" name="username" class="inputSmallest" placeholder="Enter Here..." required><br>
            </div>
        </div>
        <div class="registerParent jaro">
            <div>
                <label for="password">Password</label><br>
                <input type="password" id="password" name="password" class="inputSmallest" placeholder="Enter Here..." required>
            </div>
            <div>
                <label for="passwordConfirm">Confirm password</label><br>
                <input type="password" id="passwordConfirm" name="passwordConfirm" class="inputSmallest" placeholder="Enter Here..." required>
            </div>
        </div>

        <p id="passwordMessage" style="color: red; display: none;" class="jaro">Passwords do not match.</p>
        <?php if (($_GET['error'] ?? '') === 'passwordmismatch'): ?>
            <p class="critical jaro">Passwords do not match.</p>
        <?php endif; ?>

        <p class="jaro">Already have an account? click <a style="color: white;" href="login.php?option=login">Here</a></p>
        <input type="submit" id="registerButton" value="Register" class="orangeButton jaro" disabled>
    </form>

    <script>
        const pw      = document.getElementById('password');
        const pwc     = document.getElementById('passwordConfirm');
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
    <?php
}

function createEventButton($header) {
    ?>
    <a href="<?php echo $header; ?>.php?option=createEvent" class="createEventOpen jaro">+ create event</a>
    <?php

    if (isset($_GET['option']) && $_GET['option'] == "createEvent") {
        ?>
        <div class="modalOverlay open">
            <div class="modalBox friendModalBox jaro">
                <a href="homepage.php" class="modalClose friendModalClose" aria-label="Close">&times;</a>
                <h1>Create event</h1>
                <form action="handleEvents.php?action=create" method="post" class="createEventForm">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="inputSmall" required>

                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" class="inputSmall" required>

                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" class="inputSmall">

                    <div class="formRow">
                        <div class="formField">
                            <label for="category">Category</label>
                            <select id="category" name="category" class="inputSmall" required>
                                <option value="anyone can join">Anyone can join</option>
                                <option value="limited spaces">Limited spaces</option>
                                <option value="spectator only">Spectator only</option>
                            </select>
                        </div>

                        <div id="maxAttendeesGroup" class="formField hidden">
                            <label for="max_attendees">Max attendees</label>
                            <input type="number" id="max_attendees" name="max_attendees" class="inputSmall" min="1" step="1">
                        </div>
                    </div>

                    <label for="event_datetime">Date and time</label>
                    <input type="datetime-local" id="event_datetime" name="event_datetime" class="inputSmall" required>

                    <button type="submit" class="orangeButton createEventSubmit jaro">Create event</button>
                </form>

                <script>
                    const category = document.getElementById('category');
                    const group = document.getElementById('maxAttendeesGroup');
                    const maxInput = document.getElementById('max_attendees');

                    function toggleMax() {
                        const limited = category.value === 'limited spaces';
                        group.classList.toggle('hidden', !limited);
                        maxInput.required = limited;
                        if (!limited) maxInput.value = '';
                    }
                    category.addEventListener('change', toggleMax);
                    toggleMax();
                </script>
            </div>
        </div>
        <?php
    }
}

function eventCard($i, $events) {
    // Keep the current page and any other existing GET params, add/replace "event"
    $params = $_GET;
    $params['event'] = $events[$i]['id'];
    $moreInfoUrl = '?' . http_build_query($params);
?>
<div class="eventCard">
    <p class="eventTitle"><?php echo htmlspecialchars($events[$i]['EventTitle']); ?></p>
    <p class="eventLocation">
        <svg viewBox="0 0 24 24" width="36" height="36" aria-hidden="true">
            <path fill="currentColor" d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/>
        </svg>
        <?php echo htmlspecialchars($events[$i]['EventLocation']); ?>
    </p>
    <p>Category: <?php echo htmlspecialchars($events[$i]['EventCategory']); ?></p>
    <a href="<?php echo htmlspecialchars($moreInfoUrl); ?>" class="orangeButton eventMoreInfo">More info</a>
</div>
<?php
}

function eventModal($events) {
    if (!isset($_GET['event'])) {
        return;
    }

    $event = null;
    foreach ($events as $e) {
        if ((string)$e['id'] === (string)$_GET['event']) {
            $event = $e;
            break;
        }
    }
    if ($event === null) {
        return;
    }

    $params = $_GET;
    unset($params['event']);
    $closeUrl = '?' . http_build_query($params);
    if ($closeUrl === '?') {
        $closeUrl = strtok($_SERVER['REQUEST_URI'], '?');
    }

    $categoryString = ($event['EventCategory'] == "limited spaces")
    ? ". Spaces left: " . $event['EventMaxAttendees']
    : ".";
    ?>
    <div class="modalOverlay open">
        <div class="modalBox eventModalBox jaro">
            <h2><?php echo $event['EventTitle']; ?></h2>
                <p class="eventLocation">
                <svg viewBox="0 0 24 24" width="36" height="36" aria-hidden="true">
                    <path fill="currentColor" d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/>
                </svg>
                At <?php echo htmlspecialchars($event['EventLocation']); ?>
            </p>
            <p>Description: <?php echo $event['EventDescription']; ?></p>
            <p>Category: <?php echo $event['EventCategory'] . $categoryString; ?></p>
            <p>Date and time: <?php echo $event['EventDateTime']; ?></p><br>
            
            <?php
            if (isAlreadyAttending()) {
                ?> <p style="color: red;">You are already attending this event</p> <?php
            }                
            ?>
            <a href="handleEvents.php?action=SignUp&event=<?php echo $_GET['event']; ?>" style="width: 100%" class="orangeButton">sign up</a>
            <a href="<?php echo $closeUrl; ?>" style="width: 100%;" class="orangeButton">Close</a>
        </div>
    </div>
    <?php
}

function isAlreadyAttending() {
    $attendees = getAttendees();
    $alreadyAttending = False;
    foreach ($attendees as $attendee) {
        if ($_SESSION['user_id'] == $attendee['UserId']) {
            $alreadyAttending = True;
        }
    }

    return $alreadyAttending;
}
?>