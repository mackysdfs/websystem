<?php

@include 'config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['submit'])) {

    // Sanitize and secure inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT); // Use password_hash
    $cpass = $_POST['cpassword']; // Leave raw for comparison
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);

    // Check if the user already exists
    $select = "SELECT * FROM user_form WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'User already exists!';
    } else {
        if (!password_verify($cpass, $pass)) { // Compare raw password with hashed password
            $error[] = 'Passwords do not match!';
        } else {
            // Insert user into database
            $insert = "INSERT INTO user_form (name, email, password, user_type, course) VALUES ('$name', '$email', '$pass', '$user_type', '$course')";
            if (mysqli_query($conn, $insert)) {
                header('Location: login_form.php');
                exit();
            } else {
                $error[] = 'Registration failed: ' . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>NEMSU Register</title>
   
   <link rel="stylesheet" href="css/register.css"> <!-- Link to external CSS -->
</head>
<body>

<div class="header-container">
   <img src="css/nemsu_logo.png" alt="NEMSU Logo">
   <h1>NEMSU LIANGA CAMPUS</h1>
   <div class="buttons-wrapper">
      <button onclick="openModal()">About Us</button>
      <button onclick="openCreator()">Creator</button>

   </div>
  
   <img src="css/logodfim.png" alt="DFIM Logo">
</div>
</div>

   
<div class="form-container">

   <form action="" method="post">
      <h3>Register Now</h3>
      <?php
      if (isset($error)) {
         foreach ($error as $msg) {
            echo '<span class="error-msg">' . $msg . '</span>';
         }
      }
      ?>
      <input type="text" name="name" required placeholder="Your Full Name">
      <input type="text" name="course" required placeholder="Your Course">
      <input type="email" name="email" required placeholder="Enter your email">
      <input type="password" name="password" required placeholder="Enter your password">
      <input type="password" name="cpassword" required placeholder="Confirm your password">
      <select name="user_type">
         <option value="user">User</option>
        
      </select>
      <input type="submit" name="submit" value="Register Now" class="form-btn">
      <p>Already have an account? <a href="login_form.php">Login Now</a></p>
   </form>

</div>
</div>

<div id="creatorModal" class="modal">
   <div class="modal-content">
      <button class="modal-close" onclick="closeCreator()">Close</button>
      <div class="modal-header">About the Creators</div>
      <div class="creator-details" style="display: flex; flex-direction: column; gap: 20px;">
         <!-- Creator 1 -->
         <div style="display: flex; align-items: flex-start; gap: 20px; border: 1px solid #ccc; padding: 10px; border-radius: 8px;">
            <img src="css/jay.jpg" alt="Jay's Image" style="width: 100px; height: auto; border-radius: 50%;">
            <div>
               <strong>Name:</strong> Jay C. Plarisan<br>
               <strong>Position:</strong> Lead Developer<br>
               <strong>Description:</strong><br>
               Jay is a passionate web developer with expertise in creating intuitive and efficient web solutions. With years of experience in coding, he ensures seamless functionality and user-friendly designs in all his projects.
            </div>
         </div>
         <!-- Creator 2 -->
         <div style="display: flex; align-items: flex-start; gap: 20px; border: 1px solid #ccc; padding: 10px; border-radius: 8px;">
            <img src="css/jay.jpg" alt="Jay's Image" style="width: 100px; height: auto; border-radius: 50%;">
            <div>
               <strong>Name:</strong> Mark Vincent B. Algere<br>
               <strong>Position:</strong> UI/UX Designer<br>
               <strong>Description:</strong><br>
               John specializes in crafting visually stunning and highly interactive user interfaces. His design philosophy focuses on usability and ensuring a delightful user experience across all platforms.
            </div>
         </div>
      </div>
   </div>
</div>



<!-- JavaScript -->
<script>
   // Open Creator Modal
   function openCreator() {
      document.getElementById('creatorModal').style.display = 'flex';
   }

   // Close Creator Modal
   function closeCreator() {
      document.getElementById('creatorModal').style.display = 'none';
   }

   // Close modal if clicked outside
   window.onclick = function(event) {
      const creatorModal = document.getElementById('creatorModal');
      if (event.target === creatorModal) {
         creatorModal.style.display = 'none';
      }
   }
     // Modal functionality
     function openModal() {
      document.getElementById('aboutModal').style.display = 'flex';
   }
   function closeModal() {
      document.getElementById('aboutModal').style.display = 'none';
   }

   // Close modal if clicked outside
   window.onclick = function(event) {
      const modal = document.getElementById('aboutModal');
      if (event.target === modal) {
         modal.style.display = 'none';
      }
   };
</script>


<!-- Modal -->
<div id="aboutModal" class="modal">
   <div class="modal-content">
      <button class="modal-close" onclick="closeModal()">Close</button>
      <div class="modal-header">About Us</div>
      <p>
         The DFIMES (Department of fisheries, Invironmental Science, 
         Marine Biology, Environmental Science) is a comprehensive platform designed
          to streamline academic processes for students at NEMSU Lianga Campus. 
          The system provides an intuitive interface By centralizing these resources,
           the system simplifies the way students interact with their academic environment.
      </p>
      <p>
         Navigation through the platform is straightforward and user-friendly,
          ensuring that even first-time users can easily find the information they need.
           With features like a personalized dashboard, and  students are empowered to manage their academic
            responsibilities effectively. Finding campus locations, the system acts as a one-stop solution for navigation needs.
      </p>
      <p>
         DFIMES is particularly helpful for students as it reduces the time spent on tracking rooms, allowing them to navigate freely. 
         By integrating modern technology into education management, 
         the platform enhances accessibility and promotes a seamless educational experience. 
         This initiative not only benefits individual students but also contributes to the overall efficiency of the area.
      </p>
   </div>
</div>
</body>
</html>
