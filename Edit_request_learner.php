<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Include the database connection file
include 'connect.php'; // Update the path as necessary

// Create a new instance of the Connect class to establish the database connection
$connection = new Connect();
$conn = $connection->conn;

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start(); // Start the session if not already started

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    // Redirect to login.php if user_id cookie is not set
    header('location: login.php');
    // Add an exit after header redirection
    exit();
}

$select_user = $conn->prepare("SELECT * FROM languagelearners WHERE LearnerID = ? LIMIT 1");
$select_user->bind_param("i", $user_id);
$select_user->execute();
$fetch_user = $select_user->get_result()->fetch_assoc();
$learnerInfo = array(
    'LanguageToLearn' => '',
    'ProficiencyLevel' => '',
    'PreferredSchedule' => '',
    'SessionDuration' => '',
    'LearnerGoals' => ''
);
if (!is_null($learnerInfo) && isset($learnerInfo['LanguageToLearn'])) {
    // Access $learnerInfo['LanguageToLearn'] and other elements safely
}

$user_id = $_COOKIE['user_id'];

// Fetch the learner's information from the database
try {

    if (isset($_GET['request_id'])) {
        $requestID = $_GET['request_id'];
    $stmt = $conn->prepare("SELECT * FROM learningrequests WHERE RequestID = ?");
    $stmt->bind_param("i", $requestID);
    $stmt->execute();
    $result = $stmt->get_result();

     // Check if the request information is found
     if ($result->num_rows > 0) {
        // Fetch the request details as an associative array
        $requestDetails = $result->fetch_assoc();
    } else {
        echo "Error: Request information not found.";
        exit();
    }
} else {
    // Request ID not provided in the URL
    echo "Error: Request ID not specified.";
    exit();
} 
} catch (Exception $e) {
    // Handle exceptions
    echo "Error: " . $e->getMessage();
    exit();
}

    

