<?php
@include 'config.php';

// Start session to check for admin privileges
session_start();
if (!isset($_SESSION['admin_name'])) {
    header('location:login_form.php');
    exit();
}

// Check if the 'course' parameter is set
if (isset($_GET['course'])) {
    $courseToDelete = $_GET['course'];

    // Prepare the SQL statement to delete the course
    $stmt = $conn->prepare("DELETE FROM `user_form` WHERE course = ?");
    $stmt->bind_param("s", $courseToDelete);

    // Execute the statement and check if successful
    if ($stmt->execute()) {
        echo "<script>alert('Course deleted successfully.');</script>";
    } else {
        echo "<script>alert('Failed to delete course.');</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('No course selected for deletion.');</script>";
}

// Redirect back to the admin page
echo "<script>window.location.href = 'admin_page.php';</script>";
?>
