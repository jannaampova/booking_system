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
    <link rel="stylesheet" href="../../css/nav.css">
    <link rel="stylesheet" href="../../css/displayImages.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
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
                <a href="hostSettings.php">
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
                    if ($city != "") {
                        $sql = "SELECT Property.id AS propertyID, Property.propName, City.city
            FROM Property
            JOIN City ON Property.cityID = City.id
            WHERE City.city = '$city'";
                        $res = mysqli_query($dbConn, $sql);
                        view($res);

                    }
                    if ($checkIn != "") {


                        if ($checkOut != "") {

                        }
                    }

                    if ($guests != "") {

                    }


                }
                ?>
            </div>
        </section>
    </div>
</body>

</html>