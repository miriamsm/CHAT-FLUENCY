<?php
include 'connect.php';

// Fetch partner data from the database
$partners = []; // Initialize an empty array
function fetchPartnersFromDatabase() {
   global $conn; // Assuming $conn is your database connection object

   // Query to select partners from the database
   $query = "SELECT * FROM languagepartners";

   // Prepare the statement
   $statement = $conn->prepare($query);

   // Execute the query
   $statement->execute();

   // Fetch partners as associative array
   $partners = $statement->fetchAll(PDO::FETCH_ASSOC);

   // Return the array of partners
   return $partners;
}

// Call the function to fetch partners
$partners = fetchPartnersFromDatabase();

// Search functionality
$searchResults = [];
if (isset($_POST['search_tutor'])) {
    $searchTerm = $_POST['search_box'];
    foreach ($partners as $partner) {
        if (stripos($partner['name'], $searchTerm) !== false) {
            $searchResults[] = $partner;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Partners</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>

   <header class="header">
   
      <div class="flex">
   
         <a href="profileLearner.html" class="logo"> <img src="images/logo.jpg" width="210" height="60" alt="logo"></a> 
   
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

<section class="teachers">

   <h1 class="heading">Language Partner List</h1>

   <!-- Search form -->
   <form method="post" class="search-tutor">
      <input type="text" name="search_box" placeholder="search partners..." required maxlength="100">
      <button type="submit" class="fas fa-search" name="search_tutor"></button>
   </form>

   <!-- Display search results or partners -->
   <div class="box-container">
      <?php if (empty($searchResults)) : ?>
         <p>No results found</p>
      <?php else : ?>
         <?php foreach ($searchResults as $partner) : ?>
            <div class="box">
               <!-- Partner details -->
               <div class="tutor">
                  <img src="<?php echo $partner['image']; ?>" alt="">
                  <div>
                     <h3><?php echo $partner['name']; ?></h3>
                     <span><?php echo $partner['role']; ?></span>
                  </div>
               </div>
               <p>Spoken Languages: <span><?php echo $partner['languages']; ?></span></p>
               <p>Rating: <span><?php echo $partner['rating']; ?></span></p>
               <!-- Add other partner details as needed -->

               <!-- Buttons -->
               <a href="partner_profile.html" class="inline-btn">view partner details</a>
               <a href="mailto:<?php echo $partner['email']; ?>" class="inline-btn">Arrange meeting</a>
               <a href="post_request_learner.html" class="inline-btn">Send Request</a>
            </div>
         <?php endforeach; ?>
      <?php endif; ?>
   </div>

</section>

<footer class="footer">

   &copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_learner.html"><i class="fas fa-headset"></i><span> contact us</span></a>

</footer>

<script src="script.js"></script>
</body>
</html>





