
<!DOCTYPE html>
<html lang="en">
<head> 
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of requests </title>
    
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
 
    <!-- custom css file link  -->
    <link rel="stylesheet" href="style.css">
    <style>
      .footer
      {
      margin-top : 350px;
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
<section class="courses">

    <h1 class="heading">your list of requests</h1>
 
    <div class="box-container">
    <?php
$db_name = 'mysql:host=localhost;dbname=chatfluency';
$user_name = 'root';
$user_password = '';

try {
    // Create a PDO connection
    $conn = new PDO($db_name, $user_name, $user_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Assuming you have a way to get the LearnerID, replace 1 with the actual LearnerID value
    $learnerID = 1;

    // Prepare and execute the query to fetch specific columns from LearningRequests table
    $stmt = $conn->prepare("SELECT RequestID, RequestDate, Status FROM LearningRequests WHERE LearnerID = ?");
    $stmt->execute([$learnerID]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the fetched requests without grouping them into rows
   //  echo "<h1 class='heading'>Your list of requests</h1>";
    echo "<div class='box-container'>";

    foreach ($requests as $row) {
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
        echo "<h3>Request #" . $row["RequestID"] . "</h3>";
        echo "<span>{$row['RequestDate']}</span>";
        echo "</div>";
        echo "</div>";
        echo "<div class='thumb'>";
        echo "<span style='background-color: $backgroundColor;'>{$row['Status']}</span>  <br><br><br>";
        echo "</div>";
        echo "<a href='view_request_learner.php?request_id=" . $row["RequestID"] . "' class='inline-btn'>View Request Details</a>";

        echo "</div>";
    }

    echo "</div>"; // Close the box-container
} catch(PDOException $e) {
    // Handle PDO exceptions
    echo "Connection failed: " . $e->getMessage();
}

// Close the PDO connection
$conn = null;
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