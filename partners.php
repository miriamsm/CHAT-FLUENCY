<?php
include 'connect.php';

// Fetch partner data from the database
$partners = []; // Initialize an empty array
function fetchPartnersFromDatabase() {
   global $conn; // Assuming $conn is your database connection object

   // Query to select partners from the database
   $query = "SELECT * FROM languagepartners";

   // Perform the query
   $result = mysqli_query($conn, $query);

   // Check if query was successful
   if ($result) {
       $partners = []; // Initialize an empty array to store partners

       // Fetch each row from the result set
       while ($row = mysqli_fetch_assoc($result)) {
           // Add each row (partner) to the $partners array
           $partners[] = $row;
       }

       // Free the result set
       mysqli_free_result($result);

       // Return the array of partners
       return $partners;
   } else {
       // Query failed, return an empty array or handle the error as needed
       return [];
   }
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
   <!-- Sidebar content -->
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
   <!-- Footer content -->
</footer>

<script src="script.js"></script>
</body>
</html>





