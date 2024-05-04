<?php
include 'connect.php';
include 'sidebar.php';
$connection = new Connect();

$user_role = '';

if (isset($_COOKIE['user_id'])) {
   $user_id = $_COOKIE['user_id'];
} else {
   $user_id = '';
   header('location:login.php');
}
generateSidebar($user_role, $connection->conn);

// Fetching scheduled sessions from LearningSessions table
$sqlCurrent = "SELECT LearningSessions.SessionID, LearningSessions.SessionDate, LearningSessions.SessionDuration, LanguageLearners.FirstName AS LearnerFirstName, LanguageLearners.LastName AS LearnerLastName, LanguagePartners.FirstName AS PartnerFirstName, LanguagePartners.LastName AS PartnerLastName
               FROM LearningSessions
               INNER JOIN LanguageLearners ON LearningSessions.LearnerID = LanguageLearners.LearnerID
               INNER JOIN LanguagePartners ON LearningSessions.PartnerID = LanguagePartners.PartnerID
               WHERE LearningSessions.Status = 'Scheduled'
               ORDER BY LearningSessions.SessionDate DESC";

// Fetching completed or canceled sessions from LearningSessions table
$sqlPrevious = "SELECT LearningSessions.SessionID, LearningSessions.SessionDate, LearningSessions.SessionDuration, 
LanguageLearners.FirstName AS LearnerFirstName, LanguageLearners.LastName AS LearnerLastName, 
LanguagePartners.FirstName AS PartnerFirstName, LanguagePartners.LastName AS PartnerLastName,
LearningSessions.Status
FROM LearningSessions
INNER JOIN LanguageLearners ON LearningSessions.LearnerID = LanguageLearners.LearnerID
INNER JOIN LanguagePartners ON LearningSessions.PartnerID = LanguagePartners.PartnerID
WHERE LearningSessions.Status = 'Completed' OR LearningSessions.Status = 'Canceled'
ORDER BY LearningSessions.SessionDate DESC";

$resultCurrent = $connection->conn->query($sqlCurrent); // Execute query for scheduled sessions
$resultPrevious = $connection->conn->query($sqlPrevious); // Execute query for completed or canceled sessions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Retrieve rating and review data from the POST request
   $reviewText = $_POST["reviewText"];
   $rating = $_POST["rating"];
   
   // Perform SQL insertion into the reviewsratings table
   $sql = "INSERT INTO reviewsratings (ReviewText, Rating) VALUES ('$reviewText', '$rating')";
   
   if ($connection->conn->query($sql) === TRUE) {
      echo "Rating and review submitted successfully.";
   } else {
      echo "Error: " . $sql . "<br>" . $connection->conn->error;
   }
} else {
   echo "Invalid request.";
}
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
   <style>
      .rate {
         float: left;
         height: 46px;
         padding: 0 10px;
      }

      .rate:not(:checked)>input {
         position: absolute;
         top: -9999px;
      }

      .rate:not(:checked)>label {
         float: right;
         width: 1em;
         overflow: hidden;
         white-space: nowrap;
         cursor: pointer;
         font-size: 30px;
         color: #ccc;
      }

      .rate:not(:checked)>label:before {
         content: '★ ';
      }

      .rate>input:checked~label {
         color: #ffc700;
      }

      .rate:not(:checked)>label:hover,
      .rate:not(:checked)>label:hover~label {
         color: #deb217;
      }

      .rate>input:checked+label:hover,
      .rate>input:checked+label:hover~label,
      .rate>input:checked~label:hover,
      .rate>input:checked~label:hover~label,
      .rate>label:hover~input:checked~label {
         color: #c59b08;
      }
   </style>
</head>

