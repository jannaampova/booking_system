<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['name']) && !isset($_SESSION['userID'])) {
    header("Location: ../../userEntry/logIn.php"); // Redirect to login if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
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

        h2, h3 {
            margin-top: 0;
            color: #222;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .property-info {
            width: 250px;
            height: 250px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(7, 0, 8, 0.5);
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
            margin-bottom: 7px;
            line-height: 2.0;
        }

        .button {
            background-color: #688587a2;
            text-decoration: none;
            color: white;
            border: none;
            padding: 7px 7px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10% auto;
            position: relative;
        }

        .button:hover {
            background-color: #2b3a3ba2;
        }

        .button:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
            transform: translateY(-10px);
        }

        .tooltip-text {
            visibility: hidden;
            opacity: 0;
            width: 80%;
            background-color:rgba(47, 56, 57, 0.72);
            color: #FFF;
            text-align: center;
            border-radius: 5px;
            top:100%;
            padding: 10px;
            position: absolute;
            transform: translateX(-50%);
            transition: opacity 1s ease, transform 1s ease;
            z-index: 10;
            font-size: 10px;
        }

        .tooltip-text::after {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 50%;
            margin-left: -5px;
            border-style: solid;
            border-color: #333 transparent transparent transparent;
        }

        .main {
            width: 120%;
        }
    </style>
</head>

<body>
    <?php include "../../config.php"; ?>

    <div class="main">
        <div class="left-container">
            <div class="options">
                <?php
                $firstName = explode(' ',  $_SESSION['name'])[0]; // Get the first name
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
                    p.id AS propID,
                    b.id AS bookingID,
                    b.fromDate, 
                    b.toDate, 
                    b.totalPrice, 
                    b.bookingStatus,
                    pmt.paymentMethod as pmtMethod,
                    pmt.paymentStatus as pmtStatus
                FROM Booking b
                JOIN Property p ON b.propID = p.id
                JOIN Payment pmt on b.id=pmt.bookingID
                JOIN GuestNumber g ON p.guestNumID = g.id
                JOIN User u ON p.hostID = u.id
                WHERE b.clientID = {$_SESSION['userID']}";

                $res = mysqli_query($dbConn, $sqlBookings);
                if(mysqli_num_rows($res)>0){
                    
                while ($row = mysqli_fetch_assoc($res)) {
                    $propID = $row['propID'];
                    $bookID = $row['bookingID'];
                    $bookingStatus = $row['bookingStatus'];
                    $pmtStatus = $row['pmtStatus'];
                    $pmtMethod = $row['pmtMethod'];
                    $totalPrice = $row['totalPrice'];
                ?>
                    <div class="property-details">
                        <div class="property-info">
                            <h3><?php echo htmlspecialchars($row['propName']); ?></h3>
                            <p>Host: <?php echo htmlspecialchars($row['hostName']); ?></p>
                            <p>Guests: <?php echo htmlspecialchars($row['guestNum']); ?></p>
                        </div>
                        <div class="property-info">
                            <h3>Booking Details</h3>
                            <p><?php echo htmlspecialchars($row['fromDate']) . " to " . htmlspecialchars($row['toDate']); ?></p>
                            <p>Total Price: $<?php echo htmlspecialchars($row['totalPrice']); ?></p>
                            <p>Status: <?php echo htmlspecialchars($row['bookingStatus']); ?></p>
                            <p>Payment method: <?php echo htmlspecialchars($row['pmtMethod']); ?></p>
                            <p>Payment status: <?php echo htmlspecialchars($row['pmtStatus']); ?></p>
                        </div>
                        <div class="property-info">
                            <form>
                                <?php if ($bookingStatus === 'approved' || $bookingStatus === 'pending') : ?>
                                    <a class="button" href="cancelBooking.php?propid=<?php echo htmlspecialchars($propID); ?>&bookId=<?php echo htmlspecialchars($bookID); ?>">
                                        Cancel Booking
                                        <span class="tooltip-text">If you cancel less than 3 days prior, you'll be charged 30% of the total amount!</span>
                                    </a>
                                <?php elseif ($bookingStatus === 'cancelled') : ?>
                                    <a class="button" style="background-color: red; pointer-events: none; cursor: default;">Cancelled Booking</a>
                                <?php else : ?>
                                    <a class="button" style="background-color: grey; pointer-events: none; cursor: default;">Declined Booking</a>
                                <?php endif; ?>
                                <a class="button" href="browseSingle.php?id=<?php echo htmlspecialchars($propID); ?>">View Property</a>
                                <?php if ($bookingStatus === 'approved' && $pmtStatus==='pending' && $pmtMethod==='card' ) : ?>
                                    <a class="button" href="payment.php?totalPrice=<?php echo htmlspecialchars($totalPrice); ?>&bookId=<?php echo htmlspecialchars($bookID); ?>">Make Payment</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                <?php }
                }else{
                echo"<p>You haven't made any bookings!</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>
