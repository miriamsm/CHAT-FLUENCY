<?php
include 'connect.php';

if(isset($_GET['partnerID'])){
    $partnerID = $_GET['partnerID'];

    // Create an instance of the Connect class
    $connection = new Connect();

    // Retrieve partner details from the database
    $sql = "SELECT * FROM LanguagePartners WHERE PartnerID = ?";
    $stmt = $connection->conn->prepare($sql);
    $stmt->bind_param('s', $partnerID);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $partner = $result->fetch_assoc();
    } else {
        echo "Partner not found.";
    }

    $stmt->close();
    $connection->conn->close();
} else {
    echo "Partner ID not provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="header">
<div class="flex">
   
   <a href="profileLearner.html" class="logo"> <img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 

   <div class="icons">
      <div id="menu-btn" class="fas fa-bars"></div>
      <div id="toggle-btn" class="fas fa-sun"></div>
   </div>


</div>
</header>

<div class="side-bar">
<div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img src="images/pic-1.jpg" class="image" alt="">
      <h3 class="name">Leena Alshaikh</h3>
      <p class="role">Learner</p>
   </div>

   <nav class="navbar">
      <a href="profileLearner.html"><i class="fas fa-home"></i><span>home</span></a>
      <a href="SesssionsLearner.html"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span></a>
      <a href="partners.html"><i class="fas fa-chalkboard-user"></i><span>partners</span></a>
      <a href="about_learner.html"><i class="fas fa-question"></i><span>about</span></a>
   </nav>
   <nav>
      <div style="text-align: center; margin-top: 20px; margin-bottom: 150px;">
      <a href="home.html"  class="inline-btn" >Sign out</a>
   </div>
   </nav>
</div>

<section class="teacher-profile">
    <h1 class="heading">Partner Details</h1>
    <div class="details">
        <div class="tutor">
            <img src="images/<?php echo $partner['Photo']; ?>" alt="">
            <h3><?php echo $partner['FirstName'] . ' ' . $partner['LastName']; ?></h3>
            <span><?php echo $partner['Bio']; ?></span>
        </div>
        <div class="flex">
            <p>Proficiency in Language : <span><?php echo $partner['LanguageProf']; ?></span></p>
            <p>Session Price : <span><?php echo $partner['SessionPrice']; ?> per hour.</span></p> 
            <p><img alt="star icon" loading="lazy" width="16" height="16" decoding="async" src="https://static.cambly.com/_next/static/media/star.57929b94.svg" style="color: transparent;"> <?php echo $partner['Rating']; ?> • 6 reviews <a href="reviewsLearner.php?partnerID=<?php echo $partnerID; ?>" class="inline-btn">View Reviews</a></p>
        </div>
    </div>
</section>

<footer class="footer">
&copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_learner.html"><i class="fas fa-headset"></i><span> contact us</span></a>
</footer>

<script src="script.js"></script>
</body>
</html>


