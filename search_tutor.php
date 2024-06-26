<?php
include 'connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

// Create an instance of the Connect class
$connection = new Connect();

// Check if the search_box is set
if(isset($_POST['search_tutor']) && !empty($_POST['search_box'])) {
    // Sanitize the input to prevent SQL injection
    $search_term = $_POST['search_box'];
    // SQL query to search for tutors by first name or first and last name
    $sql = "SELECT * FROM LanguagePartners WHERE FirstName LIKE ? OR CONCAT(FirstName, ' ', LastName) LIKE ?";
    // Prepare the statement
    $stmt = $connection->conn->prepare($sql);
    // Bind parameters
    $stmt->bind_param('ss', $search_term, $search_term);
    // Execute the query
    $stmt->execute();
    // Get the result
    $result = $stmt->get_result();
} else {
    // If search_box is empty or not set, retrieve all partners
    $sql = "SELECT * FROM LanguagePartners";
    $result = $connection->conn->query($sql);

}
$select_user = $connection->conn->prepare("SELECT * FROM languagelearners WHERE LearnerID = ? LIMIT 1"); 
$select_user->bind_param("i", $user_id);
$select_user->execute();
$fetch_user = $select_user->get_result()->fetch_assoc();

// Check if the query was successful
if ($fetch_user) {
	$name = $fetch_user['FirstName'];
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
   <title>Tutors</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>

<header class="header">
    <div class="flex">
        <a href="profileLearner.php" class="logo"> <img src="images/logo.jpg" width="210" height="60" alt="logo"></a>
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
      <a href="SessionsLearner.php"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span></a>
      <a href="partners.php"><i class="fas fa-chalkboard-user"></i><span>partners</span></a>
      <a href="about_learner.php"><i class="fas fa-question"></i><span>about</span></a>
   </nav>
   <nav>
      <div style="text-align: center; margin-top: 20px; margin-bottom: 150px;">
      <a href="user_logout.php" onclick="return confirm('logout from this website?');" class="inline-btn" >Sign out</a>
   </div>
   </nav>

</div>
<section class="teachers">
      <h1 class="heading">Search Partner Results</h1>

      <div class="box-container">
         <?php
            if ($result->num_rows > 0) {
               while ($row = $result->fetch_assoc()) {
                  echo '<div class="box">';
                  echo '<div class="tutor">';
                  echo '<img src="images/' . $row["Photo"] . '" alt="">';
                  echo '<div>';
                  echo '<h3>' . $row["FirstName"] . ' ' . $row["LastName"] . '</h3>';
                  echo '<span>Native Speaker</span>';
                  echo '</div>';
                  echo '</div>';
                  echo '<p>Spoken Languages : <span>' . $row["Languages"] . '</span></p>';
                  echo '<p><img alt="star icon" src="https://static.cambly.com/_next/static/media/star.57929b94.svg" style="color: transparent;">  <span>' . $row["Rating"] . '</span></p>';
                  echo '<p><a href="partner_profile.php?partnerID=' . $row["PartnerID"] . '" class="inline-btn">View partner details</a></p>';
                  echo '<p><a href="mailto:' . $row["Email"] . '" class="inline-btn">Arrange meeting</a></p>';
                  echo '<p><a href="post_request_learner.php" class="inline-btn">Send request</a></p>';
                  echo '</div>';
               }
            } else {
               echo '<p style="font-size: 20px;">No partners found</p>';
            }
         ?>
      </div>
   </section>

<!-- teachers section ends -->

<footer class="footer">
    &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
    <a href="contact_learner.php"><i class="fas fa-headset"></i><span> contact us</span></a>
</footer>

<!-- custom js file link  -->
<script src="script.js"></script>
   
</body>
</html>
