<?php

@include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "INSERT INTO `admin_form` (name, position, email, password) VALUES ('$name', '$position', '$email', '$password')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Admin added successfully!'); window.location.href = 'admin_page.php';</script>";
    } else {
        echo "<script>alert('Failed to add admin. Please try again.'); window.location.href = 'admin_page.php';</script>";
    }
}
?>
