<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';
$connection = new connect();

// Check if user is logged in
if (!isset($_COOKIE['user_id'])) {
    header('location: login.php');
    exit(); // Added exit() after header redirect
}

$user_id = $_COOKIE['user_id'];

$select_user = $connection->conn->prepare("SELECT * FROM languagelearners WHERE LearnerID = ? LIMIT 1");
$select_user->bind_param("i", $user_id);
$select_user->execute();
$fetch_user = $select_user->get_result()->fetch_assoc();

// Fetch partner's languages from the database
$sqlPartnerLanguages = "SELECT Languages FROM languagepartners WHERE PartnerID = ?";
$stmtPartnerLanguages = $connection->conn->prepare($sqlPartnerLanguages);
$stmtPartnerLanguages->bind_param("i", $partnerID);
$partnerID = $_GET['partnerID']; // Assuming you have already fetched $partnerID from URL parameters
$stmtPartnerLanguages->execute();
$partnerLanguagesResult = $stmtPartnerLanguages->get_result();
$partnerLanguagesRow = $partnerLanguagesResult->fetch_assoc();
$partnerLanguages = explode(', ', $partnerLanguagesRow['Languages']);
$stmtPartnerLanguages->close();

// Retrieve partnerID from URL parameters
if (!isset($_GET['partnerID'])) {
    echo '<script>alert("Error: PartnerID not specified."); window.location.href = "partners.php";</script>';
    exit();
}

$partnerID = $_GET['partnerID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $scheduleInput = trim($_POST['pass']);
    // Validate schedule format using regex
    if (!preg_match("/^(1[0-2]|0?[1-9])(:[0-5][0-9])?( ?[ap]m)?$/i", $scheduleInput)) {
        echo '<script>alert("Error: Invalid schedule format. Please use format like 10am, 9pm, etc.");</script>';
        exit(); // Added exit() to stop further execution
    }

    // Validate Session Duration Input
    $sessionDuration = trim($_POST['session_duration']);
    // Validate session duration format using regex
    if (!preg_match("/^\d+ (hour|hours)$/i", $sessionDuration)) {
        echo '<script>alert("Error: Invalid session duration format. Please use format like 1 hour, 4 hours, etc.");</script>';
        exit();
    }

    // Sanitize input data
    $languageToLearn = $_POST['language']; // No trimming or validation for language input
    $proficiencyLevel = trim($_POST['level']);
    $preferredSchedule = $scheduleInput; // Use the validated schedule input
    $learnerGoals = trim($_POST['learner_goals']); // Updated name attribute

    // Check if the requested schedule conflicts with the partner's existing session
    $sqlCheckConflict = "SELECT COUNT(*) FROM learningrequests WHERE PartnerID = ? AND PreferredSchedule = ?";
    $stmtCheckConflict = $connection->conn->prepare($sqlCheckConflict);
    $stmtCheckConflict->bind_param("is", $partnerID, $preferredSchedule);
    $stmtCheckConflict->execute();
    $stmtCheckConflict->bind_result($conflictCount);
    $stmtCheckConflict->fetch();
    $stmtCheckConflict->close();

    if ($conflictCount > 0) {
        // There is a conflict, display an error message
        echo '<script>alert("Error: The requested schedule conflicts with the partner\'s existing session. Please choose a different schedule.");</script>';
    } else {
        // No conflict, proceed with the insertion/update
        // Check if the LearnerID exists in the languagelearners table
        $sqlCheckLearner = "SELECT COUNT(*) FROM languagelearners WHERE LearnerID = ?";
        $stmtCheckLearner = $connection->conn->prepare($sqlCheckLearner);
        $stmtCheckLearner->bind_param("i", $user_id);
        $stmtCheckLearner->execute();
        $stmtCheckLearner->bind_result($learnerCount);
        $stmtCheckLearner->fetch();
        $stmtCheckLearner->close();

        if ($learnerCount > 0) {
            // LearnerID exists, proceed with the insertion/update
            $sqlInsert = "INSERT INTO learningrequests(LearnerID, PartnerID, LanguageToLearn, ProficiencyLevel, PreferredSchedule, SessionDuration, RequestDate, Status, learnerGoals) 
                VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, 'Pending', ?)";
            $stmtInsert = $connection->conn->prepare($sqlInsert);
            $stmtInsert->bind_param("iisssss", $user_id, $partnerID, $languageToLearn, $proficiencyLevel, $preferredSchedule, $sessionDuration, $learnerGoals);

            if ($stmtInsert->execute()) {
                // If insertion is successful, display success message using JavaScript
                echo '<script>
                    if (confirm("Thank you! The request has been sent successfully. Do you want to see your list of requests?")) {
                        window.location.href = "list_of_requests_learner.php";
                    }
                </script>';
                exit(); // Added exit() to stop further execution
            } else {
                // If insertion fails, display an error message
                echo '<script>alert("Error: Unable to submit your request. Please try again later.");</script>';
            }

            $stmtInsert->close(); // Close the prepared statement for insertion
        } else {
            // LearnerID does not exist, display an error or handle accordingly
            echo '<script>alert("Error: Invalid LearnerID. Please choose a valid LearnerID.");</script>';
        }
    }
}

