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
    <title>Client</title>
    <link rel="stylesheet" href="../../css/client.css">
    <link rel="stylesheet" href="../../css/userOptions.css">
    <link rel="stylesheet" href="../../css/displayImages.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <style>
        
.section nav {
  display: flex;
  justify-content: flex-end;
  align-self: flex-end;
  padding: 10px;
  border-radius: 10px;
  width: fit-content;
  margin: 0;
  margin-bottom: 20px;
  position: fixed; 
  top: 10px; 
  right: 10px; 
}

.section a,
.options a{
  padding: 9px 15px;
  border-radius: 15px;
  text-decoration: none;
  color: antiquewhite;
  margin: 5px;
  transition: background-color 0.3s ease;
}

.section a:hover,
.options a:hover {
  background-color: #2b3a3ba2;
}

    </style>
</head>

<body>
    <div class="main" id="main">
        <div class="icon">
            <a href="clientBoard.php" class="logo">
                <h3>TJ</h3>
                <p>
                    <h6>EasyStay</h6>
                </p>
            </a>
        </div>

        <div class="section">

            <nav>
                <?php
                $fullName = $_SESSION['name'];
                $firstName = explode(' ', $fullName)[0];
                ?>
                <a href="../userSettings.php">
                    <i class="fas fa-user-edit"></i>
                    <?php echo htmlspecialchars($firstName); ?>
                </a>
                <a href='../admin/logOut.php'>Log Out</a>
            </nav>

            <header>

                <h1> Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>.</h1>
                <form method="post" class="search-bubble">
                    <div class="search-field">
                        <input type="search" name="city" placeholder="Where?" list="city-options">
                        <datalist id="city-options">
                            <option value="Varna"></option>
                            <option value="Bansko"></option>
                            <option value="Blagoevgrad"></option>
                            <option value="Pleven"></option>
                            <option value="Plovdiv"></option>
                            <option value="Ruse"></option>
                            <option value="Sandanski"></option>
                            <option value="Shumen"></option>
                            <option value="Sofia"></option>
                            <option value="Targovishte"></option>
                            <option value="Veliko Tarnovo"></option>
                        </datalist>

                    </div>
                    <div class="search-field">
                        <input name="checkIn" type="text" placeholder="Check In" onfocus="(this.type='date')">
                    </div>
                    <div class="search-field">
                        <input name="checkOut" type="text" placeholder="Check Out" onfocus="(this.type='date')">
                    </div>
                    <div class="search-field">
                        <input name="guests" type="search" placeholder="Travelers">
                    </div>
                    <button type="submit" name="search"><i class="fas fa-search"></i></button>
                </form>

            </header>



        </div>

        <section class="service-section">
            <div class="cont">
                <?php
                include '../../config.php';
                include 'viewFunction.php';


                if (isset($_POST['search'])) {
                    $city = $_POST['city'];
                    $checkIn = $_POST['checkIn'];
                    $checkOut = $_POST['checkOut'];
                    $guests = $_POST['guests'];
                    $conditions = [];
                    if (!empty($city)) {
                        $conditions[] = "City.city= '" . mysqli_real_escape_string($dbConn, $city) . "'";

                    }
                    if (!empty($checkIn) && !empty($checkOut)) {
                        $searchIn = new DateTime($checkIn);
                        $searchOut = new DateTime($checkOut);

                        // Ensure the check-in is before the check-out
                        if ($searchIn <= $searchOut) {
                            $checkIn = mysqli_real_escape_string($dbConn, $searchIn->format('Y-m-d'));
                            $checkOut = mysqli_real_escape_string($dbConn, $searchOut->format('Y-m-d'));

                            // SQL to find available properties
                            $sql = "
                                SELECT Property.id AS propertyID, Property.propName, City.city
                                FROM Property
                                JOIN City ON Property.cityID = City.id
                                JOIN Availabilities ON Property.id = Availabilities.propID
                                WHERE 
                                    Availabilities.propStatus = 'free'
                                    AND Availabilities.fromDate <= '$checkIn'
                                    AND Availabilities.toDate >= '$checkOut'
                                    AND Property.id NOT IN (
                                        SELECT propID
                                        FROM Booking
                                        WHERE 
                                            bookingStatus = 'approved'
                                            AND (
                                                (fromDate <= '$checkIn' AND toDate >= '$checkIn') OR
                                                (fromDate <= '$checkOut' AND toDate >= '$checkOut') OR
                                                (fromDate >= '$checkIn' AND toDate <= '$checkOut')
                                            )
                                    )
                            ";

                            $res = mysqli_query($dbConn, $sql);
                            if ($res) {
                                view($res);
                            } else {
                                echo "Error: " . mysqli_error($dbConn);
                            }
                        } else {
                            echo "Invalid date range: Check-in date must be before the check-out date.";
                        }
                    }


                    if ($guests != "") {
                        $conditions[] = "GuestNumber.guestNum= '" . mysqli_real_escape_string($dbConn, $guests) . "'";

                    }


                    $sql = "SELECT Property.id AS propertyID, Property.propName, City.city
                    FROM Property
                    JOIN City ON Property.cityID = City.id
                    JOIN GuestNumber on Property.guestNumID=GuestNumber.id";
                    if (!empty($conditions)) {
                        $sql .= " WHERE " . implode(' AND ', $conditions);
                    }

                    $res = mysqli_query($dbConn, $sql);
                    view($res);
                }
                ?>
            </div>
        </section>
    </div>
</body>

</html>