<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
   <title>Edit request</title>
   <style>
      .footer
      {
      margin-top : 50px;
      }
      </style>

</head>
<body>
   
    
   <header class="header">
   
      <div class="flex">
   
         <a href="profileLearner.html" class="logo"> <img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 
   
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
         <a href="home.html"  class="inline-btn" >Sign out</a>
      </div>
      </nav>
   
   </div>
   <section class="form-container">
    <form method="post">
    <?php
// Check if the request ID is set in the URL
if (isset($_GET['request_id'])) {
    // Get the request ID from the URL
    $requestID = $_GET['request_id'];

    // Database connection details
    $db_name = 'mysql:host=localhost;dbname=chatfluency';
    $user_name = 'root';
    $user_password = '';

    try {
        // Create a PDO connection
        $conn = new PDO($db_name, $user_name, $user_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the query to fetch specific details of the request
        $stmt = $conn->prepare("SELECT LanguageToLearn, ProficiencyLevel, PreferredSchedule, SessionDuration, RequestDate, Status FROM LearningRequests WHERE RequestID = ?");
        $stmt->execute([$requestID]);
        $requestDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the request details were found
        if ($requestDetails) {
            // Display the request details
            echo "<h3>your request</h3>";
            echo "<p><br><br>Language to learn :    {$requestDetails['LanguageToLearn']}<br></p>";
            echo "<p><br> Proficiency Level : {$requestDetails['ProficiencyLevel']}<br></p>";
            echo "<p><br> Preferred Schedule : {$requestDetails['PreferredSchedule']}<br></p>";
            echo "<p><br> Session Duration : {$requestDetails['SessionDuration']} <br></p>";
            echo "<p><br> Request Date : {$requestDetails['RequestDate']}<br> </p>";
            echo "<p><br> Status : {$requestDetails['Status']}<br> <br></p>";

            // Display the "Cancel request" button only if the status is "Pending"
            if ($requestDetails['Status'] == 'Pending') {
                echo '<div style="text-align: center; margin-top: 20px;">';
                echo '<form method="post">';
                echo '<a href="Edit_request_learner.php?request_id=' . $requestID . '" class="inline-btn">Edit request</a>';
               echo '&nbsp;&nbsp;';
               echo '<input type="submit" name="cancel_request" value="Cancel request" class="inline-delete-btn" onclick="return confirmDelete()">';

                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "<p>No details found for this request ID.</p>";
        }
    } catch (PDOException $e) {
        // Handle PDO exceptions
        echo "Connection failed: " . $e->getMessage();
    }

    // Close the PDO connection
    $conn = null;

    // Handle the form submission to cancel the request
    if (isset($_POST['cancel_request'])) {
        if ($requestDetails['Status'] == 'Pending') {
            try {
                // Create a PDO connection
                $conn = new PDO($db_name, $user_name, $user_password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Prepare and execute the query to delete the request
                $stmt = $conn->prepare("DELETE FROM LearningRequests WHERE RequestID = ?");
                $stmt->execute([$requestID]);

                echo "<script>alert('Request successfully canceled.'); window.location.href = 'list_of_requests_learner.php';</script>";
            } catch (PDOException $e) {
                // Handle PDO exceptions
                echo "Cancel request failed: " . $e->getMessage();
            }

            // Close the PDO connection
            $conn = null;
        } else {
            echo "<p>Error: You cannot cancel this request as its status is not 'Pending'.</p>";
        }
    }
} else {
    // Request ID not provided in the URL
    echo "<p>Error: Request ID not specified.</p>";
}
?>

<script>
function confirmDelete() {
    return confirm("Do you really want to delete this request?");
}
</script>
</section>
</form>

<footer class="footer">
    &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
    <a href="contact.html"><i class="fas fa-headset"></i><span> contact us</span></a>
</footer>
<script src="script.js"></script>
</body>

</html>
