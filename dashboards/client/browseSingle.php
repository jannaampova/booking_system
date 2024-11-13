<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Single</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../../css/viewSingle.css" />
    <link rel="stylesheet" href="../../css/viewSingleForm.css" />
    <link rel="stylesheet" href="../../css/buttonAndSelect.css" />
    <link rel="stylesheet" href="../../css/client.css" />
</head>
<?php
include "../../config.php";
session_start();

if (!isset($_SESSION['name'])) {
    header("Location: ../../userEntry/logIn.php");
    exit();
} ?>

<body>
    <div class="section">
        <nav>
            <a href="hostBoard.php">Home page</a>
            <a href="../admin/logOut.php">Log Out</a>
        </nav>
</div>



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

        // Fetch Property Type options
        $propTypeID = $rowProperty['propTypeID'];
        $sql = "SELECT id, propType FROM PropertyType";
        $propTypes = mysqli_query($dbConn, $sql);

        // Fetch City options
        $cityID = $rowProperty['cityID'];
        $sql = "SELECT id, city FROM City";
        $cities = mysqli_query($dbConn, $sql);

        // Fetch Guest Number options
        $guestNumID = $rowProperty['guestNumID'];
        $sql = "SELECT id, guestNum FROM GuestNumber";
        $guestNumbers = mysqli_query($dbConn, $sql);

        $sql = "SELECT * FROM Property WHERE id=$propertyId";
        $resImg = mysqli_query($dbConn, $sql);

        if ($row = mysqli_fetch_assoc($resImg)) {
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
                            <div class='overlay'>
                                <form class='form' name='deleteImg' method='post'>
                                <input type='hidden' name='img' value='" . $imagePath . "'>
                                <button type='submit' name='deleteImg' class='action-button delete'>DELETE</button>
                        </form>
                            </div>
                        </div>
                      </div>";
            }
        }
       
        ?>
    </div>

    <form class="form" method="post" action="" id="propertyForm" enctype="multipart/form-data">
        <div class="form-control">
            <label for="propertyName">Property Name</label>
            <input type="text" name="propertyName" value="<?php echo htmlspecialchars($rowProperty['propName']); ?>"
                readonly>
        </div>

        <!-- Property Type Dropdown -->
        <div class="form-control">
            <label>Property Type</label>
            <select name="propertyType" disabled>
                <?php while ($propType = mysqli_fetch_assoc($propTypes)): ?>
                    <option value="<?php echo $propType['id']; ?>" <?php echo ($propType['id'] == $propTypeID) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($propType['propType']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- City Dropdown -->
        <div class="form-control">
            <label>City</label>
            <select name="city" disabled>
                <?php while ($city = mysqli_fetch_assoc($cities)): ?>
                    <option value="<?php echo $city['id']; ?>" <?php echo ($city['id'] == $cityID) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($city['city']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Guest Number Dropdown -->
        <div class="form-control">
            <label>Guests</label>
            <select name="guests" disabled>
                <?php while ($guest = mysqli_fetch_assoc($guestNumbers)): ?>
                    <option value="<?php echo $guest['id']; ?>" <?php echo ($guest['id'] == $guestNumID) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($guest['guestNum']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-control">
            <label>Address</label>
            <input type="text" name="address" placeholder="Enter additional info"
                value="<?php echo htmlspecialchars($propAddress); ?>" readonly>
        </div>
        <div class="form-control">
            <label>Price</label>
            <input type="text" name="price" placeholder="Enter additional info"
                value="<?php echo htmlspecialchars($propPrice); ?>" readonly>
        </div>

        <div class="form-control">
            <label>Select amenities:</label>
            <div class="checkbox-grid">
                <?php
                $sql = "SELECT * FROM Amenities";
                $res = mysqli_query($dbConn, $sql);

                $sqlAmenities = "SELECT amenityID FROM PropAmenities WHERE propID = $propertyId";
                $amenitiesResult = mysqli_query($dbConn, $sqlAmenities);
                $selectedAmenities = [];

                while ($row = mysqli_fetch_assoc($amenitiesResult)) {
                    $selectedAmenities[] = $row['amenityID'];
                }


                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        $checked = in_array($row['id'], $selectedAmenities) ? 'checked' : '';
                        echo '<div>';
                        echo '<input type="checkbox" name="amenities[]" id="amenity-' . htmlspecialchars($row['id']) . '" value="' . htmlspecialchars($row['id']) . '" disabled ' . $checked . '>';
                        echo '<label for="amenity-' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['amenity']) . '</label>';
                        echo '</div>';
                    }
                } else {
                    echo 'No amenities found.';
                }
                ?>
            </div>
        </div>
        <?php
        $sql = "SELECT * FROM Availabilities";
        $res = mysqli_query($dbConn, $sql);
        $sqlAvail = "SELECT fromDate FROM Availabilities WHERE propID = $propertyId";
        $availResult = mysqli_query($dbConn, $sqlAvail);
        $availability = mysqli_fetch_assoc($availResult);
        $fromDate = $availability['fromDate'];
        ?>
        <?php
        $sql = "SELECT * FROM Availabilities";
        $res = mysqli_query($dbConn, $sql);
        $sqlAvail = "SELECT toDate FROM Availabilities WHERE propID = $propertyId";
        $availResult = mysqli_query($dbConn, $sqlAvail);
        $availability = mysqli_fetch_assoc($availResult);
        $toDate = $availability['toDate'];
        ?>
        <div class="form-control">
            <div class="form-control"> <label>Available from:</label>
                <input type="date" name="fromDate" required min="<?php echo date('Y-m-d'); ?>"
                    value="<?php echo htmlspecialchars($fromDate); ?>" disabled>
            </div>

            <div class="form-control"> <label>Available to:</label>
                <input type="date" name="toDate" required min="<?php echo date('Y-m-d'); ?>"
                    value="<?php echo htmlspecialchars($toDate); ?>" disabled>
            </div>
        </div>
        <div class="description">
            <label>Description</label>
            <textarea rows="5" cols="180" name="desc" readonly><?php echo htmlspecialchars($propDesc); ?></textarea>
        </div>

    </form>

    <?php
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

       

        $newName = mysqli_real_escape_string($dbConn, $_POST['propertyName']);
        $newTypeID = (int) $_POST['propertyType'];
        $newCityID = (int) $_POST['city'];
        $newGuestNumID = (int) $_POST['guests'];
        $newAddress = mysqli_real_escape_string($dbConn, $_POST['address']);
        $newPrice = (float) $_POST['price'];
        $newDesc = mysqli_real_escape_string($dbConn, $_POST['desc']);
        $newFrom = $_POST['fromDate'];
        $newTo = $_POST['toDate'];
        $newAmenities = isset($_POST['amenities']) ? $_POST['amenities'] : [];

        
       
        
       
    }
    ?>

</body>

</html>