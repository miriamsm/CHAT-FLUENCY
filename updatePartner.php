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
$user_id= 12;
session_start();

$select_user = $connection->conn->prepare("SELECT * FROM languagepartners WHERE PartnerID = ? LIMIT 1"); 
$select_user->bind_param("i", $user_id);
$select_user->execute();
$fetch_user = $select_user->get_result()->fetch_assoc();
$message = [];
$redirect_message = '';
if(isset($_POST['submit'])){ //checks if a form with a submit button named 'submit' has been submitted.

  
   $prev_pass = $fetch_user['Password'];
   $prev_image = $fetch_user['Photo'];

$fname = $_POST['FirstName'];
$fname = filter_var($fname, FILTER_SANITIZE_STRING);

$lname = $_POST['LastName'];
$lname = filter_var($lname, FILTER_SANITIZE_STRING);

if(!empty($fname) && $fname != $fetch_user['FirstName']){
    $update_fname = $connection->conn->prepare("UPDATE languagepartners SET FirstName = ? WHERE PartnerID = ?");
    $update_fname->bind_param("si", $fname, $user_id);
    $update_fname->execute();
    $redirect_message ='First name updated successfully!';
}

if(!empty($lname) && $lname != $fetch_user['LastName']){
    $update_lname = $connection->conn->prepare("UPDATE languagepartners SET LastName = ? WHERE PartnerID = ?");
    $update_lname->bind_param("si", $lname, $user_id);
    $update_lname->execute();
    $redirect_message = 'Last name updated successfully!';
}

$city = $_POST['City'];
if (!empty($city) && $city != $fetch_user['City']) {
    // Perform any necessary sanitization or validation of the input data

    // Prepare and execute SQL query to update the city in the database
    $update_city = $connection->conn->prepare("UPDATE languagepartners SET City = ? WHERE PartnerID = ?");
    $update_city->bind_param("si", $city, $user_id);
    $update_city->execute();
    $redirect_message = 'City updated successfully!';
    
} 


$bio = $_POST['Bio'];
if (!empty($bio) && $bio != $fetch_user['Bio']) {

    $update_bio = $connection->conn->prepare("UPDATE languagepartners SET Bio = ? WHERE PartnerID = ?");
    $update_bio->bind_param("si", $bio, $user_id);
    $update_bio->execute();
    $redirect_message  = 'Bio updated successfully!';
    
} 


$email = $_POST['Email'];
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

if (!empty($email) && $email != $fetch_user['Email']) {
    $email_regex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
    if (!preg_match($email_regex, $email)) {
        $message[] = 'Invalid email format';
    } else {
        $update_email = $connection->conn->prepare("UPDATE languagepartners SET Email = ? WHERE PartnerID = ?");
        $update_email->bind_param("si", $email, $user_id);
        $update_email->execute();
        $redirect_message  = 'Email updated successfully!';
    }
}

$phone = $_POST['Phone'];
if (!empty($phone) && $phone != $fetch_user['Phone']) {
    // Perform any necessary sanitization or validation of the input data
    if (ctype_digit($phone)) {
        // Prepare and execute SQL query to update the phone number in the database
        $update_phone = $connection->conn->prepare("UPDATE languagepartners SET Phone = ? WHERE PartnerID = ?");
        $update_phone->bind_param("si", $phone, $user_id);
        $update_phone->execute();
        $redirect_message = 'Phone updated successfully!';
    } else {
        // Render a message if the phone number contains non-numeric characters
        $message[] = 'Please enter a valid phone number!';
    }
}  

$age = $_POST['Age'];
if (!empty($age) && $age != $fetch_user['Age']) {
    // Check if the entered age is a non-negative number
    if ($age >= 0) {
        // Prepare and execute SQL query to update the age in the database
        $update_age = $connection->conn->prepare("UPDATE languagepartners SET Age = ? WHERE PartnerID = ?");
        $update_age->bind_param("ii", $age, $user_id);
        $update_age->execute();
        $redirect_message = 'Age updated successfully!';
    } else {
        // Render a message if the age is negative
        $message[]= 'Age cannot be negative!';
    }
}
$new_gender = $_POST['Gender'];
$current_gender = $fetch_user['Gender'];

if ($new_gender === 'Male' || $new_gender === 'Female') {
    if ($new_gender !== $current_gender) {
        // Prepare and execute SQL query to update the gender in the database
        $update_gender = $connection->conn->prepare("UPDATE languagepartners SET Gender = ? WHERE PartnerID = ?");
        $update_gender->bind_param("si", $new_gender, $user_id);
        $update_gender->execute();
        $redirect_message = 'Gender updated successfully!';
    } 
} else {
    // Render a message if the provided gender is invalid
    $message[] = 'Invalid gender provided!';
}

$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
$Photo = $_FILES['Photo']['name']; // Fetch the name of the uploaded image file
$Photo = filter_var($Photo, FILTER_SANITIZE_STRING);
$ext = strtolower(pathinfo($Photo, PATHINFO_EXTENSION)); // Get the file extension and convert it to lowercase
$Photo_tmp_name = $_FILES['Photo']['tmp_name'];
$Photo_folder = 'images/' . $Photo; // Path to the images directory

if (!empty($Photo) && $Photo != $fetch_user['Photo'] && in_array($ext, $allowed_extensions)) {
    $update_Photo = $connection->conn->prepare("UPDATE `languagelearners` SET `Photo` = ? WHERE LearnerID= ?");
    $update_Photo->execute([$Photo, $user_id]);
    move_uploaded_file($Photo_tmp_name, $Photo_folder);
    
    if ($prev_Photo != '' && $prev_Photo != $Photo) {
        unlink('images/' . $prev_Photo);
    }
    
    $redirect_message = 'Photo updated successfully!';
} else {
    $message[] = 'Invalid file format. Please upload a JPEG, JPG, PNG, or GIF image.';
}
   $old_pass = $_POST['old_pass']; // Assuming the password is sent in plaintext
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = $_POST['new_pass']; // Assuming the password is sent in plaintext
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = $_POST['cpass']; // Assuming the password is sent in plaintext
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
   if (!empty($old_pass)) {
      if ($old_pass !== $prev_pass) {
          $message[] = 'Old password not matched!'; // Inform the user that the old password is incorrect
      } elseif (!empty($new_pass) && $new_pass !== $cpass) {
          $message[] = 'Confirm password not matched!'; // Inform the user that the new passwords do not match
      } else {
          if (!empty($new_pass)) {
              $update_pass = $connection->conn->prepare("UPDATE languagepartners SET Password = ? WHERE PartnerID = ?");
              $update_pass->bind_param("si", $new_pass, $user_id);
              $update_pass->execute();
              $redirect_message = 'Password updated successfully!';
          } else {
              $message[] = 'Please enter a new password!'; // Inform the user to enter a new password
          }
      }
  
  }
  
}
$cancel_button_clicked = isset($_POST['cancel']); // Check if the cancel button was clicked

