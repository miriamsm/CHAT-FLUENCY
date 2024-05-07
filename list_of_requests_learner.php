<?php

error_reporting(E_ALL);

ini_set('display_errors', 1);


include 'connect.php';

$connection = new connect();

if(isset($_COOKIE['user_id'])){

   $user_id = $_COOKIE['user_id'];

}else{

   $user_id = '';

   header('location:login.php');

}



$select_user = $connection->conn->prepare("SELECT * FROM languagelearners WHERE LearnerID = ? LIMIT 1"); 

$select_user->bind_param("i", $user_id);

$select_user->execute();

$fetch_user = $select_user->get_result()->fetch_assoc();


$user_id = $_COOKIE['user_id'];


?>


<!DOCTYPE html>

<html lang="en">

<head> 

   <meta charset="UTF-8">

   <meta http-equiv="X-UA-Compatible" content="IE=edge">

   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>List of requests</title>

   

   <!-- Font Awesome CDN link -->

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

 

   <!-- Custom CSS file link -->

   <link rel="stylesheet" href="style.css">

   <style>

      .footer {

         margin-top: 350px;

      }

   </style>

</head>

<body>

   

  

<header class="header">

   

   <div class="flex">


      <a href="profileLearner.php" class="logo"> <img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 

      <?php

session_start(); // Start the session


// Check if the session variable is set and not empty

if (isset($_SESSION['redirect_message']) && !empty($_SESSION['redirect_message'])) {

 $redirect_message = $_SESSION['redirect_message'];


 // Echo or display the message where needed in your HTML

 echo '<script>alert("' . $redirect_message . '");</script>';


 // Clear the session variable

 unset($_SESSION['redirect_message']);

}

?>

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

   <a href="SesssionsLearner.php"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span></a>

   <a href="partners.php"><i class="fas fa-chalkboard-user"></i><span>partners</span></a>

   <a href="about_learner.php"><i class="fas fa-question"></i><span>about</span></a>

</nav>

<nav>

   <div style="text-align: center; margin-top: 20px; margin-bottom: 150px;">

   <a href="user_logout.php"  class="inline-btn" >Sign out</a>

</div>

</nav>


</div>


   <section class="courses">

      <h1 class="heading">Your list of requests</h1>

      <div class="box-container">

         <?php

         $conn = $connection->conn;


         if (!$conn) {

            die("Connection failed: " . mysqli_connect_error());

         }


         // Prepare and execute the query to fetch specific columns from LearningRequests table

         $sql = "SELECT RequestID, RequestDate, Status FROM LearningRequests WHERE LearnerID = ?";

         $stmt = $conn->prepare($sql);

         $stmt->bind_param("i", $user_id);

         $stmt->execute();

         $result = $stmt->get_result();


         $x = 1; // Initialize the $x variable outside the loop

         while ($row = $result->fetch_assoc()) {

             // Determine the background color based on the status

             $backgroundColor = '';

             switch ($row["Status"]) {

                 case 'Rejected':

                     $backgroundColor = 'lightcoral';

                     break;

                 case 'Accepted':

                     $backgroundColor = 'rgb(97, 195, 151)';

                     break;

                 case 'Pending':

                 default:

                     $backgroundColor = 'grey';

                     break;

             }

             // Display each request here with the background color

             echo "<div class='box'>";

             echo "<div class='tutor'>";

             echo "<div class='info'>";

             echo "<h3>Request #" . $x . "</h3>"; // Display the current value of $x

             echo "<span>{$row['RequestDate']}</span>";

             echo "</div>";

             echo "</div>";

             echo "<div class='thumb'>";

             echo "<span style='background-color: $backgroundColor;'>{$row['Status']}</span><br><br><br>";

             echo "</div>";

             echo "<a href='view_request_learner.php?request_id=" . $row["RequestID"] . "' class='inline-btn'>View Request Details</a>";

             echo "</div>";

         

             $x++; // Increment $x after each request

         }


         // Close the box-container

         echo "</div>";


         // Close the prepared statement and database connection

         $stmt->close();

         $conn->close();

         ?>

      </div>

   </section>


   <footer class="footer">

      &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!

      <a href="contact_learner.html"><i class="fas fa-headset"></i><span> contact us</span></a>

   </footer>


   <script src="script.js"></script>

</body>

</html>