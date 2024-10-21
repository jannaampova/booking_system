<?php
// adminBoard.php

session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database connection
include 'db_connection.php';

// Fetch all data
$query = "SELECT * FROM bookings";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Booking Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['user']; ?></td>
                    <td><?php echo $row['booking_date']; ?></td>
                    <td>
                        <a href="edit_booking.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="delete_booking.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?></td></tr>