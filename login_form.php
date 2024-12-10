<?php
@include 'config.php';

session_start();

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Fetch user details
    $query = "SELECT * FROM user_form WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Check role and redirect accordingly
            if (isset($row['user_type']) && strtolower($row['user_type']) === 'user') {
                $_SESSION['user_name'] = $row['name']; // Store user name in session
                $_SESSION['user_id'] = $row['id'];
                header('Location: user_page.php'); // Redirect to user page
                exit();
            } } else {
                $error[] = 'No account found with this email!';
            }
        } else {
            $error[] = 'Incorrect password!';
        }
    
}
    
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEMSU Login</title>
    <link rel="stylesheet" href="css/login.css"> <!-- Link to external CSS -->
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

<div class="form-container">
    <form action="" method="post">
        <h3>Login Now</h3>
        <?php
        // Display error messages
        if (isset($error)) {
            foreach ($error as $err) {
                echo '<span class="error-msg">' . htmlspecialchars($err) . '</span>';
            }
        }
        ?>
        <input type="email" name="email" required placeholder="Enter your email">

        <!-- Password Field with Toggle -->
        <div class="password-container">
            <input type="password" name="password" id="password" required placeholder="Enter your password">
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>

        <input type="submit" name="submit" value="Login Now" class="form-btn">
        <p>Don't have an account? <a href="register_form.php">Register now</a></p>
        <p>Lon-in as Administrator <a href="admin-loginForm.php">Log-in now</a></p>
    </form>
</div>

<!-- Creator Modal -->
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
                <img src="css/snapy.jpg" alt="Jay's Image" style="width: 100px; height: auto; border-radius: 50%;">
                <div>
                    <strong>Name:</strong> Mark Vincent B. Algere<br>
                    <strong>Position:</strong> UI/UX Designer<br>
                    <strong>Description:</strong><br>
                    Mark specializes in crafting visually stunning and highly interactive user interfaces. His design philosophy focuses on usability and ensuring a delightful user experience across all platforms.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Toggle Password
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.querySelector('.toggle-password');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.textContent = '🙈'; // Change to a "hide" icon
        } else {
            passwordField.type = 'password';
            toggleIcon.textContent = '👁️'; // Change back to "show" icon
        }
    }

    // Modal functionality
    function openModal() {
        document.getElementById('aboutModal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('aboutModal').style.display = 'none';
    }

    // Open Creator Modal
    function openCreator() {
        document.getElementById('creatorModal').style.display = 'flex';
    }
    function closeCreator() {
        document.getElementById('creatorModal').style.display = 'none';
    }

    // Close modal if clicked outside
    window.onclick = function(event) {
        const creatorModal = document.getElementById('creatorModal');
        if (event.target === creatorModal) {
            creatorModal.style.display = 'none';
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
</body>
</html>
