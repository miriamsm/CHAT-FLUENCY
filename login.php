<?php

include 'connect.php';

if(isset($_POST['submit'])) {
   $email = $_POST['email'];
   $password = sha1($_POST['pass']);
   
   // Check if login as learner button is clicked
   if(isset($_POST['login_learner'])) {
      $select_learner = $conn->prepare("SELECT * FROM `languagelearners` WHERE email = ? AND password = ?");
      $select_learner->execute([$email, $password]);
      
      if($select_learner->rowCount() > 0) {
         $row = $select_learner->fetch(PDO::FETCH_ASSOC);
         setcookie('user_id', $row['learner_id'], time() + 60*60*24*30, '/');
         header('location:profileLearner.php'); // Redirect to learner profile page
         exit(); // Stop further execution
      } else {
         $message = "Invalid email or password!";
      }
   }
   
   // Check if login as partner button is clicked
   if(isset($_POST['login_partner'])) {
      $select_partner = $conn->prepare("SELECT * FROM `languagepartners` WHERE email = ? AND password = ?");
      $select_partner->execute([$email, $password]);
      
      if($select_partner->rowCount() > 0) {
         $row = $select_partner->fetch(PDO::FETCH_ASSOC);
         setcookie('partner_id', $row['partner_id'], time() + 60*60*24*30, '/');
         header('location:profilePartner.php'); // Redirect to partner profile page
         exit(); // Stop further execution
      } else {
         $message = "Invalid email or password!";
      }
   }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">


</head>
<body style="padding-left: 0;">

<header class="header">
   <div class="flex"> 
      <a href="home.html" class="logo"><img src = "images/logo.jpg" width="210" height="60" alt="logo"></a>
      
      
      <div class="icons">
         <a href="home.html"> <div id="home-btn" class="fas fa-home"> </div> </a>
         <div id="toggle-btn" class="fas fa-sun"></div>
       </div>
 

      </div> 

</header>   

<section class="form-container">

   <form method="post" enctype="multipart/form-data">
      <h3>login now</h3>
      <p>your email <span>*</span></p>
      <input type="email" name="email" placeholder="enter your email" required maxlength="50" class="box">
      
      <p>your password <span>*</span></p>
      <input type="password" name="pass" placeholder="enter your password" required maxlength="20" class="box">
   
      <button type="submit" name="login_learner" class="btn">Log In As learner</button>
      <button type="submit" name="login_partner" class="btn">Log In As Partner</button>
   </form>
</section>

<footer style="margin-top : 80px;" class="footer">

   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_learner.html"><i class="fas fa-headset"></i><span> contact us</span></a>

</footer> 
<!-- custom js file link  -->
<script src="js/script.js"></script>

   
</body>
</html>