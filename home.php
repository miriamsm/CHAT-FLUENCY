<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"> 
 
   <!-- custom css file link  --> 
   <link rel="stylesheet" href="style.css"> 
 
    <style>
      .box {
        width: 500%; /* Adjust the width as needed */
        max-width: 600px; /* Set a maximum width if desired */
        height: auto; /* Adjust the height as needed */
        padding: 20px; /* Add padding for better aesthetics */
        margin: auto;
        transform: translateX(-150px);
      }
      
    .box-container{
      text-align: center;
    }
    </style>
    
</head>
<body style="padding-left: 0;">
    <header class="header">
      <div class="flex"> 
        <a href="home.php" class="logo"><img src = "images/logo.jpg" width="210" height="60" alt="logo"></a>
         
         
         <div class="icons">
            <a href="home.php"> <div id="home-btn" class="fas fa-home"> </div> </a>
            <div id="toggle-btn" class="fas fa-sun"></div>
          </div>
    
   
      </div> 
    </header>

    <section class="home-grid">

      <h1 class="heading">welcome to chat Fluency!</h1>
   
      <div class="box-container">

         <div class="box">
            <h3 class="title">chat Fluency with us!</h3>
            <p class="tutor">Welcome to our Online Language Exchange and Learning Platform! We are dedicated to fostering linguistic growth and cultural understanding. Our platform provides a unique space where language enthusiasts connect with native speakers for collaborative language practice. Immerse yourself in a supportive community that goes beyond learning, offering a rich cultural exchange experience. Join us on this journey of language exploration and global connections.</p>
            <a href="login.php" class="btn">Login</a>
            <a href="signUpLearner.php" class="btn">SignUp As learner</a>
            <a href="signUpPartner.php" class="btn">SignUp As Partner</a>
           
         </div>
      </div>
   
   </section>
   

    <footer class="footer">
        &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
        <a href="contact_learner.html"><i class="fas fa-headset"></i><span> contact us</span></a>

    </footer>

    <!-- Include the scripts from your original page -->
    <script src="script.js"></script>
</body>
</html>