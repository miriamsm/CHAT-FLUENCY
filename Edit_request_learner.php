<?php
include 'connect.php'; // Include your database connection file

// Initialize variables to hold form data
$language = $level = $preferred_schedule = $session_duration = $requestID = '';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $language = $_POST['language'];
    $level = $_POST['level'];
    $preferred_schedule = $_POST['preferred_schedule'];
    $session_duration = $_POST['session_duration'];
    $requestID = $_POST['request_id'];

    // Check if any required field is empty
    if (empty($language) || empty($level) || empty($preferred_schedule) || empty($session_duration)) {
        echo "<script>alert('Please fill all fields.');</script>";
    } else {
        // Database connection details (moved outside the try block for better organization)
        $db_name = 'mysql:host=localhost;dbname=chatfluency';
        $user_name = 'root';
        $user_password = '';

        try {
            // Create a PDO connection
            $conn = new PDO($db_name, $user_name, $user_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare and execute the query to fetch existing data for comparison
            $stmt = $conn->prepare("SELECT LanguageToLearn, ProficiencyLevel, PreferredSchedule, SessionDuration FROM LearningRequests WHERE RequestID = ?");
            $stmt->execute([$requestID]);
            $existingData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if the submitted data is different from the existing data
            if ($existingData['LanguageToLearn'] != $language || $existingData['ProficiencyLevel'] != $level || $existingData['PreferredSchedule'] != $preferred_schedule || $existingData['SessionDuration'] != $session_duration) {
                // Update the request information in the database
                $stmt = $conn->prepare("UPDATE LearningRequests SET LanguageToLearn = ?, ProficiencyLevel = ?, PreferredSchedule = ?, SessionDuration = ? WHERE RequestID = ?");
                $stmt->execute([$language, $level, $preferred_schedule, $session_duration, $requestID]);

                // Check if the update was successful
                $updatedRows = $stmt->rowCount();
                if ($updatedRows > 0) {
                    // Redirect to the view page after successful update with success parameter
                    header("Location: view_request_learner.php?request_id=$requestID&success=true");
                    exit(); // Stop further execution
                } else {
                    echo "<script>alert('Failed to update information. Please try again.');</script>";
                }
            } else {
                // Data is the same, redirect without attempting update
                header("Location: view_request_learner.php?request_id=$requestID");
                exit(); // Stop further execution
            }
        } catch (PDOException $e) {
            // Handle PDO exceptions
            echo "Connection failed: " . $e->getMessage();
        }

        // Close the PDO connection
        $conn = null;
    }
}

