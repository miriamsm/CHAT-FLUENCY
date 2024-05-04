<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'Connect.php'; // Include the Connect.php file

class Login {
    private $conn;

    public function __construct() {
        $connect = new Connect(); // Create an instance of the Connect class
        $this->conn = $connect->conn; // Get the database connection from the Connect class
    }

    public function loginUser($email, $password, $role) {
      // Query based on role
      if($role == "learner") {
          $sql = "SELECT * FROM languagelearners WHERE Email = ? AND Password = ?";
          $profilePage = "profileLearner.php"; // Set the profile page for learners
      } elseif($role == "partner") {
          $sql = "SELECT * FROM languagepartners WHERE Email = ? AND Password = ?";
          $profilePage = "profilePartner.php"; // Set the profile page for partners
      }
  
      // Prepare statement
      $stmt = $this->conn->prepare($sql);
      
      if(!$stmt) {
          // Error handling: Check for SQL syntax errors
          echo "SQL Error: " . $this->conn->error;
          return false;
      }
  
      // Bind parameters
      $stmt->bind_param("ss", $email, $password);
  
      // Execute statement
      $stmt->execute();
  
      // Get result
      $result = $stmt->get_result();
  
      if ($result->num_rows > 0) {
          // Login successful
          // Redirect to the appropriate profile page
          header("Location: $profilePage");
          exit;
      } else {
          // Login failed
          echo "<script>alert('Password or email is wrong');</script>";
          return false;
      }
  }
}

// Check if form is submitted
if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $role = $_POST['role'];

    $login = new Login(); // Create an instance of the Login class
    $login->loginUser($email, $password, $role);
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
      <a href="home.html" class="logo"><img src="images/logo.jpg" width="210" height="60" alt="logo"></a>
      <div class="icons">
         <a href="home.html"><div id="home-btn" class="fas fa-home"></div></a>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>
   </div> 
</header>   

<section class="form-container">
<form name="loginForm" method="post" enctype="multipart/form-data" action="" onsubmit="return validateForm()"> <h3>Login now</h3>
      <p>Your email <span>*</span></p>
      <input type="email" name="email" placeholder="Enter your email" required maxlength="50" class="box">
      
      <p>Your password <span>*</span></p>
      <input type="password" name="pass" placeholder="Enter your password" required maxlength="20" class="box">
      
      <p>Select role <span>*</span></p>
      <select name="role" required class="box">
    <option value="" disabled selected>Select role</option>
    <option value="learner">Learner</option>
    <option value="partner">Partner</option>
</select>
      <button type="submit" name="login" class="btn">Login</button>
   </form>
</section>

<footer style="margin-top: 80px;" class="footer">
   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_learner.html"><i class="fas fa-headset"></i><span> contact us</span></a>
</footer> 
<script>
    function validateForm() {
        var email = document.forms["loginForm"]["email"].value;
        var password = document.forms["loginForm"]["pass"].value;
        var role = document.forms["loginForm"]["role"].value;

        if (email == "") {
            alert("Email must be filled out");
            return false;
        }
        if (password == "") {
            alert("Password must be filled out");
            return false;
        }
        if (role == "") {
            alert("Role must be selected");
            return false;
        }
    }
</script>
<script src="js/script.js"></script>
</body>
</html>