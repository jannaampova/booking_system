<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: ../../userEntry/logIn.php"); // Redirect to login if not logged in
    exit();
}

include "../../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bookingID'])) {
        $bookingID = intval($_POST['bookingID']);
        $propertyID = $_POST['propertyID'] ?? null;
        $checkInDate = $_POST['checkInDate'] ?? null;
        $checkOutDate = $_POST['checkOutDate'] ?? null;

        if (!$bookingID || !$propertyID || !$checkInDate || !$checkOutDate) {
            echo "<p>Error: Missing required data for processing the request.</p>";
            return;
        }

        if (isset($_POST['approveBtn'])) {
            // Approve the booking
            $approveSql = "UPDATE Booking SET bookingStatus = 'approved' WHERE id = '$bookingID'";
            if (mysqli_query($dbConn, $approveSql)) {

                // Update availabilities
                $availabilitySql = "SELECT * FROM Availabilities 
                                    WHERE propID = $propertyID 
                                    AND fromDate <= '$checkInDate' 
                                    AND toDate >= '$checkOutDate'";

                $result = mysqli_query($dbConn, $availabilitySql);
                if ($row = mysqli_fetch_assoc($result)) {
                    $originalFromDate = $row['fromDate'];
                    $originalToDate = $row['toDate'];

                    // Handle availability ranges
                    if ($checkInDate == $originalFromDate && $checkOutDate == $originalToDate) {
                        $deleteSql = "DELETE FROM Availabilities WHERE id = {$row['id']}";
                        mysqli_query($dbConn, $deleteSql);
                    } elseif ($checkInDate == $originalFromDate) {
                        $updateSql = "UPDATE Availabilities 
                                      SET fromDate = $checkOutDate;
                                      WHERE id = {$row['id']}";
                        mysqli_query($dbConn, $updateSql);
                    } elseif ($checkOutDate == $originalToDate) {
                        $updateSql = "UPDATE Availabilities 
                                      SET toDate = DATE_SUB('$checkInDate', INTERVAL 1 DAY) 
                                      WHERE id = {$row['id']}";
                        mysqli_query($dbConn, $updateSql);
                    } else {
                        $splitSql1 = "UPDATE Availabilities 
                                      SET toDate = DATE_SUB('$checkInDate', INTERVAL 1 DAY) 
                                      WHERE id = {$row['id']}";

                        $splitSql2 = "INSERT INTO Availabilities (fromDate, toDate, propStatus, propID)
                                      VALUES ('$checkOutDate', '$originalToDate', 'free', $propertyID)";

                        mysqli_query($dbConn, $splitSql1);
                        mysqli_query($dbConn, $splitSql2);
                    }

                } else {
                }
            } else {
                echo "<p>Error approving booking: " . mysqli_error($dbConn) . "</p>";
            }
        } elseif (isset($_POST['cancelBtn'])) {
            // Cancel the booking
            $sql = "UPDATE Booking SET bookingStatus = 'Declined' WHERE id = $bookingID";
            if (mysqli_query($dbConn, $sql)) {
                echo "<p>Booking with ID $bookingID has been declined.</p>";
            } else {
                echo "<p>Error updating booking: " . mysqli_error($dbConn) . "</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Bookings</title>
    <link rel="stylesheet" href="../../css/addEditAdminHost.css" />
    <link rel="stylesheet" href="../../css/propInfo.css" />
    <link rel="stylesheet" href="../../css/admin.css" />
    <link rel="stylesheet" href="../../css/nav.css" />
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <style>
        h2, h3 { margin-top: 0; color: #222; margin-bottom: 15px; font-weight: 600; }
        button { background-color: #688587; color: white; border: none; font-size: 14px; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #2b3a3b; }
    </style>
</head>

<body>
    <div class="main">
        <div class="left-container">
            <div class="options">
                <a href="../userSettings.php">
                    <i class="fas fa-user-edit"></i>
                    <?php echo htmlspecialchars($_SESSION['name']); ?>
                </a>
                <a href="hostBoard.php">Dashboard</a>
                <a href="../admin/logOut.php">Log Out <i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>

        <div class="inner-flex">
            <div class="detail">
                <?php
                $sqlBookings = "SELECT 
                    p.propName AS propName,
                    g.guestNum AS guestNum,
                    u.fullName AS clientName,
                    p.id as propID,
                    b.id as bookingID,
                    b.fromDate, 
                    b.toDate, 
                    b.totalPrice, 
                    b.bookingStatus
                FROM Booking b
                JOIN User u ON b.clientID = u.id
                JOIN Property p ON b.propID = p.id
                JOIN GuestNumber g ON p.guestNumID = g.id
                WHERE p.hostID = {$_SESSION['userID']} AND b.bookingStatus = 'pending'";

                $res = mysqli_query($dbConn, $sqlBookings);

                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "
                            <div class='property-details'>
                                <div class='property-info'>
                                    <h3>" . htmlspecialchars($row['propName']) . "</h3>
                                    <p>Client: " . htmlspecialchars($row['clientName']) . "</p>
                                    <p>Booking: " . htmlspecialchars($row['fromDate']) . " to " . htmlspecialchars($row['toDate']) . "</p>
                                    <p>Total Price: $" . htmlspecialchars($row['totalPrice']) . "</p>
                                </div>
                                <div class='property-info-buttons'>
                                    <form method='post'>
                                        <input type='hidden' name='propertyID' value='" . htmlspecialchars($row['propID']) . "'>
                                        <input type='hidden' name='checkInDate' value='" . htmlspecialchars($row['fromDate']) . "'>
                                        <input type='hidden' name='checkOutDate' value='" . htmlspecialchars($row['toDate']) . "'>
                                        <input type='hidden' name='bookingID' value='" . htmlspecialchars($row['bookingID']) . "'>
                                        <button name='approveBtn' type='submit'>Approve</button>
                                        <button name='cancelBtn' type='submit'>Decline</button>
                                    </form>
                                </div>
                            </div>
                        ";
                    }
                } else {
                    echo "<p>No pending bookings at the moment.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>
