<?php
session_start();
require_once 'includes/error_handler.php';

if (!isset($_SESSION['admin_name'])) {
    header('location:admin-loginForm.php');
    exit;
}

try {
    include 'config.php';
    
    $query = "SELECT er.*, uf.name as user_name, uf.course 
             FROM equipment_requests er 
             JOIN user_form uf ON er.user_id = uf.id 
             WHERE er.status = 'pending' 
             ORDER BY er.created_at DESC";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception("Database query failed: " . mysqli_error($conn));
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    $error_message = "An error occurred while loading requests. Please try again later.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Requests</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="admin-page">
        <p><a href="admin_page.php" class="btn">Back</a></p>
        <h1>Equipment Requests</h1>
        <div id="requestsContainer">
            <?php
            if (isset($error_message)) {
                echo "<p>$error_message</p>";
            } else {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='request' data-id='{$row['id']}'>";
                        echo "<p><strong>Student:</strong> {$row['user_name']}</p>";
                        echo "<p><strong>Course:</strong> {$row['course']}</p>";
                        echo "<p><strong>Equipment:</strong> {$row['equipment']}</p>";
                        echo "<p><strong>Borrow Date:</strong> {$row['borrow_date']}</p>";
                        echo "<p><strong>Return Date:</strong> {$row['return_date']}</p>";
                        echo "<div class='actions'>";
                        echo "<button onclick='approveRequest({$row['id']})'>Approve</button>";
                        echo "<button onclick='rejectRequest({$row['id']})'>Reject</button>";
                        echo "</div></div>";
                    }
                } else {
                    echo "<p>No pending requests</p>";
                }
            }
            ?>
        </div>
    </div>

    <script src="admin.js"></script>
</body>
</html>