// Check if the form is submitted
$successMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Validate and sanitize input data
    $languageToLearn = trim($_POST['language']);
    $proficiencyLevel = trim($_POST['level']);
    $preferredSchedule = trim($_POST['preferred_schedule']);
    $sessionDuration = trim($_POST['session_duration']);
    $learnerGoals = trim($_POST['learner_goals']);
    $requestID = $_POST['request_id']; // Added request ID

   

    // Validate session duration format using regex
    if (!preg_match("/^\d+ (hour|hours)$/i", $sessionDuration)) {
        echo '<script>alert("Error: Invalid session duration format. Please use format like 1 hour, 4 hours, etc.");</script>';
       
    }

    // Check if the requested schedule conflicts with the partner's existing session
    $sqlCheckConflict = "SELECT COUNT(*) FROM learningrequests WHERE PartnerID = ? AND PreferredSchedule = ?";
    $stmtCheckConflict = $conn->prepare($sqlCheckConflict);
    $stmtCheckConflict->bind_param("is", $partnerID, $preferredSchedule);
    $stmtCheckConflict->execute();
    $stmtCheckConflict->bind_result($conflictCount);
    $stmtCheckConflict->fetch();
    $stmtCheckConflict->close();

    // If there's a conflict, show an error message
    if ($conflictCount > 0) {
        echo '<script>alert("Error: The requested schedule conflicts with the partner\'s existing session.");</script>';
        // Stop further processing
    }

    try {

          if(isset($_Get['RequestID'])){
        $requestID=$_Get['RequestID'];}
        
        // Update the learner's information in the database
        $stmt = $conn->prepare("UPDATE learningrequests SET LanguageToLearn = ?, ProficiencyLevel = ?, PreferredSchedule = ?, SessionDuration = ?, LearnerGoals = ? WHERE RequestID = ?" );
        $stmt->bind_param("sssssi", $languageToLearn, $proficiencyLevel, $preferredSchedule, $sessionDuration, $learnerGoals, $requestID);
        $stmt->execute();

        // Check if the update was successful
        $updatedRows = $stmt->affected_rows;
        if ($updatedRows > 0) {
            // Set success message
            $successMessage = "Information updated successfully!";
            // Refresh the learnerInfo variable with updated data
            $learnerInfo['LanguageToLearn'] = $languageToLearn;
            $learnerInfo['ProficiencyLevel'] = $proficiencyLevel;
            $learnerInfo['PreferredSchedule'] = $preferredSchedule;
            $learnerInfo['SessionDuration'] = $sessionDuration;
            $learnerInfo['LearnerGoals'] = $learnerGoals;
        }
    } catch (Exception $e) {
        // Handle exceptions
        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit request</title>
    
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="style.css">
    <style>
        .footer {
            margin-top: 50px;
        } 
        /* .box {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        } */

    </style>
</head>
<body>
<header class="header">
   
   <div class="flex">

      <a href="profileLearner.php" class="logo"> <img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 
      <?php
// session_start(); // Start the session

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
         <a href="sesssionsLearner.php"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span></a>
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
    <form id="editForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?request_id=<?php echo $requestID; ?>" onsubmit="return confirmSave()">
            <h3>Edit your request information</h3>
            <p>Language <span>*</span></p>
            <select id="language" name="language" class="box">
                <option value="" disabled selected>Specify the language you want to learn</option>
                <option value="English" <?php if ($learnerInfo['LanguageToLearn'] == 'English') echo 'selected'; ?>>English</option>
                <option value="Spanish" <?php if ($learnerInfo['LanguageToLearn'] == 'Spanish') echo 'selected'; ?>>Spanish</option>
                <option value="French" <?php if ($learnerInfo['LanguageToLearn'] == 'French') echo 'selected'; ?>>French</option>
                <option value="Mandarin Chinese" <?php if ($learnerInfo['LanguageToLearn'] == 'Mandarin Chinese') echo 'selected'; ?>>Mandarin Chinese</option>
                <option value="Arabic" <?php if ($learnerInfo['LanguageToLearn'] == 'Arabic') echo 'selected'; ?>>Arabic</option>
                <option value="Hindi" <?php if ($learnerInfo['LanguageToLearn'] == 'Hindi') echo 'selected'; ?>>Hindi</option>
                <option value="Russian" <?php if ($learnerInfo['LanguageToLearn'] == 'Russian') echo 'selected'; ?>>Russian</option>
                <option value="Portuguese" <?php if ($learnerInfo['LanguageToLearn'] == 'Portuguese') echo 'selected'; ?>>Portuguese</option>
                <option value="Bengali" <?php if ($learnerInfo['LanguageToLearn'] == 'Bengali') echo 'selected'; ?>>Bengali</option>
                <option value="German" <?php if ($learnerInfo['LanguageToLearn'] == 'German') echo 'selected'; ?>>German</option>
            </select>
                <!-- Add other language options and handle selected attribute similarly -->
            </select>
            <p>Your level <span>*</span></p>
            <select id="level" name="level" class="box">
                <option value="" disabled selected>Specify your current proficiency level</option>
                <option value="Beginner" <?php if ($learnerInfo['ProficiencyLevel'] == 'Beginner') echo 'selected'; ?>>Beginner</option>
                <option value="Intermediate" <?php if ($learnerInfo['ProficiencyLevel'] == 'Intermediate') echo 'selected'; ?>>Intermediate</option>
                <option value="Advanced" <?php if ($learnerInfo['ProficiencyLevel'] == 'Advanced') echo 'selected'; ?>>Advanced</option>
            </select>
                <!-- Add other proficiency level options and handle selected attribute similarly -->
            </select>
            <p>Enter your preferred schedule <span>*</span></p>
            <input type="datetime-local" name="preferred_schedule" class="box">
            <p>Enter your session duration <span>*</span></p>
            <input type="text" id="session_duration" name="session_duration" placeholder="E.g. 1 hour, 3 hours " maxlength="20" class="box" value="<?php echo $learnerInfo['SessionDuration']; ?>">
            <p>Learner Goals:</p>
            <textarea id="learner_goals" name="learner_goals" maxlength="200" cols="4" rows="4" class="box"><?php echo $learnerInfo['LearnerGoals']; ?></textarea>
            <input type="hidden" name="request_id" value="<?php echo $requestID; ?>">
            <input type="submit" value="Save changes" name="submit" class="btn">
        </form>
    </section>

    <footer class="footer">
        <!-- Footer content -->
    </footer>
    <script src="script.js"></script>
    <script>
        // Alert message if information updated successfully
        let urlParams = new URLSearchParams(window.location.search);
        let success = urlParams.get('success');
        if (success === 'true') {
            alert('Information updated successfully!');
        }

        // Function to confirm save
        function confirmSave() {
            // Check if any required field is empty
            if (document.getElementById('language').value.trim() === '' || 
                document.getElementById('level').value.trim() === '' || 
                document.getElementById('preferred_schedule').value.trim() === '' || 
                document.getElementById('session_duration').value.trim() === '' || 
                document.getElementById('learner_goals').value.trim() === '') {
                alert('Please fill all fields.');
                return false;
            }
            if (confirm("Are you sure you want to save changes?")) {
                // Redirect to view_request_learner.php
                return true; 
                
            } else {
                return false; // Prevent form submission
            }
    
        }
    </script>
</body>
</html>