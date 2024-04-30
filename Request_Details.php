<?php
include 'connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

global $conn;

// Check if a request ID is provided in the URL
if(isset($_GET['request_id'])) {
    $request_id = $_GET['request_id'];

    // Update the request status if Accept or Reject button is clicked
    if(isset($_GET['action'])) {
        $action = $_GET['action'];
        
        // Update the status based on the action
        if($action == "accept") {
            $status = "Accepted";
        } elseif($action == "reject") {
            $status = "Rejected";
        }

        // Update the status in the database
        $update_sql = "UPDATE LearningRequests SET Status = :status WHERE RequestID = :request_id";
        $stmt = $conn->prepare($update_sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':request_id', $request_id);
        $stmt->execute();
        
        // Redirect back to learner requests page
        header("Location: learner_requests.php");
        exit();
    }

    // Fetch request details based on the request ID
    $sql = "SELECT * FROM LearningRequests WHERE RequestID = :request_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':request_id', $request_id);
    $stmt->execute();

    if ($stmt && $stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // Display the request details
        $learner_name = $row["LearnerID"]; // Update this to the correct column name
        $request_date = $row["RequestDate"];
        $goals = $row["LanguageToLearn"]; // Assuming this is the column containing learner's goals
        $proficiency = $row["ProficiencyLevel"]; // Assuming this is the column containing learner's proficiency
        $status = $row["Status"]; // Assuming this is the column containing request status
    } else {
        // No request found with the provided ID
        // Redirect back to learner requests page
        header("Location: learner_requests.php");
        exit();
    }
} else {
    // No request ID provided in the URL
    // Redirect back to learner requests page
    header("Location: learner_requests.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Request details</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
   <style>
      .info .inline-btn {
    background-color: green;
  }
  .info .inline-btn:hover {
   background-color: var(--black);
   color: var(--white);  }

   </style>
<style>
   .footer
   {
   margin-top : 100px ;
   }
   </style>
</head>
<body>

<header class="header">
   <div class="flex">

      <a href="home.html" class="logo"><img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
         <img src="images/pic-1.jpg" class="image" alt="">
         <h3 class="name">Richard Murphy</h3>
         <p class="role">Partner</p>
         <a href="PartnerProfile.html" class="btn">view profile</a>
         <div class="flex-btn">
            <a href="login.html" class="option-btn">login</a>
            <a href="register.html" class="option-btn">register</a>
         </div>
      </div>

   </div>
</header>   

<div class="side-bar">
<div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img src="images/pic-1.jpg" class="image" alt="">
      <h3 class="name">Richard Murphy</h3>
      <p class="role">Partner</p>
   </div>

   <nav class="navbar">
      <a href="profilePartner.html"><i class="fas fa-home"></i><span>home</span></a>
      <a href="SessionsPartner.html"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span></a>
      <a href="about_partner.html"><i class="fas fa-question"></i><span>about</span></a>
   </nav>
   <nav>
      <div style="text-align: center; margin-top: 20px; margin-bottom: 150px;">
      <a href="home.html"  class="inline-btn" >Sign out</a>
   </div>
   </nav>
</div>

<section class="user-profile">
   <!-- Display request details -->
   <h1 class="heading">Request Details</h1>

   <div class="info">
      <div class="user">
         <!-- Display learner information -->
         <h3><?php echo $learner_name; ?></h3>
         <p>Learner</p>
      </div>
   
      <div class="box-container">
         <div class="box">
            <!-- Display learner goals -->
            <span>My goals:</span>
            <p><?php echo $goals; ?></p>
         </div>
   
         <div class="box">
            <!-- Display learner proficiency -->
            <span>Proficiency:</span>
            <p><?php echo $proficiency; ?></p>
         </div>
      </div>

      <div style="text-align: center; margin-top: 20px;">
         <!-- Buttons to accept or reject request -->
         <a href="Request_Details.php?request_id=<?php echo $request_id; ?>&action=accept" class="inline-btn" style="margin-right: 10px;">Accept request</a>
         <a href="Request_Details.php?request_id=<?php echo $request_id; ?>&action=reject" class="inline-delete-btn">Reject request</a>
      </div>
   </div>
</section>

<footer class="footer">
   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_partner.html"><i class="fas fa-headset"></i><span> contact us</span></a>
</footer>

</body>
</html>
