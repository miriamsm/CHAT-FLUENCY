<?php
// Include the connect.php file
include 'connect.php';

// Create a new instance of the Connect class
$db = new Connect();

// Check if the form is submitted
if(isset($_POST['submit'])){
    // Retrieve form data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $shortBio = $_POST['short_bio'];
    $photo = ""; // Placeholder for photo, you'll need to handle file upload separately

    // SQL query to insert data into languagepartners table
    $query = "INSERT INTO languagepartners (FirstName, LastName, Age, Gender, Email, Password, Phone, City, Bio, Photo) 
              VALUES ('$firstName', '$lastName', '$age', '$gender', '$email', '$password', '$phone', '$city', '$shortBio', '$photo')";

    // Execute the query
    $result = $db->conn->query($query);

    // Check if the query was successful
    if($result){
        echo "Partner signed up successfully!";
        // Redirect to profilePartner.php or any other page
        // header("Location: profilePartner.php");
        // exit();
    } else {
        echo "Error: " . $db->conn->error;
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
<form name="signupForm" action="SignUpPartner.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()"> 
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
      <input type="password" name="password" placeholder="enter your password" required maxlength="20" class="box">
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
      var password = document.forms["signupForm"]["password"].value;
      var phone = document.forms["signupForm"]["phone"].value;
      var city = document.forms["signupForm"]["city"].value;
      var shortBio = document.forms["signupForm"]["short_bio"].value;

      if (firstName == "" || lastName == "" || age == "" || email == "" || password == "" || phone == "" || city == "" || shortBio == "") {
         alert("All fields must be filled out");
      }
   }
</script>

</body> 
</html>