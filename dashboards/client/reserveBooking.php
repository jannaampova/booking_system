<!DOCTYPE html>
<html>

<head>
    <title>Confirm Booking</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap">
    <link rel="stylesheet" href="../../css/logIn.css">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/addEditAdminHost.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <link rel="stylesheet" href="../../css/buttonAndSelect.css">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
</head>
<?php
session_start(); // Start the session
if (!isset($_SESSION['name']) && !isset($_SESSION['userID'])) {
    header("Location: ../../userEntry/logIn.php"); // Redirect to login if not logged in
    exit();
}
$checkInDate = isset($_GET['checkIN']) ? $_GET['checkIN'] : null;
$checkOutDate = isset($_GET['checkOUT']) ? $_GET['checkOUT'] : null;
$propertyId = isset($_GET['propertyID']) ? $_GET['propertyID'] : null;
$userId = isset($_GET['id']) ? $_GET['id'] : null;
?>

<body>


    <div class="left-container">
        <div class="options">
            <?php
            $fullName = $_SESSION['name'];
            $firstName = explode(' ', $fullName)[0]; // Get the first name
            ?>
            <a href="../userSettings.php">
                <i class="fas fa-user-edit"></i>
                <?php echo htmlspecialchars($_SESSION['name']); ?>
            </a>

            <a href='bookings.php'>View Users</a>
            <a href='clientBoard.php'>Dashboard</a>
            <a href='../admin/logOut.php'>Log Out <i class="fa-solid fa-right-from-bracket"></i></a>

        </div>
    </div>
    <?php

    include '../../config.php';
    include '../../userEntry/functions.php';

    // Fetch user details from the database
    $sql = "SELECT * FROM User WHERE id = '$userId'";
    $result = mysqli_query($dbConn, $sql);

    if ($result && $user = mysqli_fetch_assoc($result)) {
        // Display the user's current data in a form for editing
        ?>
        <div class="container">
            <form class='form' method="POST">

                <div class="form-control"> <label>Full Name:</label>
                    <input type="text" name="fullName" value="<?php echo htmlspecialchars($user['fullName']); ?>"
                        required><br>
                </div>
                <div class="form-control"> <label>Phone:</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required><br>
                </div>

                <div class="form-control"> <label>Email:</label>
                    <input readonly type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                        required><br>
                </div>
                <div class="form-control"> <label>Check In:</label>
                    <input type="text" name="checkIn" readonly value="<?php echo htmlspecialchars($checkInDate); ?>"
                        required><br>
                </div>
                <div class="form-control"> <label>Check Out:</label>
                    <input type="text" name="checkOut" readonly value="<?php echo htmlspecialchars($checkOutDate); ?>"
                        required><br>
                </div>
                <div class="form-control">
                    <label>Total Price:</label>
                    <?php
                    // SQL query for fetching role name
                    $sql2 = "SELECT pricePerNight FROM Property WHERE id= '$propertyId'";
                    $res2 = mysqli_query($dbConn, $sql2);
                    $row = mysqli_fetch_assoc($res2);
                    $price = $row['pricePerNight'];
                    $date1 = new DateTime($checkInDate);
                    $date2 = new DateTime($checkOutDate);
                    $interval = $date1->diff($date2);
                    $totalPrice = $interval->days * $price;

                    ?>
                    <input type="text" name="price" value="<?php echo htmlspecialchars($totalPrice); ?>"><br>
                </div>
                <div class="form-control">
                    <button type="submit" name="confirmBooking">Request Booking</button>
                </div>
            </form>
        </div>
        <?php
    } else {
        echo "User not found.";
    }



    if (isset($_POST['confirmBooking'])) {
        $sql = "INSERT INTO Booking (clientID,propID,bookingStatus,fromDate,toDate,totalPrice) 
            VALUES ('$userId','$propertyId','pending','$checkInDate','$checkOutDate','$totalPrice')";
        $res = mysqli_query($dbConn, $sql);
        if (!$res) {
            echo "Unable to rquest booking!";
        } else {
            header("Location: clientBoard.php");
        }
        $insertReservedDates = "INSERT INTO Availabilities (fromDate, toDate, propStatus, propID)
        VALUES ('$checkInDate', '$checkOutDate', 'reserved', $propertyId)";
        mysqli_query($dbConn, $insertReservedDates);
    }
    ?>

</body>

</html>