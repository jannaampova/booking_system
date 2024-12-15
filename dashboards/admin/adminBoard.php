<?php
session_start();

if (!isset($_SESSION['name'])) {
    header("Location: ../../userEntry/logIn.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <style>
        footer {
            background-color: #08242100;
            color: #000000;
            text-align: center;
            padding: 20px 0;
            position: relative;
            margin-left: 50%;
            bottom: 0;
            width: 100%;
            user-select: none;
        }
        .left-container {
            width: 15%;
            height: 100vh;
            position: fixed;
        }

        .info-bubbles {
            width: 108%;
            margin-top: 3%;
            margin-left: 50%;
            margin-bottom: 0;
            padding: 27px;
        }

        .info-bubble {
            margin-top: 1%;
            margin-left: 2%;
            margin-bottom: 1%;
        }

        header {
            margin-top: 0;
            margin-left: 85%;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="left-container">
            <div class="options">
                <?php
                $firstName = explode(' ', $_SESSION['name'])[0]; 
                ?>
                <a href="../userSettings.php">
                    <i class="fas fa-user-edit"></i>
                    <?php echo htmlspecialchars($firstName); ?>
                </a>

                <a href='seeUsers.php'>View Users</a>
                <a href='viewActivity.php'>View Activities</a>
                <a href='addAdmin.php'>Add new admin</a>
                <a href='logOut.php'>Log Out <i class="fa-solid fa-right-from-bracket"></i></a>

            </div>
        </div>
        <div class="column">
            <div class="first-line">
                <header>
                    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
                </header>
            </div>

            <div class="info-bubbles">
                <div class="info-bubble">
                    <p><b>Properties</b> <br>
                        <?php
                        include "../../config.php";
                        $propertyCounter = 0;
                        $sql = "SELECT id FROM Property";
                        $res = mysqli_query($dbConn, $sql);
                        while ($row = mysqli_fetch_assoc($res)) {
                            $propertyCounter++;
                        }
                        echo "<i>$propertyCounter</i>";
                        ?>
                        <br>
                        <i class="fa-solid fa-house"></i>
                    </p>
                </div>
                <div class="info-bubble">
                    <p><b>Users</b> <br>
                        <?php
                        $userCounter = 0;
                        $sql = "SELECT id FROM User";
                        $res = mysqli_query($dbConn, $sql);
                        while ($row = mysqli_fetch_assoc($res)) {
                            $userCounter++;
                        }
                        echo "<i>$userCounter</i>";
                        ?>
                        <br>
                        <i class="fas fa-user"></i>
                    </p>

                </div>
                <div class="info-bubble">
                    <p><b>Bookings</b> <br>
                        <?php
                        $bookingsCounter = 0;
                        $sql = "SELECT id FROM Booking";
                        $res = mysqli_query($dbConn, $sql);
                        while ($row = mysqli_fetch_assoc($res)) {
                            $bookingsCounter++;
                        }
                        echo "<i>$bookingsCounter</i>";
                        ?>
                        <br>
                        <i class="fa-regular fa-calendar"></i>
                    </p>

                </div>
            </div>

            <div class="info-bubbles" style="background-color:#225e6fb9;
;
">
                <div class="info-bubble">
                    <p><b>Top Property</b> <br>
                        <?php
                        include "../../config.php";
                        $sql = "SELECT Property.propName, COUNT(*) as booking_count 
                                FROM Booking 
                                JOIN Property ON Booking.propID = Property.id 
                                GROUP BY propID 
                                ORDER BY booking_count DESC 
                                LIMIT 1";
                        $res = mysqli_query($dbConn, $sql);
                        if ($row = mysqli_fetch_assoc($res)) {
                            $mostBookedPropertyName = $row['propName'];
                            $bookings = $row['booking_count'];
                            echo "$mostBookedPropertyName<br>";
                            echo "<i style='font-size: 1 rem; color:#282e2a'>booked <b>$bookings</b> times</i>";

                        } else {
                            echo "<i>No bookings found</i>";
                        }
                        ?>
                        <br>
                        <i class="fa-solid fa-house"></i>
                        <br>

                    </p>
                </div>
                <div class="info-bubble">
                    <p><b>Top Host</b> <br>
                        <?php
                        $sql = "SELECT User.fullName, COUNT(*) as client_count 
                            FROM Booking 
                            JOIN Property ON Booking.propID = Property.id 
                            JOIN User ON Property.hostID = User.id 
                            GROUP BY User.id 
                            ORDER BY client_count DESC 
                            LIMIT 1";
                        $res = mysqli_query($dbConn, $sql);
                        if ($row = mysqli_fetch_assoc($res)) {
                            $mostBookedHostName = $row['fullName'];
                            $bookings = $row['client_count'];
                            echo "$mostBookedHostName<br>";
                            echo "<i style='font-size: 1 rem; color:#282e2a'>booked <b>$bookings</b> times</i>";
                        } else {
                            echo "<i>No hosts found</i>";
                        }
                        ?>
                        <br>
                        <i class="fas fa-user"></i>
                    </p>

                </div>
                <div class="info-bubble">
                    <p><b>Top Client</b> <br>
                        <?php
                        $sql = "SELECT User.fullName, COUNT(*) as booking_count
                        FROM Booking
                        JOIN User ON Booking.clientID = User.id
                        GROUP BY User.id
                        ORDER BY booking_count DESC
                        LIMIT 1";

                        $res = mysqli_query($dbConn, $sql);

                        if ($res) {
                            if ($row = mysqli_fetch_assoc($res)) {
                                $mostFrequentClient = $row['fullName'];
                                $bookings = $row['booking_count'];
                                echo "$mostFrequentClient<br>";
                                echo "<i style='font-size: 1 rem; color:#282e2a'>made <b>$bookings</b> bookings</i>";
                            } else {
                                echo "<i>No clients found</i>";
                            }
                        } else {
                            echo "Error: " . mysqli_error($dbConn);
                        }
                        ?>
                        <br>
                        <i class="fa-regular fa-calendar"></i>
                    </p>

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


</body>

</html>