<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: ../../userEntry/logIn.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$fullName = $_SESSION['name'];
$firstName = explode(' ', $fullName)[0]; // Get the first name
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($firstName); ?>'s Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <link rel="stylesheet" href="../../css/allBookings.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <STYle>
        footer {
    background-color: #08242100;
    color: #000000;
    text-align: center;
    padding: 20px 0;
    position: relative;
    margin-left:30%;
    bottom: 0;
    width: 100%;
    user-select: none; 
  
  }
        .inner-flex-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 500px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #222;
            margin: 0;
        }

        .property-details h3 {
            color: orange;
            margin: 0;
        }

        form {
            margin: 0;
        }

        select {
            padding: 5px;
            font-size: 14px;
        }

        .left-container {
            width: 15%;
            height: 100vh;
            position: fixed;
        }

        .property-info {
            margin-bottom: 25px;
            padding: 0;
            border-bottom: 1px solid #eee;
        }

        .info-bubbles {
            margin-top: 3%;
            margin-left: 35%;
            width: 90%;
        }

        .detail {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            width: 1050px;
        }

        .inner-flex {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-top: 5%;
            margin-bottom: 5%;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-left: 30%;
            width: 100%;

        }

        header {
            text-wrap: nowrap;
            font-family: 'Poppins', sans-serif;
            margin-left: 55%;
            text-align: center;
            width: 100%;
        }


        .property-details {
            width: 100%;
            font-family: 'Poppins', sans-serif;
            color: #444;
            line-height: 1.8;
            font-size: 18px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 15px;
            background-color: #fff;
            margin-top: 5%;
        }
    </STYle>
</head>

