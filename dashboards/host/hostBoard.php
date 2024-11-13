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
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <style>
        .section a {

            color: #00272e;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="left-container">
        </div>
        <div class="column">
            <div class="first-line">
                <header>
                    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>.</h1> <!-- Display admin name -->
                </header>
                <div class="section">
                    <nav>
                        <?php
                        $fullName = $_SESSION['name'];
                        $firstName = explode(' ', $fullName)[0]; // Get the first name
                        ?>
                        <a href="hostSettings.php">
                            <i class="fas fa-user-edit"></i>
                            <?php echo htmlspecialchars($firstName); ?>
                        </a>
                        <a href='logOut.php'>Log Out</a>
                    </nav>
                </div>
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

        </div>

    </div>


</body>

</html>