<body>

   <header class="header">

      <div class="flex">

         <a href="profilePartner.html" class="logo"><img src="images/logo.jpg" width="210" height="60" alt="logo"></a>



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
            <a href="home.html" class="inline-btn">Sign out</a>
         </div>
      </nav>

   </div>
   <div style="display: flex;">



      <section class="playlist-videos" style="flex: 1; margin-right: 20px;">

         <h1 class="heading">Current sessions</h1>

         <div class="box-container">
            <?php
            if ($user_role == "partner") {
               if ($resultCurrent->num_rows > 0) {
                  // Output data of each row
                  while ($row = $resultCurrent->fetch_assoc()) {
                     echo "<a class='box2'>";
                     echo "<div class='student'>";
                     echo "<img src='" . $fetch_user['Photo'] . "' alt=''>";
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
            } else {
               if ($resultCurrent->num_rows > 0) {
                  // Output data of each row
                  while ($row = $resultCurrent->fetch_assoc()) {
                     echo "<a class='box2'href='partner_profile.php'>";
                     echo "<div class='student'>";
                     echo "<img src='" . $fetch_user['Photo'] . "' alt='profile photo'>";
                     echo "<div class='info'>";
                     echo "<h3>" . $row['PartnerFirstName'] . " " . $row['PartnerLastName'] . "</h3>";
                     echo "<span>" . date('d-m-Y', strtotime($row['SessionDate'])) . "</span>";
                     echo "</div>";
                     echo "</div>";
                     echo "<h3>SessionId:" . $row['SessionID'] . "</h3>"; // Displaying session ID
                     echo "</a>";
                  }
               } else {
                  echo "<p>No sessions scheduled</p>";
               }
            }
            ?>
         </div>

      </section>

      <section class="playlist-videos" style="flex: 1;">

         <h1 class="heading">Previous sessions</h1>

         <div class="box-container">

            <?php
            if ($user_role == "partner") {
               if ($resultPrevious->num_rows > 0) {
                  // Output data of each row for completed or canceled sessions
                  while ($row = $resultPrevious->fetch_assoc()) {
                     echo "<a class='box'>";
                     echo "<div class='tutor'>";
                     echo "<img src='images/pic-2.jpg' alt='profile picture'>";
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
            } else {
               if ($resultPrevious->num_rows > 0) {
                  // Output data of each row for completed or canceled sessions
                  while ($row = $resultPrevious->fetch_assoc()) {
                     if ($row['Status'] === 'Completed') {
                        echo "<a class='box'>";

                        echo '<div class="box" onclick="showRating(this)">
                        <div class="tutor">
                           <img src="images/pic-2.jpg" alt="">
                           <div class="info">';
                           echo "<h3>" . $row['PartnerFirstName'] . " " . $row['PartnerLastName'] . "</h3>";
                           echo "<span>" . date('d-m-Y', strtotime($row['SessionDate'])) . "</span>";
                           echo'</div>
                        </div>';
                        echo "<h3>SessionId:" . $row['SessionID'] . "</h3>"; 
                        echo'<button class="inline-btn">Rate</button>
                        <div class="rating-section" style="display: none;">';
                           echo'<form name="rate" action='. $_SERVER['PHP_SELF'] . 'method="post" enctype="multipart/form-data" onsubmit="return validateForm()">';
                           
                          echo' <div class="rate">
                           <textarea class="review-text" placeholder="Write your review..." style="
                           resize: none;
                         "></textarea>
                              <input type="radio" id="star5" name="rate" value="5" />
                              
                              <label for="star5" title="text">5 stars</label>
                              <input type="radio" id="star4" name="rate" value="4" />
                              <label for="star4" title="text">4 stars</label>
                              <input type="radio" id="star3" name="rate" value="3" />
                              <label for="star3" title="text">3 stars</label>
                              <input type="radio" id="star2" name="rate" value="2" />
                              <label for="star2" title="text">2 stars</label>
                              <input type="radio" id="star1" name="rate" value="1" />
                              <label for="star1" title="text">1 star</label>

                           </div>
                           <button class="inline-btn" onclick="submitRating(this)">Submit</button>

                        </div>
                     </div>
                     </form>';
                     echo "</a>";
                  }else{
            

                     echo "<a class='box' href='partner_profile.php'>";
                     echo "<div class='tutor'>";
                     echo "<img src='images/pic-2.jpg' alt=''>";
                     echo "<div class='info'>";
                     echo "<h3>" . $row['PartnerFirstName'] . " " . $row['PartnerLastName'] . "</h3>";
                     echo "<span>" . date('d-m-Y', strtotime($row['SessionDate'])) . "</span>";
                     echo "</div>";
                     echo "</div>";
                     echo "<h3>SessionId:" . $row['SessionID'] . "</h3>"; // Displaying session ID
                     echo "</a>";
                    
                     }}
                  }
                else {
                  echo "<p>No previous sessions found.</p>";
               }
            }
            ?>
         </div>

      </section>
   </div>

   <footer class="footer">

      &copy;copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
      <a href="contact_partner.html"><i class="fas fa-headset"></i><span> contact us</span></a>

   </footer>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>
   <script>
   function showRating(element) {
      var ratingSection = element.querySelector('.rating-section');
      if (ratingSection.style.display === 'none') {
         ratingSection.style.display = 'block';
  
      }
   }
   function submitRating(button) {
   var box = button.closest('.box');
   var reviewText = box.querySelector('.review-text').value;
   var rating = box.querySelector('input[name="rate"]:checked').value;
   
   // // Send the reviewText and rating to your backend using AJAX
   // var xhr = new XMLHttpRequest();
   // xhr.open("POST", "submit_rating.php", true);
   // xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   // xhr.onreadystatechange = function() {
   //    if (xhr.readyState === 4 && xhr.status === 200) {
   //       // Handle the response from the server
   //       console.log(xhr.responseText);
   //    }
   // };
   // var data = "reviewText=" + encodeURIComponent(reviewText) + "&rating=" + encodeURIComponent(rating);
   // xhr.send(data);
}
</script>
   

</body>

</html>