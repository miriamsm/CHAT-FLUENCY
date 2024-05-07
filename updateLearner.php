<?php

include 'connect.php';
$connection = new connect();

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
  header('location:login.php');
}

session_start();
$select_user = $connection->conn->prepare("SELECT * FROM languagelearners WHERE LearnerID = ? LIMIT 1"); 
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
    $update_fname = $connection->conn->prepare("UPDATE `languagelearners` SET FirstName = ? WHERE LearnerID = ?");
    $update_fname->bind_param("si", $fname, $user_id);
    $update_fname->execute();
    $redirect_message ='First name updated successfully!';
}

if(!empty($lname) && $lname != $fetch_user['LastName']){
    $update_lname = $connection->conn->prepare("UPDATE `languagelearners` SET LastName = ? WHERE LearnerID = ?");
    $update_lname->bind_param("si", $lname, $user_id);
    $update_lname->execute();
    $redirect_message = 'Last name updated successfully!';
}

$city = $_POST['City'];
if (!empty($city) && $city != $fetch_user['City']) {
    // Perform any necessary sanitization or validation of the input data

    // Prepare and execute SQL query to update the city in the database
    $update_city = $connection->conn->prepare("UPDATE `languagelearners` SET City = ? WHERE LearnerID = ?");
    $update_city->bind_param("si", $city, $user_id);
    $update_city->execute();
    $redirect_message = 'City updated successfully!';
    
} 


$location = $_POST['Location'];
if (!empty($location) && $location != $fetch_user['Location']) {
    // Perform any necessary sanitization or validation of the input data

    // Prepare and execute SQL query to update the location in the database
    $update_location = $connection->conn->prepare("UPDATE `languagelearners` SET Location = ? WHERE LearnerID = ?");
    $update_location->bind_param("si", $location, $user_id);
    $update_location->execute();
    $redirect_message  = 'Location updated successfully!';
    
} 


$email = $_POST['Email'];
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

if (!empty($email) && $email != $fetch_user['Email']) {
    $email_regex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
    if (!preg_match($email_regex, $email)) {
        $message[] = 'Invalid email format';
    } else {
        $update_email = $connection->conn->prepare("UPDATE `languagelearners` SET Email = ? WHERE LearnerID = ?");
        $update_email->execute([$email, $user_id]);
        $update_email->bind_param("si", $email, $user_id);
        $update_email->execute();
        $redirect_message  = 'Email updated successfully!';
    }
}

$Photo = $_FILES['Photo']['name']; // Fetch the name of the uploaded image file
$Photo = filter_var($Photo, FILTER_SANITIZE_STRING);
$Photo_tmp_name = $_FILES['Photo']['tmp_name'];
$Photo_folder = 'images/' . $Photo; // Path to the images directory

// Check if the "Remove Photo" checkbox is checked
if (isset($_POST['remove_photo']) && $_POST['remove_photo'] == 'on') {
    // Remove photo from the database
    $default_photo = "profile.png";
    // Prepare the SQL statement
    $update_Photo = $connection->conn->prepare("UPDATE `languagelearners` SET `Photo` = ? WHERE LearnerID = ?");
    // Bind parameters and execute the statement
    $update_Photo->bind_param("si", $default_photo, $user_id);
    $update_Photo->execute();
    // Remove photo file from the server
    $redirect_message = 'Photo removed successfully!';
    } 
    else {
    // Upload and update photo if not removing
    if (!empty($Photo) && $Photo != $fetch_user['Photo']) {
        $update_Photo = $connection->conn->prepare("UPDATE `languagelearners` SET `Photo` = ? WHERE LearnerID= ?");
        $update_Photo->execute([$Photo, $user_id]);
        move_uploaded_file($Photo_tmp_name, $Photo_folder);

        if ($prev_Photo != '' && $prev_Photo != $Photo) {
            unlink('images/' . $prev_Photo);
        }

        $redirect_message = 'Photo updated successfully!';
    }
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
              $update_pass = $connection->conn->prepare("UPDATE languagelearners SET Password = ? WHERE LearnerID = ?");
              $update_pass->bind_param("si", $new_pass, $user_id);
              $update_pass->execute();
              $redirect_message = 'Password updated successfully!';
          } else {
              $message[] = 'Please enter a new password!'; // Inform the user to enter a new password
          }
      }
  }
  // Check if any field has been updated
if (empty($redirect_message)&& empty($message)) {
    $message[] = 'No fields were updated!';
}


}


