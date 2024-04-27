<?php

include 'connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
  header('location:login.php');
}

if(isset($_POST['submit'])){ //checks if a form with a submit button named 'submit' has been submitted.

   $select_user = $conn->prepare("SELECT * FROM `languagelearners` WHERE LearnerID = ? LIMIT 1");
   $select_user->execute([$user_id]);
   $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

   $prev_pass = $fetch_user['Password'];
   $prev_image = $fetch_user['Photo'];

$fname = $_POST['FirstName'];
$fname = filter_var($fname, FILTER_SANITIZE_STRING);

$lname = $_POST['LastName'];
$lname = filter_var($lname, FILTER_SANITIZE_STRING);

if(!empty($fname)){
    $update_fname = $conn->prepare("UPDATE `languagelearners` SET FirstName = ? WHERE LearnerID = ?");
    $update_fname->execute([$fname, $user_id]);
    $message[] = 'First name updated successfully!';
}

if(!empty($lname)){
    $update_lname = $conn->prepare("UPDATE `languagelearners` SET LastName = ? WHERE LearnerID = ?");
    $update_lname->execute([$lname, $user_id]);
    $message[] = 'Last name updated successfully!';
}

$city = $_POST['City'];
if (!empty($city)) {
    // Perform any necessary sanitization or validation of the input data

    // Prepare and execute SQL query to update the city in the database
    $update_city = $conn->prepare("UPDATE 'languagelearners' SET City = ? WHERE LearnerID = ?");
    $update_city->execute([$city, $user_id]);
    $message[] = 'City updated successfully!';
    
} 


$location = $_POST['Location'];
if (!empty($location)) {
    // Perform any necessary sanitization or validation of the input data

    // Prepare and execute SQL query to update the location in the database
    $update_location = $conn->prepare("UPDATE 'languagelearners' SET Location = ? WHERE LearnerID = ?");
    $update_location->execute([$location, $user_id]);
    $message[] = 'Location updated successfully!';
    
} 


   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT Email FROM `languagelearners` WHERE Email = ?");
      $select_email->execute([$email]);
      if($select_email->rowCount() > 0){
         $message[] = 'email already taken!';
      }else{
         $update_email = $conn->prepare("UPDATE `languagelearners` SET Email = ? WHERE LearnerID= ?");
         $update_email->execute([$email, $user_id]);
         $message[] = 'email updated successfully!';
      }
   }

   $Photo = $_FILES['Photo']['name'];//fetches the name of the uploaded image file.
   $Photo = filter_var($Photo, FILTER_SANITIZE_STRING);
   $ext = pathinfo($Photo, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $Photo_size = $_FILES['Photo']['size'];
   $Photo_tmp_name = $_FILES['Photo']['tmp_name'];
   $Photo_folder = 'uploaded_files/'.$rename;

   if(!empty($Photo)){
      if($Photo_size > 2000000){
         $message[] = 'photo size too large!';
      }else{
         $update_Photo = $conn->prepare("UPDATE `languagelearners` SET `Photo` = ? WHERE LearnerID= ?");
         $update_Photo->execute([$rename, $user_id]);
         move_uploaded_file($Photo_tmp_name, $Photo_folder);
         if($prev_Photo != '' AND $prev_Photo != $rename){
            unlink('uploaded_files/'.$prev_Photo);
         }
         $message[] = 'Photo updated successfully!';
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';//This hash value might represent an empty password or a default value. It's used for comparison purposes.
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'old password not matched!';//This condition checks if the hashed value of the old password is not equal to the previous password stored in $prev_pass
      }elseif($new_pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE LearnerID = ?");
            $update_pass->execute([$cpass, $user_id]);
            $message[] = 'password updated successfully!';
         }else{
            $message[] = 'please enter a new password!';
         }
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
   <title>Update</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
   <style>
     
  .option-btn {
    background-color: gray;
  }

  .btn {
    background-color: green;
  }
  .error-message {
         color: red;
         font-size: 16px;
         margin-top: 5px;
      }
   </style>
   <script src="script.js"></script>
   
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
   
<section class="form-container">

   <form id= profile-form method="post" enctype="multipart/form-data">
      <h3>Edit profile</h3>
      <p>edit first name</p>
      <input id= "first-name-input" type="text"name="FirstName" placeholder="<?= $fetch_user['FirstName']; ?>" maxlength="50" class="box">
      <p>edit last name</p>
      <input id="last-name-input" type="text" name="LastName" placeholder="<?= $fetch_user['LastName']; ?>" maxlength="50" class="box">
      <p>edit city</p>
      <input id="city-input" type="text"name="City" placeholder="<?= $fetch_user['City']; ?>" maxlength="50" class="box">
      <p>edit location</p>
      <input id="location-input" type="text" name="Location" placeholder="<?= $fetch_user['Location']; ?>" maxlength="50" class="box">
      <p>edit email</p>
      <input id="email-input" name="Email" placeholder="<?= $fetch_user['Email']; ?>" maxlength="50" class="box">
      <p>previous password</p>
      <input id="old-pass-input"  name="old_pass" placeholder="enter your old password" maxlength="20" class="box">
      <p>new password</p>
      <input id="new-pass-input" type="password" name="new_pass" placeholder="enter your new password" maxlength="20" class="box">
      <p>confirm password</p>
      <input id="confirm-pass-input" type="password" name="cpass" placeholder="confirm your new password" maxlength="20" class="box">
      <p>edit pic</p>
      <input id="pic-input" name="Photo" type="file" accept="image/*" class="box">
      <!-- Span elements for displaying validation messages -->
<span id="email-error" class="error-message"></span>
<span id="password-error" class="error-message"></span>
<span id="firstName-error" class="error-message"></span>
<span id="lastName-error" class="error-message"></span>
<span id="city-error" class="error-message"></span>
<span id="location-error" class="error-message"></span>
      <input  type="submit" id="cancel-btn" value="cancel" name="submit" class="option-btn">
      <input type="submit" id="update-btn" value="update" name="submit" class="btn">
      <input type="submit" id= "delete-btn" value="delete account" name="submit" class="delete-btn">
   </form>
</section>


<footer class="footer">

   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_learner.html"><i class="fas fa-headset"></i><span> contact us</span></a>

</footer>


<script>
   /*
// JavaScript code for handling form submission and button clicks
// Event listener for form submission
document.getElementById('profile-form').addEventListener('submit', function(event) {
   event.preventDefault(); 
 // Prevent the default form submission

   // Perform actions based on the form data
   const firstName = document.getElementById('first-name-input').value;
   const lastName = document.getElementById('last-name-input').value;
   const city = document.getElementById('city-input').value;
   const location = document.getElementById('location-input').value;
   const email = document.getElementById('email-input').value;
   const oldPassword = document.getElementById('old-pass-input').value;
   const newPassword = document.getElementById('new-pass-input').value;
   const confirmPassword = document.getElementById('confirm-pass-input').value;
   const pic = document.getElementById('pic-input').value;

   // Perform further actions such as validation, AJAX requests, etc.

   // Example: Log the form data to the console
   console.log('First Name:', firstName);
   console.log('Last Name:', lastName);
   console.log('City:', city);
   console.log('Location:', location);
   console.log('Email:', email);
   console.log('Old Password:', oldPassword);
   console.log('New Password:', newPassword);
   console.log('Confirm Password:', confirmPassword);
   console.log('Pic:', pic);

   // Reset the form
   document.getElementById('profile-form').reset();
});


// Event listener for the cancel button
document.getElementById('cancel-btn').addEventListener('click', function() {
   // Reset the form
   document.getElementById('profile-form').reset();
   window.location.href = 'profileLearner.html';

   // Perform cancel action here
    // Example: Show an alert message
});
// Event listener for the update button
// Event listener for the update button
document.getElementById('update-btn').addEventListener('click', function() {
  
   document.getElementById('email-error').textContent = '';
   document.getElementById('password-error').textContent = '';
   document.getElementById('firstName-error').textContent = '';
   document.getElementById('lastName-error').textContent = '';
   document.getElementById('city-error').textContent = '';
   document.getElementById('location-error').textContent = '';

   // Perform update action here
   const firstName = document.getElementById('first-name-input').value;
   const lastName = document.getElementById('last-name-input').value;
   const city = document.getElementById('city-input').value;
   const location = document.getElementById('location-input').value;
   const email = document.getElementById('email-input').value;
   const oldPassword = document.getElementById('old-pass-input').value;
   const newPassword = document.getElementById('new-pass-input').value;
   const confirmPassword = document.getElementById('confirm-pass-input').value;
   const pic = document.getElementById('pic-input').value;

   let isEmailValid = true;
   let isPasswordValid = true;
   let isFirstNameValid = true;
   let isLastNameValid = true;
   let isCityValid = true;
   let isLocationValid = true;

   // Validate email format
   if (email.trim() !== '') {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
         document.getElementById('email-error').textContent = 'Invalid email format';
         isEmailValid = false;
      }
   }

   // Validate password length and alphanumeric characters
   if (newPassword.trim() !== '') {
      const passwordRegex = /^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9]{8,}$/;
      if (!passwordRegex.test(newPassword)) {
         document.getElementById('password-error').textContent = 'Password must be at least 8 characters long and contain alphanumeric characters';
         isPasswordValid = false;
      }
   }

   // Validate password matching
   if (newPassword.trim() !== '' && confirmPassword.trim() !== '' && newPassword !== confirmPassword) {
      document.getElementById('password-error').textContent = 'New password and confirm password must match';
      isPasswordvalid = false;
   }

   // Validate first name, last name, city, and location to be characters only
   const nameRegex = /^[a-zA-Z]+$/;
   if (firstName.trim() !== '') {
      if (!nameRegex.test(firstName)) {
         document.getElementById('firstName-error').textContent = 'First name can only contain characters';
         isFirstNameValid = false;
      }
   }

   if (lastName.trim() !== '') {
      if (!nameRegex.test(lastName)) {
         document.getElementById('lastName-error').textContent = 'Last name can only contain characters';
         isLastNameValid = false;
      }
   }

   if (city.trim() !== '') {
      if (!nameRegex.test(city)) {
         document.getElementById('city-error').textContent = 'city can only contain characters';
         isCityValid = false;
      }
   }

   if (location.trim() !== '') {
      if (!nameRegex.test(location)) {
         document.getElementById('location-error').textContent = 'Location can only contain characters';
         isLocationValid = false;
      }
   }
   if (oldPassword && !newPassword && !confirmPassword) {
      document.getElementById('phone-error').textContent = 'enter new and confirm passwords';
      isPasswordValid=false;
   }
   // Check if any validation failed
   if (!isEmailValid || !isPasswordValid || !isFirstNameValid || !isLastNameValid || !isCityValid || !isLocationValid) {
      return;
   }
   // Log the updated form data to the console
   console.log('First Name:', firstName);
   console.log('Last Name:', lastName);
   console.log('City:', city);
   console.log('Location:', location);
   console.log('Email:', email);
   console.log('Old Password:', oldPassword);
   console.log('New Password:', newPassword);
   console.log('Confirm Password:', confirmPassword);
   console.log('Pic:', pic);

   // Reset the form
   document.getElementById('profile-form').reset();
   // Perform further actions such as AJAX requests or updating the database
   // ...

  
   // Show a success message
   alert('Form updated');
   window.location.href = 'profileLearner.html';
});
// Event listener for the delete button
document.getElementById('delete-btn').addEventListener('click', function() {
   // Perform delete action here
   if (confirm('Are you sure you want to delete?')) {
          // Redirect to home.html
          window.location.href = 'home.html';
       // Show a success message
       alert('Account deleted successfully');
   } else {
       // Deletion canceled
       alert('Deletion canceled');  // Example: Show an alert message
   }
});
*/
</script>

</body>
</html>
