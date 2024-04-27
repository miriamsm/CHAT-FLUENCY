<?php
// Sidebar content based on user role and database connection
function generateSidebar($user_role, $conn) {
    if ($user_role === 'learner') {
        // Query the database to get learner information
        $sql = "SELECT * FROM LanguageLearners WHERE FirstName = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_role);
        $stmt->execute();
        $learner_result = $stmt->get_result();
        $learner_row = $learner_result->fetch_assoc();

        // Sidebar content for learners
        echo '
        <div class="side-bar">

        <div id="close-btn">
           <i class="fas fa-times"></i>
        </div>
     
        <div class="profile">
           <img src="images/pic-1.jpg" class="image" alt="">
           <h3 class="name">' . $learner_row['FirstName'] . ' ' . $learner_row['LastName'] . '</h3>
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
     
     </div>';
    } elseif ($user_role === 'partner') {
        // Query the database to get partner information
        $sql = "SELECT * FROM LanguagePartners WHERE FirstName = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_role);
        $stmt->execute();
        $partner_result = $stmt->get_result();
        $partner_row = $partner_result->fetch_assoc();

        // Sidebar content for partners
        echo '
        <div class="side-bar">

        <div id="close-btn">
           <i class="fas fa-times"></i>
        </div>
     
        <div class="profile">
           <img src="images/pic-1.jpg" class="image" alt="">
           <h3 class="name">' . $partner_row['FirstName'] . ' ' . $partner_row['LastName'] . '</h3>
           <p class="role">Partner</p>
        </div>
     
        <nav class="navbar">
           <a href="profilePartner.html"><i class="fas fa-home"></i><span>home</span></a>
           <a href="SessionsPartner.html"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span></a>
           <a href="about_partner.html"><i class="fas fa-question"></i><span>about</span></a>
        </nav>
        <nav>
           <div style="text-align: center; margin-top: 20px; margin-bottom: 150px;">
              <a href="home.html"  class="inline-btn" >Sign out</a>
        </div>
        </nav>
     
     </div>';
    } else {
        // Default sidebar content if user role is not defined
        echo ''; // You can customize this based on your requirements
    }
}
?>
