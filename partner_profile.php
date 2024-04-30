<?php
include 'connect.php';

// Check if the partner ID is provided in the URL
if(isset($_GET['id'])) {
    $partner_id = $_GET['id'];

    // Fetch partner data from the database based on ID
    function fetchPartnerDetailsFromDatabase($partner_id) {
        global $conn; // Assuming $conn is your database connection object

        // Query to select partner details based on ID
        $query = "SELECT * FROM languagepartners WHERE id = :partner_id";

        // Prepare the statement
        $statement = $conn->prepare($query);

        // Bind parameters
        $statement->bindParam(':partner_id', $partner_id, PDO::PARAM_INT);

        // Execute the query
        $statement->execute();

        // Fetch partner details
        $partner = $statement->fetch(PDO::FETCH_ASSOC);

        // Return the partner details
        return $partner;
    }

    // Call the function to fetch partner details
    $partner = fetchPartnerDetailsFromDatabase($partner_id);

    // Check if partner exists
    if($partner) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Partner profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

   <style>
      .footer
      {
      margin-top : 150px ;
      }
      </style>
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
            <img src="<?php echo $partner['image']; ?>" alt="">
            <h3><?php echo $partner['name']; ?></h3>
            <span><?php echo $partner['description']; ?></span>
         </div>
         <div class="flex">
            <p>Proficiency in Language : <span><?php echo $partner['proficiency']; ?></span></p>
            <p>Session Price : <span><?php echo $partner['session_price']; ?></span></p>
            <p>Rating: <span><?php echo $partner['rating']; ?></span></p>
            <!-- Add more partner details here -->
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
<?php
    } else {
        echo "Partner not found.";
    }
} else {
    echo "Partner ID not provided.";
}
?>
