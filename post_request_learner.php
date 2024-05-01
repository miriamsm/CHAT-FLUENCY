<?php
include 'connect.php'; // Include your database connection file

// Database connection details

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $languageToLearn = trim($_POST['language']);
    $proficiencyLevel = trim($_POST['level']);
    $preferredSchedule = trim($_POST['pass']);
    $sessionDuration = trim($_POST['session_duration']); // Corrected name attribute
    
    // Validate session duration to ensure it's a positive integer
  

    // Assuming you have a way to get the LearnerID, replace 1 with the actual LearnerID value
    $learnerID = 1;

    // Insert the request into the database
    $sqlInsert = "INSERT INTO LearningRequests (LearnerID, LanguageToLearn, ProficiencyLevel, PreferredSchedule, SessionDuration, RequestDate, Status) 
                  VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, 'Pending')";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bindParam(1, $learnerID);
    $stmtInsert->bindParam(2, $languageToLearn);
    $stmtInsert->bindParam(3, $proficiencyLevel);
    $stmtInsert->bindParam(4, $preferredSchedule);
    $stmtInsert->bindParam(5, $sessionDuration);
    
    if ($stmtInsert->execute()) {
        // If insertion is successful, display success message and ask user if they want to see their list of requests
        echo '<script>
                if (confirm("Thank you! The request has been sent successfully. Do you want to see your list of requests?")) {
                    window.location.href = "list_of_requests_learner.php";
                } else {
                    document.getElementById("postRequestForm").reset(); // Reset the form
                }
              </script>';
    } else {
        // If insertion fails, display an error message
        echo '<script>alert("Error: Unable to submit your request. Please try again later.");</script>';
    }
   
    $stmtInsert->closeCursor(); // Close the prepared statement for insertion
}

// Close the database connection
$conn = null;
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
        
            // Check if any field is empty
            if (language === "" || level === "" || schedule === "" || sessionDuration === "") {
                alert("Please fill in all fields.");
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
            <a href="profileLearner.html" class="logo"> <img src="images/logo.jpg" width="210" height="60" alt="logo"></a> 
            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <div id="toggle-btn" class="fas fa-sun"></div>
            </div>
        </div>
    </header>  

    <div class="side-bar">
        <div id="close-btn">
            <i class="fas fa-times"></i>
        </div>
        <div class="profile">
            <img src="images/pic-1.jpg" class="image" alt="">
            <h3 class="name">Leena Alshaikh</h3>
            <p class="role">Learner</p>
        </div>
        <nav class="navbar">
            <a href="profileLearner.html"><i class="fas fa-home"></i><span>home</span></a>
            <a href="SesssionsLearner.html"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span></a>
            <a href="partners.html"><i class="fas fa-chalkboard-user"></i><span>partners</span></a>
            <a href="about_learner.html"><i class="fas fa-question"></i><span>about</span></a>
        </nav>
        <nav>
            <div style="text-align: center; margin-top: 20px; margin-bottom: 150px;">
                <a href="home.html" class="inline-btn">Sign out</a>
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
            <input type="text" name="pass" placeholder="E.g. weekdays evenings , weekends mornings " maxlength="20" class="box">
            
            <p>enter your session duration <span>*</span></p>
            <input type="text" name="session_duration" placeholder="E.g. 1 hour , 90 minutes " maxlength="20" class="box">
        
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
