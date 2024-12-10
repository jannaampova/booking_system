<?php
include "../../config.php";
$bookId = $_GET['bookId'];
$propId = $_GET['propid'];
$today = new DateTime();

// Get booking dates
$sql = "SELECT fromDate, toDate FROM Booking WHERE id = $bookId";
$res = mysqli_query($dbConn, $sql);
$row = mysqli_fetch_assoc($res);

// Check if query returns a valid result
if ($row) {
    $bookedFrom = $row['fromDate'];
    $bookedTo = $row['toDate'];

    $sql = "SELECT fromDate, toDate FROM Availabilities WHERE propID = $propId AND fromDate = '$bookedFrom' AND toDate = '$bookedTo'";
    $res = mysqli_query($dbConn, $sql);
    $row = mysqli_fetch_assoc($res);

    if ($row) {
        $from = $row['fromDate'];
        $to = $row['toDate'];

        $fromDate = new DateTime($from);
        $toDate = new DateTime($to);

        $interval = $fromDate->diff($today);

        if ($interval->days > 3) {
            $sql = "UPDATE Availabilities SET propStatus = 'free' WHERE propID = $propId AND fromDate = '$from' AND toDate = '$to'";
            mysqli_query($dbConn, $sql);

            $sql = "UPDATE Booking SET bookingStatus = 'cancelled' WHERE propID = $propId AND fromDate = '$from' AND toDate = '$to'";
            mysqli_query($dbConn, $sql);
            echo "<script>
            window.location.href = 'yourBookings.php'; // Redirect to the bookings page
            </script>";
        } else {
            echo "<script>
            window.location.href = 'payment.php'; // Redirect to the payment page
            </script>";
        }
    } else {
        echo "No matching availability found.";
    }
} else {
    echo "Booking not found.";
}
?>