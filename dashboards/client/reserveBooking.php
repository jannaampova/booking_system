<!DOCTYPE html>
<html>

<head>
    <title>Request Booking</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap">
    <link rel="stylesheet" href="../../css/logIn.css">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/addEditAdminHost.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <link rel="stylesheet" href="../../css/buttonAndSelect.css">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <style>
        .form-control label {
            font-weight: 600;
            color: #333;
            margin-right: 10px;
        }

        .form-control input,
        .form-control select,
        .form-control textarea {
            box-shadow: 0px 0px 0px 0px #192d2d66;
            color: white;
        }

        .radio-flex {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 50px;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .radio-input {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid #888;
            appearance: none;
            cursor: pointer;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .radio-input:checked {
            background-color: #2b3a3ba2;

        }

        .radio-label {
            font-size: 14px;
            text-wrap: nowrap;
            color: #333;
            cursor: pointer;
            font-weight: 600;
        }
    </style>
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

            <a href='yourBookings.php'>Bookings</a>
            <a href='clientBoard.php'>Dashboard</a>
            <a href='../admin/logOut.php'>Log Out <i class="fa-solid fa-right-from-bracket"></i></a>

        </div>
    </div>
    <?php

    include '../../config.php';
    include '../../userEntry/functions.php';
    ob_start();

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
                    <div class="radio-flex">
                        <div class="radio-item">
                            <input type="radio" name="payment" id="code" value="card" class="radio-input">
                            <label class="radio-label" for="code">Pay via confirmation code</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" name="payment" id="prop" value="cash" class="radio-input">
                            <label class="radio-label" for="prop">Pay on property</label>
                        </div>
                    </div>



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
                    <button type="submit" name="confirmBooking" style="color:white;">Request Booking</button>
                </div>
            </form>
        </div>
        <?php
    } else {
        echo "User not found.";
    }



    if (isset($_POST['confirmBooking'])) {
        // Sanitize and validate input data
        $paymentMethod = mysqli_real_escape_string($dbConn, $_POST['payment']);
        $userId = intval($userId); // Assuming $userId is retrieved from the session
        $propertyId = intval($propertyId); // Assuming $propertyId is passed in context
        $checkInDate = mysqli_real_escape_string($dbConn, $checkInDate);
        $checkOutDate = mysqli_real_escape_string($dbConn, $checkOutDate);
        $totalPrice = floatval($totalPrice); // Assuming $totalPrice is calculated earlier
    
        // Start transaction
        mysqli_begin_transaction($dbConn);
    
        try {
            // Insert booking
            $sql = "INSERT INTO Booking (clientID, propID, bookingStatus, fromDate, toDate, totalPrice) 
                    VALUES ('$userId', '$propertyId', 'pending', '$checkInDate', '$checkOutDate', '$totalPrice')";
            if (!mysqli_query($dbConn, $sql)) {
                throw new Exception("Error inserting booking: " . mysqli_error($dbConn));
            }
    
            // Retrieve booking ID and price for payment
            $selectForPayment = "SELECT id, totalPrice FROM Booking 
                                 WHERE propID = '$propertyId' AND fromDate = '$checkInDate' AND toDate = '$checkOutDate'";
            $resPay = mysqli_query($dbConn, $selectForPayment);
            if (!$resPay || mysqli_num_rows($resPay) == 0) {
                throw new Exception("Error fetching booking details: " . mysqli_error($dbConn));
            }
            $rowPay = mysqli_fetch_assoc($resPay);
            $bookId = $rowPay['id'];
            $priceToPay = $rowPay['totalPrice'];
    
            // Insert payment
            $paySql = "INSERT INTO Payment (bookingID, paymentStatus, paymentMethod, amount) 
                       VALUES ('$bookId', 'pending', '$paymentMethod', '$priceToPay')";
            if (!mysqli_query($dbConn, $paySql)) {
                throw new Exception("Error inserting payment: " . mysqli_error($dbConn));
            }
    
            // Insert reserved dates
            $insertReservedDates = "INSERT INTO Availabilities (fromDate, toDate, propStatus, propID)
                                    VALUES ('$checkInDate', '$checkOutDate', 'reserved', '$propertyId')";
            if (!mysqli_query($dbConn, $insertReservedDates)) {
                throw new Exception("Error inserting reserved dates: " . mysqli_error($dbConn));
            }
    
            // Commit transaction
            mysqli_commit($dbConn);
            header("Location: clientBoard.php");
            exit();
        } catch (Exception $e) {
            // Rollback transaction in case of error
            mysqli_rollback($dbConn);
            echo "Booking process failed: " . $e->getMessage();
        }
    }
    ob_end_flush(); // Sends "Hello, World!" after header

    ?>
    

</body>

</html>