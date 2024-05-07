<?php
include 'connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

// Create an instance of the Connect class
$connection = new Connect();

// Fetch request details based on the request ID
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
        } else {
            $status = "Pending";
        }

        // Update the status in the database
        $update_sql = "UPDATE LearningRequests SET Status = ? WHERE RequestID = ?";
        $stmt = $connection->conn->prepare($update_sql);
        $stmt->bind_param('si', $status, $request_id);
        $stmt->execute();
        
        // Redirect back to learner requests page
        header("Location: learner_requests.php");
        exit();
    }

    // Fetch request details from the database
    $sql = "SELECT lr.*, ll.FirstName AS LearnerFirstName, ll.LastName AS LearnerLastName, ll.Photo
            FROM LearningRequests lr
            INNER JOIN LanguageLearners ll ON lr.LearnerID = ll.LearnerID
            WHERE lr.RequestID = ?";
    $stmt = $connection->conn->prepare($sql);
    $stmt->bind_param('i', $request_id);
    $stmt->execute();
    

    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Display the request details
        $learner_name = $row["LearnerFirstName"] . ' ' . $row["LearnerLastName"];
        $request_date = $row["RequestDate"];
        $goals = $row["LearnerGoals"]; // Assuming this is the column containing learner's goals
        $proficiency = $row["ProficiencyLevel"]; // Assuming this is the column containing learner's proficiency
        $status = $row["Status"]; // Assuming this is the column containing request status
        $Language = $row["LanguageToLearn"];
        $Duration = $row["SessionDuration"];
        $Schedule= $row["PreferredSchedule"];
        $Photo = $row["Photo"] ;
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

$select_user = $connection->conn->prepare("SELECT * FROM languagepartners WHERE PartnerID = ? LIMIT 1"); 
$select_user->bind_param("i", $user_id);
$select_user->execute();
$fetch_user = $select_user->get_result()->fetch_assoc();

// Check if the query was successful
if ($fetch_user) {
    // Get the 'name' attribute from the fetched row
    $name = $fetch_user['FirstName'];
} else {
    // Default name if the query fails or no data is found
    $name = "Guest";
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

      <a href="home.php" class="logo"><img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 

      <div class="icons">
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
      <a href="user_logout.php" onclick="return confirm('logout from this website?');" class="inline-btn" >Sign out</a>
   </div>
   </nav>
</div>

<section class="user-profile">
   <!-- Display request details -->
   <h1 class="heading">Request Details</h1>

   <div class="info">
      <div class="user">

      <img src="images/<?php echo $Photo; ?>" alt="Learner Photo">
         <!-- Display learner information -->
         <h3><?php echo $learner_name; ?></h3>
         <p>Learner</p>
      </div>
   
      <div class="box-container">
      <div class="box">
        <!-- Display learner Session duration -->
        <span style="font-size: 1.5em;"><Strong>Session Duration:</Strong></span>
        <p style="font-size: 1.5em;"><?php echo $Duration; ?></p>
    </div>

    <div class="box">
        <!-- Display learner proficiency -->
        <span style="font-size: 1.5em;"><Strong>Proficiency:</Strong></span>
        <p style="font-size: 1.5em;"><?php echo $proficiency; ?></p>
    </div>

    <div class="box">
        <!-- Display learner language -->
        <span style="font-size: 1.5em;"><Strong>Language to learn:</Strong></span>
        <p style="font-size: 1.5em;"><?php echo $Language; ?></p>
    </div>

    <div class="box">
        <!-- Display learner Preferred schedule -->
        <span style="font-size: 1.5em;"><Strong>Preferred Schedule:</Strong></span>
        <p style="font-size: 1.5em;"><?php echo $Schedule; ?></p>
    </div>

    <div class="box">
        <!-- Display learner goals -->
        <span style="font-size: 1.5em;"><Strong>My goals:</Strong></span>
        <p style="font-size: 1.5em;"><?php echo $goals; ?></p>
    </div>
    
</div>


      <div style="text-align: center; margin-top: 20px;">
         <!-- Buttons to accept or reject request -->
         <a href="Request_Details.php?request_id=<?php echo $request_id; ?>&action=accept" class="inline-btn" style="margin-right: 10px;">Accept request</a>
         <a href="Request_Details.php?request_id=<?php echo $request_id; ?>&action=reject" class="inline-delete-btn">Reject request</a>
      </div>
   </div>
</section>
<script src="script.js"></script>
<footer class="footer">
   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_partner.php"><i class="fas fa-headset"></i><span> contact us</span></a>
</footer>

</body>
</html>
