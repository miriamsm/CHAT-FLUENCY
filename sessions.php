<?php
include 'connect.php';
include 'sidebar.php';

$user_role = ''; 

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
 }else{
    $user_id = '';
 }
 generateSidebar($user_role, $conn); 
 
// Fetching scheduled sessions from LearningSessions table
$sqlCurrent = "SELECT LearningSessions.SessionID, LearningSessions.SessionDate, LearningSessions.SessionDuration, LanguageLearners.FirstName AS LearnerFirstName, LanguageLearners.LastName AS LearnerLastName, LanguagePartners.FirstName AS PartnerFirstName, LanguagePartners.LastName AS PartnerLastName
               FROM LearningSessions
               INNER JOIN LanguageLearners ON LearningSessions.LearnerID = LanguageLearners.LearnerID
               INNER JOIN LanguagePartners ON LearningSessions.PartnerID = LanguagePartners.PartnerID
               WHERE LearningSessions.Status = 'Scheduled'
               ORDER BY LearningSessions.SessionDate DESC";

// Fetching completed or canceled sessions from LearningSessions table
$sqlPrevious = "SELECT LearningSessions.SessionID, LearningSessions.SessionDate, LearningSessions.SessionDuration, LanguageLearners.FirstName AS LearnerFirstName, LanguageLearners.LastName AS LearnerLastName, LanguagePartners.FirstName AS PartnerFirstName, LanguagePartners.LastName AS PartnerLastName
                FROM LearningSessions
                INNER JOIN LanguageLearners ON LearningSessions.LearnerID = LanguageLearners.LearnerID
                INNER JOIN LanguagePartners ON LearningSessions.PartnerID = LanguagePartners.PartnerID
                WHERE LearningSessions.Status = 'Completed' OR LearningSessions.Status = 'Canceled'
                ORDER BY LearningSessions.SessionDate DESC";

$resultCurrent = $conn->query($sqlCurrent); // Execute query for scheduled sessions
$resultPrevious = $conn->query($sqlPrevious); // Execute query for completed or canceled sessions

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sessions</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
   
   <div class="flex">

      <a href="profilePartner.html" class="logo"><img src = "images/logo.jpg" width="210" height="60" alt="logo"></a>

     

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
<div style="display: flex;">



   <section class="playlist-videos" style="flex: 1; margin-right: 20px;">

      <h1 class="heading">Current sessions</h1>

      <div class="box-container">
         <?php
         if($user_role=="partner"){
         if ($resultCurrent->rowCount() > 0) {
             // Output data of each row
             while ($row = $resultCurrent->fetch(PDO::FETCH_ASSOC)) {
                 echo "<a class='box2'>";
                 echo "<div class='student'>";
                 echo "<img src='" . $row['Photo'] . "' alt=''>";
                 echo "<div class='info'>";
                 echo "<h3>" . $row['LearnerFirstName'] . " " . $row['LearnerLastName'] . "</h3>";
                 echo "<span>" . date('d-m-Y', strtotime($row['SessionDate'])) . "</span>";
                 echo "</div>";
                 echo "</div>";
                 echo "<h3>" . $row['SessionID'] . "</h3>"; // Displaying session ID
                 echo "</a>";
             }
         } else {
            echo "<p>No sessions scheduled</p>";
         }}
         else{
            if ($resultCurrent->rowCount() > 0) {
               // Output data of each row
               while ($row = $resultCurrent->fetch(PDO::FETCH_ASSOC)) {
                   echo "<a class='box2'href='partner_profile.html'>";
                   echo "<div class='student'>";
                   echo "<img src='" . $row['Photo'] . "' alt=''>";
                   echo "<div class='info'>";
                   echo "<h3>" . $row['PartnerFirstName'] . " " . $row['PartnerLastName'] . "</h3>";
                   echo "<span>" . date('d-m-Y', strtotime($row['SessionDate'])) . "</span>";
                   echo "</div>";
                   echo "</div>";
                   echo "<h3>" . $row['SessionID'] . "</h3>"; // Displaying session ID
                   echo "</a>";
               }
           } else {
              echo "<p>No sessions scheduled</p>";
         }}
         ?>
      </div>

   </section>

   <section class="playlist-videos" style="flex: 1;">

<h1 class="heading">Previous sessions</h1>

<div class="box-container">

<?php
         if($user_role=="partner"){
         if ($resultPrevious->rowCount() > 0) {
             // Output data of each row for completed or canceled sessions
             while ($row = $resultPrevious->fetch(PDO::FETCH_ASSOC)) {
                 echo "<a class='box'>";
                 echo "<div class='tutor'>";
                 echo "<img src='images/pic-2.jpg' alt=''>";
                 echo "<div class='info'>";
                 echo "<h3>" . $row['LearnerFirstName'] . " " . $row['LearnerLastName'] . "</h3>";
                 echo "<span>" . date('d-m-Y', strtotime($row['SessionDate'])) . "</span>";
                 echo "</div>";
                 echo "</div>";
                 echo "<h3>" . $row['SessionID'] . "</h3>"; // Displaying session ID
                 echo "</a>";
             }
         } else {
             echo "<p>No previous sessions found.</p>";
         }}
         else{
            if ($resultPrevious->rowCount() > 0) {
               // Output data of each row for completed or canceled sessions
               while ($row = $resultPrevious->fetch(PDO::FETCH_ASSOC)) {
                   echo "<a class='box'href='partner_profile.html'>";
                   echo "<div class='tutor'>";
                   echo "<img src='images/pic-2.jpg' alt=''>";
                   echo "<div class='info'>";
                   echo "<h3>" . $row['PartnerFirstName'] . " " . $row['PartnerLastName'] . "</h3>";
                   echo "<span>" . date('d-m-Y', strtotime($row['SessionDate'])) . "</span>";
                   echo "</div>";
                   echo "</div>";
                   echo "<h3>" . $row['SessionID'] . "</h3>"; // Displaying session ID
                   echo "</a>";
               }
           } else {
               echo "<p>No previous sessions found.</p>";
           }
         }
         ?>
</div>

</section>
</div>

   <footer class="footer">

&copy;copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
<a href="contact_partner.html"><i class="fas fa-headset"></i><span> contact us</span></a>

</footer>

<!-- custom js file link  -->
<script src="js/script.js"></script>


</body>
</html>
