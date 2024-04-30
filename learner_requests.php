<?php
include 'connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

global $conn;
// Fetch learner requests from the database along with learner names
$sql = "SELECT lr.*, ll.FirstName AS LearnerFirstName, ll.LastName AS LearnerLastName 
        FROM LearningRequests lr
        INNER JOIN LanguageLearners ll ON lr.LearnerID = ll.LearnerID";
$stmt = $conn->query($sql); // Use PDO query method
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

      <a href="home.html" class="logo"> <img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 

      

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
        
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
      <img src="images/pic-1.jpg" class="image" alt="">
      <h3 class="name">Richard Murphy</h3>
      <p class="role">Partner</p>
   </div>

   <nav class="navbar">
      <a href="profilePartner.html"><i class="fas fa-home"></i><span>home</span></a>
      <a href="SessionsPartner.html"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span></a>
      <a href="about_partner.html"><i class="fas fa-question"></i><span>about</span></a>
   </nav>
   <nav>
      <div style="text-align: center; margin-top: 20px; margin-bottom: 150px;">
      <a href="home.html"  class="inline-btn" >Sign out</a>
   </div>
   </nav>

</div>

<section class="courses">

   <h1 class="heading">Student Requests</h1>

   <div class="box-container">
      <?php
      // Display learner requests
      if ($stmt && $stmt->rowCount() > 0) {
         while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="box">';
            echo '<div class="tutor">';
            echo '<img src="images/pic-9.jpg" alt="">'; // Assuming static image for now
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
            echo '<div class="request-status pending">Pending</div>';
            echo '</div>';
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
   <a href="contact_partner.html"><i class="fas fa-headset"></i><span> contact us</span></a>
</footer>

</body>
</html>