$cancel_button_clicked = isset($_POST['cancel']); // Check if the cancel button was clicked

if ($cancel_button_clicked) {
    // If the cancel button was clicked, set a redirect message and redirect to the profile page
    header('Location: profileLearner.php');
    exit;
}


if ( isset($_POST['deleteacc-confirm']) && $_POST['deleteacc-confirm'] === "true") {
   setcookie('user_id', '', time() - 1, '/');
   // Perform the deletion action here
   $delete_user = $connection->conn->prepare("DELETE FROM `languagelearners` WHERE LearnerID = ?");
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
  header('Location: profileLearner.php');
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
   <script src="script.js"></script>
   
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
   
         <a href="profileLearner.html" class="logo"> <img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 
   
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
      <img src="images/<?= $fetch_user['Photo']; ?>" class="image" alt="">
         <h3 class="name"><?= $fetch_user['FirstName'] . ' ' . $fetch_user['LastName']; ?></h3>
         <p class="role">Learner</p>
      </div>
   
      <nav class="navbar">
         <a href="profileLearner.php"><i class="fas fa-home"></i><span>home</span></a>
         <a href="sessionsLearner.php"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span></a>
         <a href="partners.php"><i class="fas fa-chalkboard-user"></i><span>partners</span></a>
         <a href="about_learner.php"><i class="fas fa-question"></i><span>about</span></a>
      </nav>
      <nav>
         <div style="text-align: center; margin-top: 20px; margin-bottom: 150px;">
         <a href="user_logout.php" onclick="return confirm('logout from this website?');" class="inline-btn" >Sign out</a>
      </div>
      </nav>
   
   </div>
   
<section class="form-container">

   <form id= profile-form method="post" enctype="multipart/form-data">
      <h3>Edit profile</h3>
      <p>edit first name</p>
      <input id= "first-name-input" type="text"name="FirstName" value="<?= $fetch_user['FirstName']; ?>" placeholder="Enter your first name" maxlength="50" class="box">
      <p>edit last name</p>
      <input id="last-name-input" type="text" name="LastName" placeholder="Enter your last name" value="<?= $fetch_user['LastName']; ?>" maxlength="50" class="box">
      <p>edit city</p>
      <input id="city-input" type="text"name="City" placeholder="Enter your city" value="<?= $fetch_user['City']; ?>" maxlength="50" class="box">
      <p>edit location</p>
      <input id="location-input" type="text" name="Location" placeholder="Enter your location" value="<?= $fetch_user['Location']; ?>" maxlength="50" class="box">
      <p>edit email</p>
      <input id="email-input" type=email name="Email" placeholder="Enter your email" value="<?= $fetch_user['Email']; ?>" maxlength="50" class="box">
      <p>previous password</p>
      <input id="old-pass-input"  name="old_pass" placeholder="enter your old password" maxlength="20" class="box">
      <p>new password</p>
      <input id="new-pass-input" type="password" name="new_pass" placeholder="enter your new password" maxlength="20" class="box">
      <p>confirm password</p>
      <input id="confirm-pass-input" type="password" name="cpass" placeholder="confirm your new password" maxlength="20" class="box">
      <p>edit pic</p>
      <input id="pic-input" value="<?= $fetch_user['Photo']; ?>" name="Photo" type="file" accept="image/*" class="box">
      <p>
    <input id="remove-photo" type="checkbox" name="remove_photo">
    <label for="remove-photo">Remove Photo</label>
    </p>

      <?php foreach ($message as $msg) {
   echo '<span class="error-message">' . $msg . '</span>';
}
?>
      <!-- Span elements for displaying validation messages -->
<!-- <span id="email-error" class="error-message"></span>
<span id="password-error" class="error-message"></span>
<span id="firstName-error" class="error-message"></span>
<span id="lastName-error" class="error-message"></span>
<span id="city-error" class="error-message"></span>
<span id="location-error" class="error-message"></span> -->
      <input  type="submit" id="cancel-btn" value="cancel" name="cancel" class="option-btn">
      <input type="submit" id="update-btn" value="update" name="submit" class="btn">
      <input type="submit" id="delete-btn" onclick="ConfirmDelete()" value="delete account" name="deleteacc" class="delete-btn">
      <input type="hidden" id="delete-confirm-input" name="deleteacc-confirm" value="">
   </form>
</section>


<footer class="footer">

   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_learner.php"><i class="fas fa-headset"></i><span> contact us</span></a>

</footer>



</body>
</html>
