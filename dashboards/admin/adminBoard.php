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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>

</head>

<body>
    <div class="main">
        <div class="left-container">
            <div class="options">
                <?php
                $fullName = $_SESSION['name'];
                $firstName = explode(' ', $fullName)[0]; // Get the first name
                ?>
                <a href="hostSettings.php">
                    <i class="fas fa-user-edit"></i>
                    <?php echo htmlspecialchars($firstName); ?>
                </a>

                <a href='seeUsers.php'>View Users</a>
                <a href='seeUsers.php'>View Users</a>
                <a href='logOut.php'>Log Out <i class="fa-solid fa-right-from-bracket"></i></a>

            </div>
        </div>
        <div class="column">
            <div class="first-line">
                <header>
                    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1> <!-- Display admin name -->
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

        </div>

    </div>


</body>

</html>