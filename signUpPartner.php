<?php

include 'connect.php';

if(isset($_POST['submit'])){

   $partner_id = unique_id();
   $first_name = $_POST['first_name'];
   $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
   $last_name = $_POST['last_name'];
   $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
   $age = $_POST['age'];
   $age = filter_var($age, FILTER_SANITIZE_NUMBER_INT);
   $gender = $_POST['gender'];
   $gender = filter_var($gender, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $password = sha1($_POST['password']);
   $password = filter_var($password, FILTER_SANITIZE_STRING);
   $phone = $_POST['phone'];
   $phone = filter_var($phone, FILTER_SANITIZE_STRING);
   $city = $_POST['city'];
   $city = filter_var($city, FILTER_SANITIZE_STRING);
   $short_bio = $_POST['short_bio'];
   $short_bio = filter_var($short_bio, FILTER_SANITIZE_STRING);

   $image = $_FILES['photo']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['photo']['size'];
   $image_tmp_name = $_FILES['photo']['tmp_name'];
   $image_folder = 'uploaded_files/'.$rename;

   $select_partner = $conn->prepare("SELECT * FROM `languagepartners` WHERE email = ?");
   $select_partner->execute([$email]);
   
   if($select_partner->rowCount() > 0){
      $message[] = 'Email already taken!';
   }else{
      $insert_partner = $conn->prepare("INSERT INTO `languagepartners`(partner_id, first_name, last_name, age, gender, email, photo, password, phone, city, bio) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
      $insert_partner->execute([$partner_id, $first_name, $last_name, $age, $gender, $email, $rename, $password, $phone, $city, $short_bio]);
      move_uploaded_file($image_tmp_name, $image_folder);
      
      $verify_partner = $conn->prepare("SELECT * FROM `languagepartners` WHERE email = ? AND password = ? LIMIT 1");
      $verify_partner->execute([$email, $password]);
      $row = $verify_partner->fetch(PDO::FETCH_ASSOC);
      
      if($verify_partner->rowCount() > 0){
         setcookie('partner_id', $row['partner_id'], time() + 60*60*24*30, '/');
         header('location:partner_home.php');
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

   <!-- font awesome cdn link  --> 
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"> 

   <!-- custom css file link  --> 
   <link rel="stylesheet" href="style.css"> 

</head> 
<body style="padding-left: 0;"> 

   <header class="header">
   
      <section class="flex">
   
         <a href="home.html" class="logo"> <img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 
   
         <div class="icons">
            <a href="home.html"> <div id="home-btn" class="fas fa-home"> </div> </a>
            <div id="toggle-btn" class="fas fa-sun"></div>
         </div>
   
   
      </section>
   
   </header>    

<section class="form-container">
   <form name="signupForm" action="profilePartner.html" method="post" enctype="multipart/form-data" onsubmit="return validateForm()"> 
       <h3>Sign Up</h3>
      <p>Your first name <span>*</span></p> 
      <input type="text" name="first_name" placeholder="enter your first name" required maxlength="50" class="box"> 

      <p>Your last name <span>*</span></p> 
      <input type="text" name="last_name" placeholder="enter your last name" required maxlength="50" class="box"> 
      
      <p>Your age <span>*</span></p> 
      <input type="number" name="age" placeholder="enter your age" required class="box"> 

      <p>Your gender <span>*</span></p> 
      <select name="gender" class="box"> 
         <option value="male">Male</option> 
         <option value="female">Female</option> 
      </select> 

      <p>Your email <span>*</span></p> 
      <input type="email" name="email" placeholder="enter your email" required maxlength="50" class="box"> 
      
      <p>Your password <span>*</span></p> 
      <input type="password" name="pass" placeholder="enter your password" required maxlength="20" class="box"> 

      <p>Your phone <span>*</span></p> 
      <input type="tel" name="phone" placeholder="enter your phone" required maxlength="15" class="box"> 

      <p>Your city <span>*</span></p> 
      <input type="text" name="city" placeholder="enter your city" required maxlength="50" class="box"> 

      <p>Your short bio(Spoken language and cultural knowledge) <span>*</span></p> 
      <textarea name="short_bio" placeholder="enter your short bio" required maxlength="200" class="box" rows="3"></textarea> 

      <p>Your photo (optional)</p> 
      <input type="file" accept="image/*" class="box"> 

      <input type="submit" value="Sign up" name="submit" class="btn"> 
   </form> 
</section> 

<footer class="footer"> 
   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved! 
   <a href="contact_partner.html"><i class="fas fa-headset"></i><span> contact us</span></a>

</footer> 

<!-- custom js file link  --> 
<script src="js/script.js"></script> 

<script>
   function validateForm() {
      var firstName = document.forms["signupForm"]["first_name"].value;
      var lastName = document.forms["signupForm"]["last_name"].value;
      var age = document.forms["signupForm"]["age"].value;
      var email = document.forms["signupForm"]["email"].value;
      var password = document.forms["signupForm"]["pass"].value;
      var phone = document.forms["signupForm"]["phone"].value;
      var city = document.forms["signupForm"]["city"].value;
      var shortBio = document.forms["signupForm"]["short_bio"].value;

      if (firstName == "" || lastName == "" || age == "" || email == "" || password == "" || phone == "" || city == "" || shortBio == "") {
         alert("All fields must be filled out");
         return false;
      }
   }
</script>

</body> 
</html>