// Check if the request ID is set in the URL
if (isset($_GET['request_id'])) {
    // Get the request ID from the URL
    $requestID = $_GET['request_id'];

    // Database connection details (moved outside the try block for better organization)
    $db_name = 'mysql:host=localhost;dbname=chatfluency';
    $user_name = 'root';
    $user_password = '';

    try {
        // Create a PDO connection
        $conn = new PDO($db_name, $user_name, $user_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the query to fetch specific details of the request
        $stmt = $conn->prepare("SELECT LanguageToLearn, ProficiencyLevel, PreferredSchedule, SessionDuration FROM LearningRequests WHERE RequestID = ?");
        $stmt->execute([$requestID]);
        $requestDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the request details were found
        if ($requestDetails) {
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
    </style>
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
            <a href="SesssionsLearner.html"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span</a>
            <a href="partners.html"><i class="fas fa-chalkboard-user"></i><span>partners</span></a>
            <a href="about_learner.html"><i class="fas fa-question"></i><span>about</span></a>
        </nav>
        <nav>
            <div style="text-align: center; margin-top: 20px; margin-bottom: 150px;">
                <a href="home.html" class="inline-btn">Sign out</a>
            </div>
        </nav>
    </div>
    <script>
    function confirmSave() {
        // Check if any required field is empty
        if (document.getElementById('language').value.trim() === '' || 
            document.getElementById('level').value.trim() === '' || 
            document.getElementById('preferred_schedule').value.trim() === '' || 
            document.getElementById('session_duration').value.trim() === '') {
            alert('Please fill all fields.');
            return false;
        }
        return confirm("Are you sure you want to save changes?");
    }

    // Redirect after successful form submission
    function redirectAfterSave() {
        window.location.href = 'view_request_learner.php?request_id=<?php echo $requestID; ?>';
    }
</script>

    <section class="form-container">
        <form id="editForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return confirmSave()">
            <h3>Edit your request information</h3>
            <p>Language <span>*</span></p>
            <select id="language" name="language" class="box">
                <option value="" disabled selected>Specify the language you want to learn</option>
                <option value="English" <?php if ($requestDetails['LanguageToLearn'] == 'English') echo 'selected'; ?>>English</option>
                <option value="Spanish" <?php if ($requestDetails['LanguageToLearn'] == 'Spanish') echo 'selected'; ?>>Spanish</option>
                  <option value="French" <?php if ($requestDetails['LanguageToLearn'] == 'French') echo 'selected'; ?>>French</option>
                  <option value="Mandarin Chinese" <?php if ($requestDetails['LanguageToLearn'] == 'Mandarin Chinese') echo 'selected'; ?>>Mandarin Chinese</option>
                  <option value="Arabic" <?php if ($requestDetails['LanguageToLearn'] == 'Arabic') echo 'selected'; ?>>Arabic</option>
                  <option value="Hindi" <?php if ($requestDetails['LanguageToLearn'] == 'Hindi') echo 'selected'; ?>>Hindi</option>
                  <option value="Russian" <?php if ($requestDetails['LanguageToLearn'] == 'Russian') echo 'selected'; ?>>Russian</option>
                  <option value="Portuguese" <?php if ($requestDetails['LanguageToLearn'] == 'Portuguese') echo 'selected'; ?>>Portuguese</option>
                  <option value="Bengali" <?php if ($requestDetails['LanguageToLearn'] == 'Bengali') echo 'selected'; ?>>Bengali</option>
                  <option value="German" <?php if ($requestDetails['LanguageToLearn'] == 'German') echo 'selected'; ?>>German</option>
            </select>
            <p>Your level <span>*</span></p>
            <select id="level" name="level" class="box">
                <option value="" disabled selected>Specify your current proficiency level</option>
                <option value="Beginner" <?php if ($requestDetails['ProficiencyLevel'] == 'Beginner') echo 'selected'; ?>>Beginner</option>
                <option value="Intermediate" <?php if ($requestDetails['ProficiencyLevel'] == 'Intermediate') echo 'selected'; ?>>Intermediate</option>
                <option value="Advanced" <?php if ($requestDetails['ProficiencyLevel'] == 'Advanced') echo 'selected'; ?>>Advanced</option>

                <!-- Add other options similarly -->
            </select>
            <p>Enter your preferred schedule <span>*</span></p>
            <input type="text" id="preferred_schedule" name="preferred_schedule" placeholder="E.g. weekdays evenings, weekends mornings" maxlength="20" class="box" value="<?php echo $requestDetails['PreferredSchedule']; ?>">
            <p>Enter your session duration <span>*</span></p>
            <input type="text" id="session_duration" name="session_duration" placeholder="E.g. 1 hour, 90 minutes" maxlength="20" class="box" value="<?php echo $requestDetails['SessionDuration']; ?>">
            <!-- Include other form fields -->
            <input type="hidden" name="request_id" value="<?php echo $requestID; ?>">   
               <!-- Hidden field to pass the request ID for updating -->
            
            <input type="submit" value="Save changes" name="submit" class="btn">
        </form>
    </section>

    <footer class="footer">
        &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
        <a href="contact.html"><i class="fas fa-headset"></i><span> contact us</span></a>
    </footer>
    <script src="script.js"></script>
    <script>
        // Alert message if information updated successfully
        let urlParams = new URLSearchParams(window.location.search);
        let success = urlParams.get('success');
        if (success === 'true') {
            alert('Information updated successfully!');
        }
    </script>
</body>
</html>

<?php
        } else {
            echo "<p>No details found for this request ID.</p>";
        }
    } catch (PDOException $e) {
        // Handle PDO exceptions
        echo "Connection failed: " . $e->getMessage();
    }

    // Close the PDO connection
    $conn = null;
} else {
    // Request ID not provided in the URL
    echo "<p>Error: Request ID not specified.</p>";
}
?>
