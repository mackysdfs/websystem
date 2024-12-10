<?php

@include 'config.php';

// Check if a course is selected
$course = isset($_GET['course']) && $_GET['course'] !== '' ? $_GET['course'] : null;

// Construct the SQL query
$sql = "SELECT * FROM `user_form`";
if ($course) {
    $sql .= " WHERE course = '" . mysqli_real_escape_string($conn, $course) . "'";
}

$result = mysqli_query($conn, $sql);

// Check if the query returns any rows
if (mysqli_num_rows($result) > 0) {
    // Loop through the results and output each row
    while ($row = mysqli_fetch_assoc($result)) {
        echo "
            <tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["course"] . "</td>
                <td>" . $row["gender"] . "</td>
            </tr>
        ";
    }
} else {
    // If no results are found, display a message
    echo "
        <tr>
            <td colspan='4'>No students found for the selected course.</td>
        </tr>
    ";
}

?>
