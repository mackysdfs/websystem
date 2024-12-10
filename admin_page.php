<?php

@include 'config.php';

session_start();

// Ensure only admins can access the admin page
if (!isset($_SESSION['admin_name'])) {
    header('location:login_form.php');
    exit();
}

// Fetch unique courses for the dropdown filter
$courseList = [];
$courseQuery = "SELECT DISTINCT course FROM `user_form`";
$courseResult = mysqli_query($conn, $courseQuery);
while ($courseRow = mysqli_fetch_assoc($courseResult)) {
    $courseList[] = $courseRow['course'];
}

// Handle student deletion if requested
if (isset($_GET['delete_student_id'])) {
    $id = intval($_GET['delete_student_id']);
    $deleteQuery = "DELETE FROM `user_form` WHERE id = $id";

    if (mysqli_query($conn, $deleteQuery)) {
        echo "<script>
            alert('Student deleted successfully.');
            window.location.href = 'admin_page.php';
        </script>";
    } else {
        echo "<script>
            alert('Error deleting student.');
            window.location.href = 'admin_page.php';
        </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Admin Dashboard</h3> 
        <button onclick="showSection('manageCourses')" class="sidebar-btn">Manage Courses</button>
        <button onclick="showSection('students')" class="sidebar-btn">Students</button>
        <button onclick="showSection('studentCourse')" class="sidebar-btn">Student Course</button>
        <!-- Logout Button -->
        <div class="logout">
        <p><a href="admin_transaction.php" class="btn">Students Inquiry</a></p>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <button onclick="openAdminForm()" class="sidebar-btn">Add Admin</button>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</h1>

        <!-- Add Course Section -->
        <div id="addCourse" class="section">
            <h2>Add New Course</h2>
            <form method="POST" action="add_course.php">
                <label for="new_course">Course Name:</label>
                <input type="text" id="new_course" name="new_course" required>
                <button type="submit" class="btn">Add Course</button>
            </form>
        </div>

        <!-- Manage Courses Section -->
        <div id="manageCourses" class="section">
            <h2>Manage Courses</h2>
            <p>Here you can manage all courses (e.g., delete courses).</p>
            <button onclick="showSection('addCourse')">Add New Course</button>
            
            <table>
                <thead>
                    <th>Course</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                <?php
                $allCoursesQuery = "SELECT DISTINCT course FROM `user_form`";
                $allCoursesResult = mysqli_query($conn, $allCoursesQuery);
                while ($courseRow = mysqli_fetch_assoc($allCoursesResult)) {
                    $courseName = htmlspecialchars($courseRow['course']);
                    echo "
                        <tr>
                            <td>$courseName</td>
                            <td>
                                <a href='delete_course.php?course=$courseName'><button>Delete</button></a>
                            </td>
                        </tr>
                    ";
                }
                ?>
                </tbody>
            </table>
        </div>

        <!-- Students Section -->
        <div id="students" class="section">
            <h2>Registered Students</h2>
            <p>Below is the list of students registered in the system.</p>
            <table>
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Gender</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                <?php
                $studentsQuery = "SELECT * FROM `user_form`";
                $studentsResult = mysqli_query($conn, $studentsQuery);
                while ($row = mysqli_fetch_assoc($studentsResult)) {
                    $id = $row["id"];
                    $name = htmlspecialchars($row["name"]);
                    $course = htmlspecialchars($row["course"]);
                    $gender = htmlspecialchars($row["gender"]);
                    echo "
                        <tr>
                            <td>$id</td>
                            <td>$name</td>
                            <td>$course</td>
                            <td>$gender</td>
                            <td>
                                <button onclick=\"confirmDeleteStudent($id)\">Delete</button>
                            </td>
                        </tr>
                    ";
                }
                ?>
                </tbody>
            </table>
        </div>

        <!-- Student Course Section -->
        <div id="studentCourse" class="section">
            <h2>Student Course</h2>
            <p>Filter students by course.</p>
            <form id="filterCourseForm">
                <label for="filterCourse">Select Course:</label>
                <select name="filterCourse" id="filterCourse">
                    <option value="">All Courses</option>
                    <?php foreach ($courseList as $courseOption): ?>
                        <option value="<?php echo htmlspecialchars($courseOption); ?>">
                            <?php echo htmlspecialchars($courseOption); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <table id="studentCourseTable">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Gender</th>
                </thead>
                <tbody>
                    <!-- Results will be loaded here dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Admin Modal -->
<div id="adminFormModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeAdminForm()">&times;</span>
        <h2>Add New Admin</h2>
        <form method="POST" action="add_admin.php">
            <input type="text" id="name" name="name" required placeholder="Enter your full Name">
            <input type="text" id="position" name="position" required placeholder="Enter your Position">
            <input type="text" id="email" name="email" required placeholder="Enter your Email">
            <input type="password" id="password" name="password" required placeholder="Enter your Password">
            <button type="submit" class="btn">Add</button>
        </form>
    </div>
</div>

<script>
    function confirmDeleteStudent(studentId) {
        if (confirm("Are you sure you want to delete this student?")) {
            window.location.href = `admin_page.php?delete_student_id=${studentId}`;
        }
    }

    function openAdminForm() {
        document.getElementById('adminFormModal').style.display = 'block';
    }

    function closeAdminForm() {
        document.getElementById('adminFormModal').style.display = 'none';
    }

    window.onclick = function (event) {
        let modal = document.getElementById('adminFormModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };

    function showSection(sectionId) {
        document.querySelectorAll('.section').forEach(section => {
            section.style.display = 'none';
        });
        document.getElementById(sectionId).style.display = 'block';
    }

    $(document).ready(function () {
        $('#filterCourse').on('change', function () {
            let selectedCourse = $(this).val();
            $.ajax({
                url: 'filter_students.php',
                type: 'GET',
                data: { course: selectedCourse },
                success: function (data) {
                    $('#studentCourseTable tbody').html(data);
                }
            });
        });
    });

    showSection('addCourse');
</script>

</body>
</html>
