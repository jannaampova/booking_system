<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['name']) && !isset($_SESSION['userID'])) {
    header("Location: ../../userEntry/logIn.php"); // Redirect to login if not logged in
    exit();
}

?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Single Property</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../../css/viewSingle.css" />
    <link rel="stylesheet" href="../../css/buttonAndSelect.css" />
    <link rel="stylesheet" href="../../css/addEditAdminHost.css" />
    <link rel="stylesheet" href="../../css/propInfo.css" />
    <link rel="stylesheet" href="../../css/admin.css" />
    <link rel="stylesheet" href="../../css/nav.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <style>
        .detail {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 15px;
            width: 1050px;
        }

        .property-details {
            display: flex;
            flex-direction: row;
            gap: 50px;
            width: 100%;
            font-family: 'Roboto', Arial, sans-serif;
            color: #444;
            line-height: 1.8;
            font-size: 16px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
        }

        h2,
        h3 {
            margin-top: 0;
            color: #222;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .property-info {
            width: 250px;
            /* Set a fixed width */
            height: 250px;
            /* Set a fixed height */
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(7, 0, 8, 0.499);
            border-radius: 35px;
            border-color: black;
            margin-left: 5%;
            margin-bottom: 25px;
            padding: 20px 30px;
            border-bottom: 1px solid #eee;
        }



        .property-info p {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
            line-height: 2.5;
        }



        .button {
            background-color: #688587a2;
            text-decoration: none;
            color: white;
            border: none;
            padding: 10px 10px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20% auto;

        }

      

        .button:hover {
            background-color: #2b3a3ba2;

        }

        .main {
            width: 120%;
        }
    </style>
</head>

<body>
    <?php
    include "../../config.php";

    ?>

    <div class="main">
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
                <a href="clientBoard.php">Dashboard</a>
                <a href="../admin/logOut.php">Log Out <i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>

        <div class="inner-flex">
            <div class="detail">
                <?php
                $sqlBookings = "SELECT 
             p.propName AS propName,
             g.guestNum AS guestNum,
             u.fullName AS hostName,
             p.id as propID,
             b.id as bookingID,
             b.fromDate, 
             b.toDate, 
             b.totalPrice, 
             b.bookingStatus
         FROM Booking b
         JOIN Property p ON b.propID = p.id
         JOIN GuestNumber g ON p.guestNumID = g.id
         JOIN User u ON p.hostID = u.id
         WHERE b.clientID = {$_SESSION['userID']}";

                $res = mysqli_query($dbConn, $sqlBookings);
                while ($row = mysqli_fetch_assoc($res)) {
                    $propID = $row['propID'];
                    $bookID = $row['bookingID'];
                    $bookingStatus = $row['bookingStatus'];
                    echo "<div class='property-details'>
        <div class='property-info'>
            <h3>" . htmlspecialchars($row['propName']) . "</h3>
            <p>Host: " . htmlspecialchars($row['hostName']) . "</p>
            <p>Guests: " . htmlspecialchars($row['guestNum']) . "</p> 
        </div>
        <div class='property-info'>  
            <h3>Booking Details</h3>
            <p>" . htmlspecialchars($row['fromDate']) . " to " . htmlspecialchars($row['toDate']) . "</p>
            <p>Total Price: $" . htmlspecialchars($row['totalPrice']) . "</p>
            <p>Status: " . htmlspecialchars($row['bookingStatus']) . "</p>
        </div>
        <div class='property-info'>";
                    ?>
                    <form name='form'>
                        <?php if ($bookingStatus === 'approved' || $bookingStatus === 'pending'): ?>
                            <a class='button'
                                href='cancelBooking.php?propid=<?= htmlspecialchars($propID) ?>&bookId=<?= htmlspecialchars($bookID) ?>'>Cancel
                                Booking</a>
                        <?php elseif ($bookingStatus === 'cancelled'): ?>
                            <p><a href="#"  class='button' style="background-color: red;pointer-events: none;cursor:arrow;">Cancelled booking</a></p>
                            <?php else:?>
                                <p><a href="#"  class='button' style="background-color: grey;pointer-events: none;cursor:arrow;">Declined booking</a></p>
                       <?php endif; ?>
                        <a class='button' href='browseSingle.php?id=<?= htmlspecialchars($propID) ?>'>View Property</a>
                    </form>

                </div>
            </div>
        <?php } ?>
    </div>
</body>

</html>