if ($cancel_button_clicked) {
    // If the cancel button was clicked, set a redirect message and redirect to the profile page
    header('Location: profilePartner.php');
    exit;
}

if ( isset($_POST['deleteacc-confirm']) && $_POST['deleteacc-confirm'] === "true") {
   // Perform the deletion action here
   $delete_user = $connection->conn->prepare("DELETE FROM `languagepartners` WHERE PartnerID = ?");
   $delete_user->bind_param("i", $user_id);
   $delete_user->execute();
   // Redirect the user to a confirmation page or perform any other action
   header('Location: login.php');
   exit;
}


if($redirect_message !== '') {
   // Set the success message in a session variable
   
   $_SESSION['redirect_message'] = $redirect_message;
   // Redirect to profileLearner.php
  header('Location: profilePartner.php');
   exit;

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
</head>
<body>
<script>
   function ConfirmDelete() {
    var confirmed = confirm("Are you sure you want to delete?");
    if (confirmed==true) {
        // Set the value of the hidden input field to indicate confirmation
        document.getElementById("delete-confirm-input").value = "true";
    } else {
        // If canceled, reset the hidden input field value
        document.getElementById("delete-confirm-input").value = "";
    }
    // Submit the form regardless of confirmation status
    document.getElementById("profile-form").submit();
}
</script>


<script>
    // Check if the redirect message session variable is set
    <?php if(isset($_SESSION['redirect_message'])): ?>
        // Display the redirect message as an alert
        alert("<?php echo $_SESSION['redirect_message']; ?>");
        // Unset the session variable to prevent it from being displayed again
        <?php unset($_SESSION['redirect_message']); ?>
    <?php endif; ?>

</script>
   <header class="header">
   
      <div class="flex">
   
         <a href="profilePartner.php" class="logo"><img src = "images/logo.jpg" width="210" height="60" alt="logo"></a>
   
        
   
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
         <img src="images/<?= $fetch_user['Photo']; ?>" class="image" alt="">
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
         <a href="home.html"  class="inline-btn" >Sign out</a>
      </div>
      </nav>
   
   </div>

   
<section class="form-container">

   <form id="profile-form" method="post" enctype="multipart/form-data">
      <h3>Edit profile</h3>
      <p>edit first name</p>
      <input id="first-name-input" type="text" name="FirstName" value="<?= $fetch_user['FirstName']; ?>" placeholder="Enter your first name" maxlength="50" class="box">
      <p>edit last name</p>
      <input id="last-name-input" type="text" name="LastName" value="<?= $fetch_user['LastName']; ?>" placeholder="Enter your last name" maxlength="50" class="box">
      <p>edit bio</p>
      <input id="bio-input" type="text" name="Bio" value="<?= $fetch_user['Bio']; ?>" placeholder="Enter your bio" size="30" maxlength="100" class="box">
      <p>edit city</p>
      <input id="city-input" type="text" name="City" value="<?= $fetch_user['City']; ?>" placeholder="Enter your city" maxlength="50" class="box">
      <p>edit email</p>
      <input id="email-input" type="email" name="Email" value="<?= $fetch_user['Email']; ?>" placeholder="Enter your email" maxlength="50" class="box">
      <p>edit phone</p>
      <input id="phone-input" type="text" name="Phone" value="<?= $fetch_user['Phone']; ?>" placeholder="Enter your Phone" maxlength="10" class="box">
      <p>edit gender</p>
      <select id="gender-input" name="Gender" class="box">
    <option value="Male" <?= ($fetch_user['Gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
    <option value="Female" <?= ($fetch_user['Gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
</select>
      <p>edit age</p>
      <input id="age-input" type="number" name="Age" value="<?= $fetch_user['Age']; ?>" required class="box" value="34">
      <p>previous password</p>
      <input id="previous-pass-input" type="password" name="old_pass" placeholder="enter your old password" maxlength="20" class="box">
      <p>new password</p>
      <input id="new-pass-input" type="password" name="new_pass" placeholder="enter your old password" maxlength="20" class="box">
      <p>confirm password</p>
      <input id="confirm-pass-input" type="password" name="cpass" placeholder="confirm your new password" maxlength="20" class="box">
      <p>edit pic</p>
      <input name = "Photo" id="pic-input" type="file" accept="image/*" class="box">

         <!-- Span elements for displaying validation messages -->
         <?php foreach ($message as $msg) {
   echo '<span class="error-message">' . $msg . '</span>';
}
?>
      <input type="submit" id="cancel-btn"  value="cancel" name="cancel" class="option-btn">
      <input type="submit" id="update-btn" value="update" name="submit" class="btn">
      <input type="submit" id="delete-btn" onclick="ConfirmDelete()" value="delete account" name="deleteacc" class="delete-btn">
      <input type="hidden" id="delete-confirm-input" name="deleteacc-confirm" value="">
        
   </section>
</div>


<footer class="footer">

   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_partner.html"><i class="fas fa-headset"></i><span> contact us</span></a>

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
      const bio = document.getElementById('bio-input').value;
      const city = document.getElementById('city-input').value;
      const email = document.getElementById('email-input').value;
      const phone = document.getElementById('phone-input').value;
      const gender = document.getElementById('gender-input').value;
      const age = document.getElementById('age-input').value;
      const oldPassword = document.getElementById('old-pass-input').value;
      const newPassword = document.getElementById('new-pass-input').value;
      const confirmPassword = document.getElementById('confirm-pass-input').value;
      const pic = document.getElementById('pic-input').value;
   
      // Perform further actions such as validation, AJAX requests, etc.
   
      // Example: Log the form data to the console
      console.log('First Name:', firstName);
      console.log('Last Name:', lastName);
      console.log('Bio:', bio);
      console.log('City:', city);
      console.log('Email:', email);
      console.log('Phone:', phone);
      console.log('Gender:', gender);
      console.log('Age:', age);
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
      window.location.href = 'profilePartner.html';
   
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
      document.getElementById('phone-error').textContent = '';
      document.getElementById('age-error').textContent = '';
   
      // Perform update action here
      const firstName = document.getElementById('first-name-input').value;
      const lastName = document.getElementById('last-name-input').value;
      const city = document.getElementById('city-input').value;
      const age = document.getElementById('age-input').value;
      const phone = document.getElementById('phone-input').value;
      const email = document.getElementById('email-input').value;
      const oldPassword = document.getElementById('previous-pass-input').value;
      const newPassword = document.getElementById('new-pass-input').value;
      const confirmPassword = document.getElementById('confirm-pass-input').value;
      const pic = document.getElementById('pic-input').value;
      const bio = document.getElementById('bio-input').value;
      const gender = document.getElementById('gender-input').value;
   
      let isEmailValid = true;
      let isPasswordValid = true;
      let isFirstNameValid = true;
      let isLastNameValid = true;
      let isCityValid = true;
      let isPhoneValid = true;
      let isAgeValid= true;
   
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
         isPasswordValid = false;
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
    // Validate phone number to allow only numbers
    if (phone.trim() !== '') {
      const phoneRegex = /^\d+$/;
      if (!phoneRegex.test(phone)) {
         document.getElementById('phone-error').textContent = 'Phone number can only contain numbers';
         isPhoneValid = false;
      }
   }

    // Validate age to be positive
    if (age.trim() !== '') {
      const ageNumber = parseInt(age);
      if (isNaN(ageNumber) || ageNumber < 1) {
         document.getElementById('age-error').textContent = 'Age must be a positive number';
         isAgeValid = false;
      }
   }
   if (oldPassword && !newPassword && !confirmPassword) {
      document.getElementById('phone-error').textContent = 'enter new and confirm passwords';
      isPasswordValid=false;
   }
      // Check if any validation failed
      if (!isEmailValid || !isPasswordValid || !isFirstNameValid || !isLastNameValid || !isCityValid || !isPhoneValid || !isAgeValid) {
         return;
      }
      // Log the updated form data to the console
      console.log('First Name:', firstName);
      console.log('Last Name:', lastName);
      console.log('Bio:', bio);
      console.log('City:', city);
      console.log('Phone:', phone);
      console.log('Gender:', gender);
      console.log('Age:', age);
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
<!-- custom js file link  -->
<script src="script.js"></script>

</body>
</html>
