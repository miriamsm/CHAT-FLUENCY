<?php
include 'connect.php'; // Include your database connection file

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
