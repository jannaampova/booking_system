<?php
include "../../config.php";
$bookId = $_GET['bookId'];
$propId = $_GET['propid'];
$today = new DateTime();

// Get booking dates
$res = mysqli_query($dbConn, "SELECT fromDate, toDate,totalPrice FROM Booking WHERE id = $bookId");
$row = mysqli_fetch_assoc($res);


// Check if query returns a valid result
if ($row) {
    $bookedFrom = $row['fromDate'];
    $bookedTo = $row['toDate'];
    $price=$row['totalPrice'];
    

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
            $price=($price/100)*30;
            echo "<script>
            window.location.href = 'payment.php?totalPrice=$price&bookId=$bookId&source=cancelBooking&propId=$propId&from=$from&to=$to';
      </script>";

        }
    } else {
        echo "No matching availability found.";
    }
} else {
    echo "Booking not found.";
}
?>