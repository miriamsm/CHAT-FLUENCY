<?php
include 'connect.php';

// Check if the form is submitted
if(isset($_POST['submit'])) {
    // Assign form data to variables
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $city = $_POST['city'];
    $location = $_POST['location'];

    // Upload photo
    $photo = '';
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["photo"]["size"] > 500000) {
            $uploadOk = 0;
        }
        // Allow only certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo = $target_file;
            }
        }
    }

    // Insert data into database
    $sql = "INSERT INTO languagelearners (FirstName, LastName, Email, Password, Photo, City, Location)
            VALUES ('$firstName', '$lastName', '$email', '$password', '$photo', '$city', '$location')";

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
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
   <form action="profileLearner.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()"> 
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
      <input type="file" accept="image/*" name="photo" class="box"> 

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