<?php

@include 'config.php';

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:login_form.php');
}

// Fetch all unique courses
$courseList = [];
$courseQuery = "SELECT DISTINCT course FROM `user_form`";
$courseResult = mysqli_query($conn, $courseQuery);
while ($courseRow = mysqli_fetch_assoc($courseResult)) {
    $courseList[] = $courseRow['course'];
}

// Handle course deletion
if (isset($_GET['delete'])) {
    $deleteCourse = $_GET['delete'];
    $deleteQuery = "DELETE FROM `user_form` WHERE course = '$deleteCourse'";
    if (mysqli_query($conn, $deleteQuery)) {
        echo "<script>alert('Course deleted successfully!'); window.location.href = 'manage_courses.php';</script>";
    } else {
        echo "Error deleting course: " . mysqli_error($conn);
    }
}

// Handle course update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldCourse = $_POST['old_course'];
    $newCourse = $_POST['new_course'];

    $updateQuery = "UPDATE `user_form` SET course = '$newCourse' WHERE course = '$oldCourse'";
    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Course updated successfully!'); window.location.href = 'manage_courses.php';</script>";
    } else {
        echo "Error updating course: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="container">

    <div class="content">
        <h3>Manage Courses</h3>

        <!-- List of Courses -->
        <ul>
            <?php foreach ($courseList as $course): ?>
                <li>
                    <?php echo $course; ?>
                    <a href="?delete=<?php echo $course; ?>" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Update Course -->
        <form method="POST" action="">
            <label for="old_course">Old Course:</label>
            <select name="old_course" id="old_course" required>
                <option value="">Select Course</option>
                <?php foreach ($courseList as $courseOption): ?>
                    <option value="<?php echo $courseOption; ?>"><?php echo $courseOption; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="new_course">New Course Name:</label>
            <input type="text" id="new_course" name="new_course" required>

            <button type="submit" class="btn">Update Course</button>
        </form>

        <a href="admin_page.php" class="btn">Back to Admin Page</a>
    </div>

</div>

</body>
</html>
