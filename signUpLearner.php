<?php

include 'connect.php';

if(isset($_POST['submit'])){

   $id = unique_id();
   $first_name = $_POST['first_name'];
   $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
   $last_name = $_POST['last_name'];
   $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $password = sha1($_POST['password']);
   $password = filter_var($password, FILTER_SANITIZE_STRING);
   $city = $_POST['city'];
   $city = filter_var($city, FILTER_SANITIZE_STRING);
   $location = $_POST['location'];
   $location = filter_var($location, FILTER_SANITIZE_STRING);

   $image = $_FILES['photo']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['photo']['size'];
   $image_tmp_name = $_FILES['photo']['tmp_name'];
   $image_folder = 'uploaded_files/'.$rename;

   $select_learner = $conn->prepare("SELECT * FROM `languagelearners` WHERE email = ?");
   $select_learner->execute([$email]);
   
   if($select_learner->rowCount() > 0){
      $message[] = 'Email already taken!';
   }else{
      $insert_learner = $conn->prepare("INSERT INTO `languagelearners`(id, first_name, last_name, email, password, photo, city, location) VALUES(?,?,?,?,?,?,?,?)"); 
      $insert_learner->execute([$id, $first_name, $last_name, $email, $password, $rename, $city, $location]);
      move_uploaded_file($image_tmp_name, $image_folder);
      
      $verify_learner = $conn->prepare("SELECT * FROM `languagelearners` WHERE email = ? AND password = ? LIMIT 1"); 
      $verify_learner->execute([$email, $password]);
      $row = $verify_learner->fetch(PDO::FETCH_ASSOC);
      
      if($verify_learner->rowCount() > 0){
         setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
         header('location:learner_home.php');
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
   <title>Sign up</title> 
 
   <!-- font awesome cyyydn link  --> 
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"> 
 
   <!-- custom css file link  --> 
   <link rel="stylesheet" href="style.css"> 
 
</head> 
<body style="padding-left: 0;"> 
 
   <header class="header">
   
      <div class="flex">
   
         <a href="home.html" class="logo"> <img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 
   
         <div class="icons">
            <a href="home.html"> <div id="home-btn" class="fas fa-home"> </div> </a>
            <div id="toggle-btn" class="fas fa-sun"></div>
         </div>
   
   
      </div>
   
   </header>    
 
<section class="form-container">
   <form action="profileLearner.html" method="post" enctype="multipart/form-data" onsubmit="return validateForm()"> 
       <h3>Sign Up</h3>
      <p>Your first name <span>*</span></p> 
      <input type="text" name="first_name" placeholder="enter your first name" required maxlength="50" class="box"> 
 
      <p>Your last name <span>*</span></p> 
      <input type="text" name="last_name" placeholder="enter your last name" required maxlength="50" class="box"> 
 
      <p>Your email <span>*</span></p> 
      <input type="email" name="email" placeholder="enter your email" required maxlength="50" class="box"> 
 
      <p>Your password <span>*</span></p> 
      <input type="password" name="pass" placeholder="enter your password" required maxlength="20" class="box"> 

      <p>Your photo (optional)</p> 
      <input type="file" accept="image/*" class="box"> 

      <p>Your city <span>*</span></p> 
      <input type="text" name="city" placeholder="enter your city" required maxlength="50" class="box"> 

      <p>Your location <span>*</span></p> 
      <input type="text" name="location" placeholder="enter your location" required maxlength="100" class="box"> 
 
      <input type="submit" value="Sign up" name="submit"  class="btn"> 
   </form> 
</section>
 
<footer class="footer"> 
   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved! 
   <a href="contact_learner.html"><i class="fas fa-headset"></i><span> contact us</span></a>

</footer> 
 
<!-- custom js file link  --> 
<script src="js/script.js"></script> 

<script>
   function validateForm() {
      var firstName = document.forms["signupForm"]["first_name"].value;
      var lastName = document.forms["signupForm"]["last_name"].value;
      var email = document.forms["signupForm"]["email"].value;
      var password = document.forms["signupForm"]["pass"].value;
      var city = document.forms["signupForm"]["city"].value;
      var location = document.forms["signupForm"]["location"].value;

      if (firstName == "" || lastName == "" || email == "" || password == "" || city == "" || location == "") {
         alert("All fields must be filled out");
         return false;
      }
   }
</script>
 
</body> 
</html>