<body>
    <div class="main">
        <div class="left-container">
            <div class="options">
                <?php
                $fullName = $_SESSION['name'];
                $firstName = explode(' ', $fullName)[0]; // Get the first name
                ?>
                <a href="../userSettings.php">
                    <i class="fas fa-user-edit"></i>
                    <?php echo htmlspecialchars($firstName); ?>
                </a>
                <a href="viewProperties.php">View Your Properties</a>
                <a href="addProperty.php">Add Property</a>
                <a href="hostPendingBookings.php">Pending bookings</a>
                <a href="allBookings.php">All bookings</a>
                <a href='../admin/logOut.php'>Log Out <i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>
        <div class="column">
            <div class="first-line">
                <header>
                    <h1 class="h1">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
                </header>

            </div>

            <div class="info-bubbles">
                <div class="info-bubble">
                    <p><b>You own</b><br>
                        <?php
                        include "../../config.php"; // Include the database connection
                        $propertyCounter = 0;
                        $fullName = mysqli_real_escape_string($dbConn, $_SESSION['name']); // Escape the session value
                        $sql = "SELECT COUNT(Property.id) AS propertyCount
                              FROM Property
                              JOIN User ON Property.hostID = User.id
                              WHERE User.fullName = '$fullName'";

                        $res = mysqli_query($dbConn, $sql);
                        if ($row = mysqli_fetch_assoc($res)) {
                            $propertyCounter = $row['propertyCount'];
                        }
                        echo "$propertyCounter properties.";
                        ?>
                        <br>
                        <i class="fa-solid fa-house"></i>
                    </p>
                </div>

                <div class="info-bubble">
                    <p><b>You were booked</b><br>
                        <?php
                        include "../../config.php"; // Include the database connection
                        
                        $bookingCounter = 0;
                        $fullName = mysqli_real_escape_string($dbConn, $_SESSION['name']); // Escape the session value
                        
                        $sql = "SELECT COUNT(Booking.id) AS bookingCount
                               FROM Booking
                               JOIN Property ON Booking.propID = Property.id
                               JOIN User ON Property.hostID = User.id
                               WHERE User.fullName = '$fullName'";

                        $res = mysqli_query($dbConn, $sql);

                        if ($row = mysqli_fetch_assoc($res)) {
                            $bookingCounter = $row['bookingCount'];
                        }

                        echo "$bookingCounter times.";

                        ?>
                        <br>
                        <i class="fa-regular fa-calendar"></i>
                    </p>
                </div>
                <div class="info-bubble">
                    <p><b>You had</b><br>
                        <?php
                        include "../../config.php"; // Include the database connection
                        
                        $clientCounter = 0;
                        $fullName = mysqli_real_escape_string($dbConn, $_SESSION['name']); // Escape the session value
                        
                        $sql = "SELECT COUNT(DISTINCT Booking.clientID) AS clientCount
                         FROM Booking
                         JOIN Property ON Booking.propID = Property.id
                         JOIN User ON Property.hostID = User.id
                         WHERE User.fullName = '$fullName'";

                        $res = mysqli_query($dbConn, $sql);

                        if ($row = mysqli_fetch_assoc($res)) {
                            $clientCounter = $row['clientCount'];
                        }

                        echo "$clientCounter clients.";

                        ?>
                        <br>
                        <i class="fas fa-user"></i>
                    </p>
                </div>
            </div>
            <div class="inner-flex">
                <div class="inner-flex-header">
                    <h2 class="section-title">Upcoming Bookings this month</h2>
                    <form method="POST">
                        <label for="sortBy">Sort By Date:</label>
                        <select name="sortBy" id="sortBy" onchange="this.form.submit()">
                            <option value="">Select Filter</option>
                            <option value="asc" <?php echo (isset($_POST['sortBy']) && $_POST['sortBy'] === 'asc') ? 'selected' : ''; ?>>Earliest</option>
                            <option value="desc" <?php echo (isset($_POST['sortBy']) && $_POST['sortBy'] === 'desc') ? 'selected' : ''; ?>>Latest</option>
                        </select>
                    </form>
                </div>
                <div class="detail">
                    <?php
                    include "../../config.php";

                    $startDate = date('Y-m-01');
                    $endDate = date('Y-m-t');

                    $sqlBookings = "SELECT 
                        p.propName AS propName,
                        g.guestNum AS guestNum,
                        u.fullName AS clientName,
                        p.id as propID,
                        b.id as bookingID,
                        b.fromDate, 
                        b.toDate, 
                        b.totalPrice, 
                        b.bookingStatus as bookingStatus
                    FROM Booking b
                    JOIN User u ON b.clientID = u.id
                    JOIN Property p ON b.propID = p.id
                    JOIN GuestNumber g ON p.guestNumID = g.id
                    WHERE p.hostID = {$_SESSION['userID']} 
                    AND b.fromDate >= '$startDate' 
                    AND b.toDate <= '$endDate'
                    AND b.bookingStatus='approved'";

                    if (isset($_POST['sortBy'])) {
                        $sortBy = $_POST['sortBy'] ?? '';
                        switch ($sortBy) {
                            case 'asc':
                                $sqlBookings .= " ORDER BY b.fromDate ASC";
                                break;
                            case 'desc':
                                $sqlBookings .= " ORDER BY b.fromDate DESC";
                                break;
                        }
                    } else {
                        $sqlBookings .= " ORDER BY b.fromDate ASC";
                    }

                    $sqlBookings .= " LIMIT 10";
                    $res = mysqli_query($dbConn, $sqlBookings);

                    if (mysqli_num_rows($res) > 0) {
                        while ($row = mysqli_fetch_assoc($res)) {
                            echo "
                            <div class='property-details'>
                                <h3>" . htmlspecialchars($row['propName']) . "</h3>
                                <p><b>Client:</b> " . htmlspecialchars($row['clientName']) . "</p>
                                <p><b>Check-in date:</b> " . htmlspecialchars($row['fromDate']) . "</p>
                                <p><b>Check-out date:</b> " . htmlspecialchars($row['toDate']) . "</p>
                                <p><b>Total Price:</b> $" . htmlspecialchars($row['totalPrice']) . "</p>
                                <p><b>Booking status:</b> <i style='color:green'>" . strtoupper(htmlspecialchars($row['bookingStatus'])) . "</i></p>
                            </div>
                            ";
                        }
                    } else {
                        echo "<p>No upcoming bookings this month.</p>";
                    }
                    ?>
                </div>
            </div>
            <footer>
                <div class="footer-content">
                    <p>&copy; 2024 TJ EasyStay.</p>
                    <ul class="socials">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                    </ul>
                </div>
            </footer>
        </div>

    </div>



    </div>


    </div>



</body>

</html>