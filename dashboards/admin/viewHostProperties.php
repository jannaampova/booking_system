<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: ../../userEntry/logIn.php");
    exit();
}
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host's Properties</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../../css/viewProp.css" />
    <link rel="stylesheet" href="../../css/admin.css" />
    <link rel="stylesheet" href="../../css/buttonAndSelect.css" />
    <link rel="stylesheet" href="../../css/displayImages.css" />
    <link rel="stylesheet" href="../../css/nav.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>

    <style>
        .left-container {
            width: 200px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #333;
            z-index: 10;
        }


        .cont {
            margin-left: 20% !important;
            margin-top: 10% !important;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            width: 100%;
            margin-top: 3%;
        }

        .details {
            padding: 10px !important;
            border-radius: 6px !important;
            background-color: #688587a2 !important;
        }

        .details h3 {
            margin-top: 0;
            font-size: 1.5rem;
            color: #ffffff;
            text-shadow: none !important;
        }

        .details p {
            color: #ffffff !important;
        }
    </style>
</head>

<body>
    <div class="left-container">
        <div class="options">
            <?php
            include "../../config.php";
            $firstName = explode(' ', $_SESSION['name'])[0]; // Get the first name
            $host = $_GET['hostID'];
            $hostName = mysqli_fetch_assoc(mysqli_query($dbConn, "SELECT fullName FROM User WHERE id = $host"))['fullName'];
            ?>
            <a href="../userSettings.php">
                <i class="fas fa-user-edit"></i>
                <?php echo htmlspecialchars($firstName); ?>
            </a>
            <a href="adminBoard.php">Dashboard</a>
            <a href="hostDetails.php?role=2">Host Details</a>
            <a href="logOut.php">Log Out <i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </div>

    <section class="service-section">
        <div class="row">
            <div class="section-title">
            <h1>   <?php echo htmlspecialchars($hostName); ?>
                Properties</h1>
            </div>
        </div>
        <div class="cont">
            <?php
            include '../client/viewFunction.php';

            $sql = "SELECT Property.id AS propertyID, Property.propName, Property.pricePerNight AS price, 
                                   PropertyType.propType, GuestNumber.guestNum, User.fullName, City.city
                            FROM Property
                            JOIN City ON Property.cityID = City.id
                            JOIN User ON Property.hostID = User.id
                            JOIN GuestNumber ON Property.guestNumID = GuestNumber.id
                            JOIN PropertyType ON Property.propTypeID = PropertyType.id
                            WHERE Property.hostID = ?";

            $stmt = $dbConn->prepare($sql);
            $stmt->bind_param("i", $host);
            $stmt->execute();
            $result = $stmt->get_result();
            $source = "viewHostProperties";
            view($result, $source);
            ?>
        </div>
    </section>
</body>

</html>