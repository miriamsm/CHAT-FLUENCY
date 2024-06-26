<?php
include 'connect.php';
$connection = new Connect();

if (isset($_COOKIE['user_id'])) {
   $user_id = $_COOKIE['user_id'];
} else {
   $user_id = '';
   header('location:login.php');
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

$partnerId = $user_id;


// Fetching scheduled sessions from LearningSessions table
$sqlCurrent = "SELECT LearningSessions.SessionID, LearningSessions.SessionDate, LearningSessions.SessionDuration,
 LanguageLearners.FirstName AS LearnerFirstName, LanguageLearners.LastName AS LearnerLastName,
 LanguagePartners.FirstName AS PartnerFirstName, LanguagePartners.LastName AS PartnerLastName, 
 LanguageLearners.Photo AS LearnerPhoto
               FROM LearningSessions
               INNER JOIN LanguageLearners ON LearningSessions.LearnerID = LanguageLearners.LearnerID
               INNER JOIN LanguagePartners ON LearningSessions.PartnerID = LanguagePartners.PartnerID
               WHERE LearningSessions.PartnerID = '$partnerId' AND LearningSessions.Status = 'Scheduled' 
               ORDER BY LearningSessions.SessionDate DESC";

// Fetching completed or canceled sessions from LearningSessions table
$sqlPrevious = "SELECT LearningSessions.SessionID, LearningSessions.SessionDate, LearningSessions.SessionDuration, 
LanguageLearners.FirstName AS LearnerFirstName, LanguageLearners.LastName AS LearnerLastName, 
LanguagePartners.FirstName AS PartnerFirstName, LanguagePartners.LastName AS PartnerLastName,
LearningSessions.Status, LanguageLearners.Photo AS LearnerPhoto
FROM LearningSessions
INNER JOIN LanguageLearners ON LearningSessions.LearnerID = LanguageLearners.LearnerID
INNER JOIN LanguagePartners ON LearningSessions.PartnerID = LanguagePartners.PartnerID
WHERE LearningSessions.PartnerID = '$partnerId' AND (LearningSessions.Status = 'Completed' OR LearningSessions.Status = 'Canceled')
ORDER BY LearningSessions.SessionDate DESC";

$resultCurrent = $connection->conn->query($sqlCurrent); // Execute query for scheduled sessions
$resultPrevious = $connection->conn->query($sqlPrevious); // Execute query for completed or canceled sessions

?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sessions</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>

<body>

<header class="header">
      <div class="flex">
         <a href="profilePartner.php" class="logo"> <img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 
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
   <img src="images/<?= $fetch_user['Photo']; ?>" class="image" alt="profile photo">
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

   <div style="display: flex;">



      <section class="playlist-videos" style="flex: 1; margin-right: 20px;">

         <h1 class="heading">Current sessions</h1>

         <div class="box-container">
            <?php
            if ($resultCurrent->num_rows > 0) {
               // Output data of each row
               while ($row = $resultCurrent->fetch_assoc()) {
                  echo "<a class='box2'>";
                  echo "<div class='student'>";
                  echo '<img src="images/' . $row['LearnerPhoto'] . '" alt="profile picture">';
                  echo "<div class='info'>";
                  echo "<h3>" . $row['LearnerFirstName'] . " " . $row['LearnerLastName'] . "</h3>";
                  echo "<span>" . date('d-m-Y', strtotime($row['SessionDate'])) . "</span>";
                  echo "</div>";
                  echo "</div>";
                  echo "<h3>SessionId:" . $row['SessionID'] . "</h3>"; // Displaying session ID
                  echo "</a>";
               }
            } else {
               echo "<p>No sessions scheduled</p>";
            }

            ?>
         </div>

      </section>

      <section class="playlist-videos" style="flex: 1;">

         <h1 class="heading">Previous sessions</h1>

         <div class="box-container">

            <?php
            if ($resultPrevious->num_rows > 0) {
               // Output data of each row for completed or canceled sessions
               while ($row = $resultPrevious->fetch_assoc()) {
                  echo "<a class='box2'>";
                  echo "<div class='student'>";
                  echo '<img src="images/' . $row['LearnerPhoto'] . '" alt="profile picture">';
                  echo "<div class='info'>";
                  echo "<h3>" . $row['LearnerFirstName'] . " " . $row['LearnerLastName'] . "</h3>";
                  echo "<span>" . date('d-m-Y', strtotime($row['SessionDate'])) . "</span>";
                  echo "</div>";
                  echo "</div>";
                  echo "<h3>SessionId:" . $row['SessionID'] . "</h3>"; // Displaying session ID
                  echo "</a>";
               }
            } else {
               echo "<p>No previous sessions found.</p>";
            }


            ?>
         </div>

      </section>
   </div>

   <footer style="margin-top : 80px;" class="footer">
   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_partner.php"><i class="fas fa-headset"></i><span> contact us</span></a>
</footer>
   <!-- custom js file link  -->
   <script src="script.js"></script>



</body>

</html>