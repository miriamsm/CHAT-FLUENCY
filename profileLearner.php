<?php

include 'connect.php';
$connection = new connect();
/*
if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
}
*/

$user_id = 123456789;

$select_user = $connection->conn->prepare("SELECT * FROM languagelearners WHERE LearnerID = ? LIMIT 1"); 
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

$select_sessions = $connection->conn->prepare("SELECT * FROM learningsessions WHERE LearnerID = ?");
$select_sessions->bind_param("i", $user_id);
$select_sessions->execute();
$total_sessions = $select_sessions->get_result()->num_rows;

$select_requests = $connection->conn->prepare("SELECT * FROM learningrequests WHERE LearnerID = ?");
$select_requests->bind_param("i", $user_id);
$select_requests->execute();
$total_requests = $select_requests->get_result()->num_rows;

$select_partners = $connection->conn->prepare("SELECT * FROM languagelearners WHERE LearnerID = ?");
$select_partners->bind_param("i", $user_id);
$select_partners->execute();
$total_partners = $select_partners->get_result()->num_rows;
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
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
         <a href="home.html"  class="inline-btn" >Sign out</a>
      </div>
      </nav>
   
   </div>



<section class="user-profile">

   <h1 class="heading"> Welcome <?= $fetch_user['FirstName']; ?>!</h1>

   <div class="info">

      <div class="user">
         <img src="uploaded_files/<?= $fetch_user['Photo']; ?>" alt="">
         <h3><?= $fetch_user['FirstName']. ' ' . $fetch_user['LastName'];  ?></h3>
         <p>Learner</p>
         <p><?= $fetch_user['City'] . ', ' . $fetch_user['Location']; ?></p>
         <a href="updateLearner.php" class="inline-btn">edit profile</a>
      </div>
   
      <div class="box-container">

        <div class="box">
          <div class="flex">
            <img src="images/session.png"  style="width: 25px; height: 25px;" alt="sessions">
             <div>
              
                <span><?= $total_sessions; ?></span>
                <p>sessions</p>
             </div>
          </div>
          <a href="SesssionsLearner.php" class="inline-btn">view sessions</a>
       </div>

       <div class="box">
          <div class="flex">
            <img src="images/request.png"  style="width: 30px; height: 30px;" alt="request">
             <div>
                <span><?= $total_requests; ?></span>
                <p>requests</p>
             </div>
          </div>
          <a href="list_of_requests_learner.html" class="inline-btn">view requests</a>
       </div>
   
       <div class="box">
        <div class="flex">
         <img src="images/teacher-at-the-blackboard.png"  style="width: 30px; height: 30px;" alt="teacher-at-the-blackboard">
           <div>
              <span><?= $total_partners; ?></span>
              <p>partners</p>
               </div>
            </div>
            <a href="partners.php" class="inline-btn">view partners</a>
         </div>
   
      </div>
   </div>

</section>

<footer style="margin-top : 80px;" class="footer">

   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_learner.php"><i class="fas fa-headset"></i><span> contact us</span></a>

</footer>

<!-- custom js file link  -->
<script src="script.js"></script>

</body>
</html>
