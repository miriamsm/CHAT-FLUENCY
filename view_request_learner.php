<?php

error_reporting(E_ALL);

ini_set('display_errors', 1);


include 'connect.php';

$connection = new Connect();

if (isset($_COOKIE['user_id'])) {

    $user_id = $_COOKIE['user_id'];

} else {

    $user_id = '';

    header('location:login.php');

}


$select_user = $connection->conn->prepare("SELECT * FROM languagelearners WHERE LearnerID = ? LIMIT 1"); 

$select_user->bind_param("i", $user_id);

$select_user->execute();

$fetch_user = $select_user->get_result()->fetch_assoc();


$user_id = $_COOKIE['user_id'];


?>

<!DOCTYPE html>

<html lang="en">

<head>

   <meta charset="UTF-8">

   <!-- font awesome cdn link  -->

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">


   <!-- custom css file link  -->

   <link rel="stylesheet" href="style.css">

   <title>view request</title>

   <style>

      .footer

      {

      margin-top : 50px;

      }

      p.learner-goals {

   max-height: 200px; /* Set a maximum height */

   overflow: auto; /* Enable scrolling */

   white-space: nowrap; /* Prevent text wrapping */


}

      </style>


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

   <a href="sessionsLearner.php"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span></a>

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

    <form method="post">

    <?php


    try {

        // Check if the request ID is set in the URL

        if (isset($_GET['request_id'])) {

            $requestID = $_GET['request_id'];


            // Prepare and execute the query to fetch specific details of the request

            $stmt = $connection->conn->prepare("SELECT LanguageToLearn, ProficiencyLevel, PreferredSchedule, SessionDuration, RequestDate, Status, learnerGoals FROM LearningRequests WHERE RequestID = ?");

            $stmt->bind_param("s", $requestID); // Bind parameter

            $stmt->execute();

            $result = $stmt->get_result();


            // Check if the request details were found

            if ($result->num_rows > 0) {

                // Fetch the result as an associative array

                $requestDetails = $result->fetch_assoc();


                // Display the request details

                echo "<h3>your request</h3>";

                echo "<p><br><br>Language to learn: " . htmlspecialchars($requestDetails['LanguageToLearn']) . "<br></p>";

                echo "<p><br>Proficiency Level: " . htmlspecialchars($requestDetails['ProficiencyLevel']) . "<br></p>";

                echo "<p><br>Preferred Schedule: " . htmlspecialchars($requestDetails['PreferredSchedule']) . "<br></p>";

                echo "<p><br>Session Duration: " . htmlspecialchars($requestDetails['SessionDuration']) . "<br></p>";

                echo "<p><br>Request Date: " . htmlspecialchars($requestDetails['RequestDate']) . "<br> </p>";

                echo "<p><br>Status: " . htmlspecialchars($requestDetails['Status']) . " <br></p>";

                // Add learnerGoals to the display

// Display the "Learner Goals" section

echo "<p class='learner-goals'><br>Learner Goals: " . htmlspecialchars($requestDetails['learnerGoals']) . "<br> <br></p>";


                // Display the "Cancel request" button only if the status is "Pending"

                if ($requestDetails['Status'] == 'Pending') {

                    echo '<div style="text-align: center; margin-top: 20px;">';

                    echo '<form method="post">';

                    echo '<a href="Edit_request_learner.php?request_id=' . $requestID . '" class="inline-btn">Edit request</a>';

                    echo '&nbsp;&nbsp;';

                    echo '<input type="submit" name="cancel_request" value="Cancel request" class="inline-delete-btn">';


                    echo '</form>';

                    echo '</div>';

                }

            } else {

                echo "<p>No details found for this request ID.</p>";

            }

        } else {

            // Request ID not provided in the URL

            echo "<p>Error: Request ID not specified.</p>";

        }

    } catch (Exception $e) {

        // Handle exceptions

        echo "Error: " . $e->getMessage();

    }


    // Handle the form submission to cancel the request

    if (isset($_POST['cancel_request'])) {

        if ($requestDetails['Status'] == 'Pending') {

            try {

                // Prepare and execute the query to delete the request

                $stmt = $connection->conn->prepare("DELETE FROM LearningRequests WHERE RequestID = ?");

                $stmt->bind_param("s", $requestID); // Bind parameter

                $stmt->execute();


                echo "<script>alert('Request successfully canceled.'); window.location.href = 'list_of_requests_learner.php';</script>";

            } catch (Exception $e) {

                // Handle exceptions

                echo "Cancel request failed: " . $e->getMessage();

            }

        } else {

            echo "<p>Error: You cannot cancel this request as its status is not 'Pending'.</p>";

        }

    }


    ?>

</section>

</form>


<footer class="footer">

    &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!

    <a href="contact.html"><i class="fas fa-headset"></i><span> contact us</span></a>

</footer>

<script src="script.js"></script>

</body>


</html>