<?php
// Include the connect.php file
include 'connect.php';

// Create an instance of the Connect class
$connection = new Connect();
if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
 }else{
    $user_id = '';
 }
// Retrieve partners data using the connection object
$sql = "SELECT * FROM LanguagePartners";
$result = $connection->conn->query($sql);

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
    <title>Partners</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
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
        <h1 class="heading">Language Partner List</h1>
        <form action="search_tutor.php" method="post" class="search-tutor">
            <input type="text" name="search_box" maxlength="100" placeholder="Search partners..." required>
            <button type="submit" class="fas fa-search" name="search_tutor"></button>
        </form>

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
                    echo '<p><a href="post_request_learner.php?partnerID=' . $row["PartnerID"] . '" class="inline-btn">Send request</a></p>';
                    echo '</div>';
                }
            } else {
                echo '<p style="font-size: 20px;">No partners found</p>';
            }
            ?>
        </div>
    </section>

<footer class="footer">
    &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
    <a href="contact_learner.php"><i class="fas fa-headset"></i><span> contact us</span></a>
</footer>

<script src="script.js"></script>
</body>
</html>
