<?php

include 'connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

// Check if the search_box is set
if(isset($_POST['search_tutor']) && !empty($_POST['search_box'])) {
    // Sanitize the input to prevent SQL injection
    $search_term = $_POST['search_box'];
    // SQL query to search for tutors by first name or first and last name
    $sql = "SELECT * FROM LanguagePartners WHERE FirstName LIKE :search OR CONCAT(FirstName, ' ', LastName) LIKE :search";
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    // Bind parameters
    $stmt->bindParam(':search', $search_term, PDO::PARAM_STR);
    // Execute the query
    $stmt->execute();
} else {
    // If search_box is empty or not set, retrieve all partners
    $sql = "SELECT * FROM LanguagePartners";
    $stmt = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tutors</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

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
      <a href="home.html"  class="inline-btn" >Sign out</a>
   </div>
   </nav>

</div>
<section class="teachers">

   <h1 class="heading">expert tutors</h1>


   <div class="box-container">

      <?php
         if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="box">';
                echo '<div class="tutor">';
                echo '<img src="images/' . $row["Photo"] . '" alt="">';
                echo '<div>';
                echo '<h3>' . $row["FirstName"] . ' ' . $row["LastName"] . '</h3>';
                echo '<span>Native Speaker</span>';
                echo '</div>';
                echo '</div>';
                echo '<p>Spoken Languages : <span>' . $row["Languages"] . '</span></p>';
                echo '<p><img alt="star icon" src="https://static.cambly.com/_next/static/media/star.57929b94.svg" style="color: transparent;">  <span>' . $row["Rating"] . '</span></p>';
                echo '<p><a href="partner_profile.php?partnerID=' . $row["PartnerID"] . '" class="inline-btn">View partner details</a></p>';
                echo '<p><a href="mailto:' . $row["Email"] . '" class="inline-btn">Arrange meeting</a></p>';
                echo '<p><a href="post_request_learner.php" class="inline-btn">Send request</a></p>';
                echo '</div>';
            }
        } else {
            echo '<p style="font-size: 20px;">No partners found</p>';
        }
    ?>
   </div>

</section>

<!-- teachers section ends -->

<footer class="footer">
    &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
    <a href="contact_learner.html"><i class="fas fa-headset"></i><span> contact us</span></a>
</footer>

<!-- custom js file link  -->
<script src="script.js"></script>
   
</body>
</html>
