<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Single Property</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../../css/viewSingle.css" />
    <link rel="stylesheet" href="../../css/buttonAndSelect.css" />
    <link rel="stylesheet" href="../../css/client.css" />
    <link rel="stylesheet" href="../../css/addEditAdminHost.css" />
    <link rel="stylesheet" href="../../css/admin.css" />
    <link rel="stylesheet" href="../../css/nav.css" />
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <style>
       .cont {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    padding: 20px;
    max-height: 700px;
    overflow-y: auto;
    margin: 0 auto;
}

.inner-flex {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin-top: 30px;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    margin-left:28%;
}

.images-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
}

.images-gallery img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    margin-bottom: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.images-gallery img:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.property-details {
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
    margin-bottom: 25px;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.property-info label {
    font-weight: 600;
    color: #333;
    margin-right: 10px;
}

.description {
    margin-top: 20px;
    font-size: 15px;
    color: #555;
}

.book-now-button {
    background-color: #28a745;
    color: #fff;
    border: none;
    padding: 12px 25px;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 25px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.book-now-button:hover {
    background-color: #218838;
    transform: translateY(-2px);
}

.book-now-button:active {
    background-color: #1e7e34;
    transform: translateY(0);
}
.main{
 width: 120%;   
}
    </style>
</head>

<body>
    <?php
    include "../../config.php";
    session_start();

    if (!isset($_SESSION['name']) && !isset($_SESSION['userID'])) {
        header("Location: ../../userEntry/logIn.php");
        exit();
    }
    ?>

    <div class="main">
        <div class="left-container">
            <div class="options">
                <?php
                $fullName = $_SESSION['name'];
                $userID=$_SESSION['userID'];                
                $firstName = explode(' ', $fullName)[0];
                ?>
                <a href="hostSettings.php"><i class="fas fa-user-edit"></i>
                    <?php echo htmlspecialchars($firstName); ?></a>
                <a href="clientBoard.php">Dashboard</a>
                <a href="bookings.php">Bookings</a>
                <a href="../admin/logOut.php">Log Out <i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>

        <div class="inner-flex">
            <div class="cont">
                <?php
                $propertyId = isset($_GET['id']) ? intval($_GET['id']) : 0;
                $sql = "SELECT * FROM Property WHERE id=$propertyId";
                $res = mysqli_query($dbConn, $sql);
                $rowProperty = mysqli_fetch_assoc($res);

                $propAddress = $rowProperty['propAddress'];
                $propName = $rowProperty['propName'];
                $propPrice = $rowProperty['pricePerNight'];
                $propDesc = $rowProperty['propDesc'];

                $sqlImgID = "SELECT imgID FROM imgToProp WHERE propertyID=$propertyId";
                $imgRes = mysqli_query($dbConn, $sqlImgID);
                $images = [];
                while ($imgRow = mysqli_fetch_assoc($imgRes)) {
                    $sqlImgPath = "SELECT imgPath FROM Images WHERE id={$imgRow['imgID']}";
                    $pathRes = mysqli_query($dbConn, $sqlImgPath);
                    if ($pathRow = mysqli_fetch_assoc($pathRes)) {
                        $images[] = $pathRow['imgPath'];
                    }
                }

                foreach ($images as $imagePath) {
                    echo "<div class='service-item'>
                            <div class='service-item-inner'>
                                <div class='kol'>
                                    <img src='" . htmlspecialchars($imagePath) . "' alt='Property Image' style='max-width:100%; height:auto;'>
                                </div>
                            
                            </div>
                          </div>";
                }
                ?>
            </div>
            <div class="detail">
                <!-- Property Details -->
                <div class="property-details">
                    <h2><?php echo htmlspecialchars($propName); ?></h2>
                    <div class="property-info">
                        <label>Address: </label>
                        <span><?php echo htmlspecialchars($propAddress); ?></span>
                    </div>
                    <div class="property-info">
                        <label>Price per Night: </label>
                        <span>$<?php echo htmlspecialchars($propPrice); ?></span>
                    </div>
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($propDesc)); ?></p>
                </div>

                <!-- Availability Details -->
                <?php
                $sqlAvail = "SELECT fromDate, toDate FROM Availabilities WHERE propID = $propertyId";
                $availResult = mysqli_query($dbConn, $sqlAvail);
                $availability = mysqli_fetch_assoc($availResult);
                $fromDate = $availability['fromDate'];
                $toDate = $availability['toDate'];
                ?>
                <div class="property-info">
                    <label>Available From: </label>
                    <span><?php echo htmlspecialchars($fromDate); ?></span>
                </div>
                <div class="property-info">
                    <label>Available To: </label>
                    <span><?php echo htmlspecialchars($toDate); ?></span>
                </div>

                <!-- Booking Button -->
                <a href="confirmBooking.php?id=<?php echo htmlspecialchars($_SESSION['userID']); ?>&propertyID=<?php echo htmlspecialchars($propertyId); ?>&checkIN=<?php echo htmlspecialchars($propertyId); ?>">
                <button class="book-now-button" >Book Now</button>
                </a>
            </div>
        </div>
    </div>
</body>

</html>