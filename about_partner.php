<?php
// Database connection
include 'connect.php';
$connection = new Connect();
if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id='';
   header('location:login.php');
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
   <title>About us</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>

<header class="header">
      <div class="flex">
         <a href="profilePartner.php" class="logo"> <img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 
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
   <img src="images/<?= $fetch_user['Photo']; ?>" class="image" alt="profile photo">
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


<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>

      <div class="content">
         <h3>why choose us?</h3>
         <p>Our interactive platform empowers you to achieve fluency faster than ever before. Join a community of language enthusiasts and embark on a journey to linguistic proficiency with Chat Fluency!</p>
      </div>

   </div>

   <div class="box-container">

      <div class="box">
         <i class="fas fa-graduation-cap"></i>
         <div>
            <h3>+5k</h3>
            <p>Online sessions</p>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-user-graduate"></i>
         <div>
            <h3>+20k</h3>
            <p>brilliant learners</p>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-chalkboard-user"></i>
         <div>
            <h3>+2k</h3>
            <p>expert partners</p>
         </div>
      </div>

     

   </div>

</section> 


   



<footer style="margin-top : 80px;" class="footer">
   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_partner.php"><i class="fas fa-headset"></i><span> contact us</span></a>
</footer>
 

<!-- custom js file link  -->
<script src="js/script.js"></script>

   
</body>
</html>