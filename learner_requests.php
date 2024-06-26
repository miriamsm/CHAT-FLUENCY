<?php
include 'connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

// Create an instance of the Connect class
$connection = new Connect();

// Fetch learner requests from the database along with learner names
$sql = "SELECT lr.*, ll.FirstName AS LearnerFirstName, ll.LastName AS LearnerLastName, ll.Photo 
        FROM LearningRequests lr
        INNER JOIN LanguageLearners ll ON lr.LearnerID = ll.LearnerID
        WHERE lr.PartnerID = $user_id";
$stmt = $connection->conn->query($sql); // Use the connection object's query method


function getStatusColor($status) {
   switch ($status) {
       case 'Accepted':
           return 'accepted';
           break;
       case 'Rejected':
           return 'rejected';
           break;
       case 'Pending':
           return 'pending';
           break;
       default:
           return '';
           break;
   }
}




$select_user = $connection->conn->prepare("SELECT * FROM languagepartners WHERE PartnerID = ? LIMIT 1"); 
$select_user->bind_param("i", $user_id);
$select_user->execute();
$fetch_user = $select_user->get_result()->fetch_assoc();

// Check if the query was successful
if ($fetch_user) {
    // Get the 'name' attribute from the fetched row
    $name = $fetch_user['FirstName'];
} else {
    // Default name if the query fails or no data is found
    $name = "Guest";
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Learner requests</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>

<header class="header">
   
   <section class="flex">

      <a href="home.php" class="logo"> <img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 

      

      <div class="icons">
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
         <img src="images/pic-1.jpg" class="image" alt="">
         <h3 class="name">Richard Murphy</h3>
         <p class="role">Partner</p>
         <a href="profile.html" class="btn">view profile</a>
         <div class="flex-btn">
            <a href="login.html" class="option-btn">login</a>
            <a href="register.html" class="option-btn">register</a>
         </div>
      </div>

   </section>

</header>   

<div class="side-bar">

   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
   <img src="images/<?= $fetch_user['Photo']; ?>" class="image" alt="">
         <h3 class="name"><?= $fetch_user['FirstName'] . ' ' . $fetch_user['LastName']; ?></h3>
         <p class="role">Partner</p>
   </div>

   <nav class="navbar">
      <a href="profilePartner.php"><i class="fas fa-home"></i><span>home</span></a>
      <a href="SessionsPartner.php"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span></a>
      <a href="about_partner.php"><i class="fas fa-question"></i><span>about</span></a>
   </nav>
   <nav>
      <div style="text-align: center; margin-top: 20px; margin-bottom: 150px;">
      <a href="user_logout.php" onclick="return confirm('logout from this website?');" class="inline-btn" >Sign out</a>
   </div>
   </nav>

</div>

<section class="courses">
   <h1 class="heading">Student Requests</h1>

   <div class="box-container">
   <?php
// Display learner requests
if ($stmt && $stmt->num_rows > 0) {
    while ($row = $stmt->fetch_assoc()) {
        // Calculate remaining time only for requests with "Pending" status
if ($row["Status"] === "Pending") {
   // Initial remaining time: 47 hours, 59 minutes, and 59 seconds
   $remainingTime = (47 * 3600) + (59 * 60) + 59;

   $currentTime = time();
   $requestTime = strtotime($row["RequestTimestamp"]);
   $elapsedTime = $currentTime - $requestTime;
   $remainingTime -= $elapsedTime; // Subtract elapsed time from initial remaining time

   // Ensure remaining time doesn't go negative
   $remainingTime = max(0, $remainingTime);

   $remainingHours = floor($remainingTime / 3600); // Convert remaining time to hours
   $remainingMinutes = floor(($remainingTime % 3600) / 60); // Convert remaining time to minutes
   $remainingSeconds = $remainingTime % 60; // Get remaining seconds
} else {
   $remainingTime = null; // Set remaining time to null for "Accepted" or "Rejected" statuses
}

        echo '<div class="box">';
        echo '<div class="tutor">';
        echo '<img src="images/' . $row["Photo"] . '" alt="Learner photo">'; // Assuming static image for now
        echo '<div class="info">';
        // Display learner's name
        $learner_name = $row["LearnerFirstName"] . ' ' . $row["LearnerLastName"];
        echo '<h3>' . $learner_name . '</h3>';
        echo '<span>' . $row["RequestDate"] . '</span>';
        echo '</div>';
        echo '</div>';
        echo '<h3 class="title">' . $learner_name . ' Wants to take a session with you!</h3>';
        echo '<a href="Request_Details.php?request_id=' . $row["RequestID"] . '" class="inline-btn">Request Details</a>';
        echo '<div class="box">';
        $status = $row["Status"]; // Assigning value to $status variable
        echo '<div class="request-status ' . getStatusColor($status) . '">' . $status . ' </div>';
        echo '</div>';

        // Display remaining time if not "Accepted" or "Rejected"
if ($remainingTime !== null && $remainingTime > 0) {
   echo "<p>You have " . $remainingHours . " hours, " . $remainingMinutes . " minutes, and " . $remainingSeconds . " seconds remaining to respond to this request.</p>";
} elseif ($remainingTime !== null && $remainingTime <= 0) {
   $delete_request = $connection->conn->prepare("DELETE FROM LearningRequests WHERE RequestID = ?");
   $delete_request->bind_param("i", $row["RequestID"]);
   $delete_request->execute();
   continue; // Skip processing further for this request
}

        echo '</div>';
    }
} else {
    echo "<p>No learner requests found.</p>";
}
?>


   </div>
</section>
<script src="script.js"></script>
<footer style="margin-top : 80px;" class="footer">
   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_partner.php"><i class="fas fa-headset"></i><span> contact us</span></a>
</footer>

</body>
</html>