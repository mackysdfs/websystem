<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login_form.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Equipment Borrowing</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="user-page">
        <h1>Welcome, <?php echo $_SESSION['user_name']; ?></h1>
        <p><a href="user_page.php" class="btn">back</a></p>
        <button id="openTransactionModal">Request Equipment</button>

        <!-- Show user's requests -->
        <div class="my-requests">
            <h2>My Requests</h2>
            <?php
            include 'config.php';
            
            $user_id = $_SESSION['user_id'];
            $query = "SELECT * FROM equipment_requests 
                     WHERE user_id = '$user_id' 
                     ORDER BY created_at DESC";
            
            $result = mysqli_query($conn, $query);
            
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='request'>";
                    echo "<p><strong>Equipment:</strong> {$row['equipment']}</p>";
                    echo "<p><strong>Borrow Date:</strong> {$row['borrow_date']}</p>";
                    echo "<p><strong>Return Date:</strong> {$row['return_date']}</p>";
                    echo "<p><strong>Status:</strong> <span class='status-{$row['status']}'>{$row['status']}</span></p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No requests found</p>";
            }
            ?>
        </div>

        <!-- Modal for Equipment Request -->
        <div id="transactionModal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h2>Request Equipment</h2>
                <form id="transactionForm">
                    <label for="equipment">Select Equipment:</label>
                    <select id="equipment" name="equipment" required>
                        <option value="" disabled selected>Select equipment</option>
                        <?php
                        $query = "SELECT name, available_quantity FROM equipment_inventory WHERE available_quantity > 0";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['name']}'>{$row['name']} ({$row['available_quantity']} available)</option>";
                        }
                        ?>
                    </select>

                    <label for="borrowDate">Borrow Date:</label>
                    <input type="date" id="borrowDate" name="borrowDate" required>

                    <label for="returnDate">Return Date:</label>
                    <input type="date" id="returnDate" name="returnDate" required>

                    <button type="submit">Submit Request</button>
                </form>
            </div>
        </div>
    </div>

    <script src="user.js"></script>
</body>
</html>