// Close the database connection
$connection->conn->close();
?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8"> <!-- Added meta charset -->

    <title>Post a request</title>

    

    <!-- font awesome cdn link  -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">


    <!-- custom css file link  -->

    <link rel="stylesheet" href="style.css">


    <script>

        // Function to validate the form

        function validateForm() {

            var language = document.getElementById('language').value;

            var level = document.getElementById('level').value;

            var schedule = document.getElementsByName('pass')[0].value;

            var sessionDuration = document.getElementsByName('session_duration')[0].value; // Updated name without spaces

            var learnerGoals = document.getElementsByName('learner_goals')[0].value; // Get learner goals value

        

        // Check if any field is empty

        if (language === "" || level === "" || schedule === "" || sessionDuration === "" || learnerGoals === "") {

            alert("Please fill in all fields.");

            return false;

        }

        // Check if the selected language is one of the partner's languages
    var partnerLanguages = <?php echo json_encode($partnerLanguages); ?>;
    if (!partnerLanguages.includes(language)) {
        alert("Invalid language. Please choose another language.");
        return false;
    }

            // Additional validation logic can be added here if needed

        

            // Form is valid, allow submission

            return true;

        }

       

    </script>

</head>

<body>

    

<header class="header">

   

   <div class="flex">


      <a href="profileLearner.php" class="logo"> <img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 

      <?php

session_start(); // Start the session


// Check if the session variable is set and not empty

if (isset($_SESSION['redirect_message']) && !empty($_SESSION['redirect_message'])) {

 $redirect_message = $_SESSION['redirect_message'];


 // Echo or display the message where needed in your HTML

 echo '<script>alert("' . $redirect_message . '");</script>';


 // Clear the session variable

 unset($_SESSION['redirect_message']);

}

?>

      <div class="icons">

         <div id="toggle-btn" class="fas fa-sun"></div>

      </div>



   </div>


</header>   


    <div class="side-bar">


<div id="close-btn">

   <i class="fas fa-times"></i>

</div>


<div class="profile">

<img src="images/<?= $fetch_user['Photo']; ?>" class="image" alt="">

   <h3 class="name"><?= $fetch_user['FirstName'] . ' ' . $fetch_user['LastName']; ?></h3>

   <p class="role">Learner</p>

</div>


<nav class="navbar">

<a href="profileLearner.php"><i class="fas fa-home"></i><span>home</span></a>

   <a href="SesssionsLearner.php"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span></a>

   <a href="partners.php"><i class="fas fa-chalkboard-user"></i><span>partners</span></a>

   <a href="about_learner.php"><i class="fas fa-question"></i><span>about</span></a>

</nav>

<nav>

   <div style="text-align: center; margin-top: 20px; margin-bottom: 150px;">

   <a href="user_logout.php"  class="inline-btn" >Sign out</a>

</div>

</nav>


</div>


    <section class="form-container">

        <form id="postRequestForm" action="" method="post" onsubmit="return validateForm()">  

            <h3>Enter your request information</h3>

            <p> language <span>*</span></p>

            <select id="language" name="language" class="box">

                <option value="" disabled selected>Specify the language you want to learn</option>

                <option value="English">English</option>

                <option value="Spanish">Spanish</option>

                <option value="French">French</option>

                <option value="Mandarin Chinese">Mandarin Chinese</option>

                <option value="Arabic">Arabic</option>

                <option value="Hindi">Hindi</option>

                <option value="Russian">Russian</option>

                <option value="Portuguese">Portuguese</option>

                <option value="Bengali">Bengali</option>

                <option value="German">German</option>

            </select>       

            <p> your level <span>*</span></p>

            <select id="level" name="level" class="box">

                <option value="" disabled selected>Specify your current proficiency level</option>

                <option value="Beginner">Beginner</option>

                <option value="Intermediate">Intermediate</option>

                <option value="Advanced">Advanced</option>

            </select>

            <p>enter your preferred schedule <span>*</span></p>

            <input type="datetime-local" name="pass" class="box">

            

            <p>enter your session duration <span>*</span></p>

            <input type="text" name="session_duration" placeholder="E.g. 1 hour , 3 hours " maxlength="20" class="box">

            <p>Enter your learner goals <span>*</span></p>

            <textarea name="learner_goals" placeholder="Describe your goals..." maxlength="200" col rows="4" class="box"></textarea>

    

            <input type="submit" value="Send" name="submit" class="btn" >

        </form>

    </section>


    <footer class="footer">

        &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!

        <a href="contact.html"><i class="fas fa-headset"></i><span> contact us</span></a>

    </footer>

    <script src="script.js"></script>

</body>

</html>