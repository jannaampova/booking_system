<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Single</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../../css/viewSingle.css" />
    <link rel="stylesheet" href="../../css/logIn.css" />
    <link rel="stylesheet" href="../../css/buttonAndSelect.css" />
    <link rel="stylesheet" href="../../css/check.css" />
    <link rel="stylesheet" href="../../css/nav.css" />
    <link rel="stylesheet" href="../../css/admin.css" />
    <link rel="stylesheet" href="../../css/addEditAdminHost.css" />
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <style>
        .main {
            width: 100%;
        }

        .form-control input,
        .form-control select,
        .form-control textarea {
            box-shadow: 0px 0px 0px 0px #192d2d66;
            color: white;
        }


        .form-control select {
            color: black !important;
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
            margin-left: 28%;
            width: 100%;
        }

        .detail {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            width: 1050px;
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

        .cont {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
            max-height: 700px;
            overflow-y: auto;
            margin: 0 auto;
        }
    </style>

</head>
<?php
include "../../config.php";
session_start();

if (!isset($_SESSION['name'])) {
    header("Location: ../../userEntry/logIn.php");
    exit();
} ?>

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
                </a> <a href="hostBoard.php">Dashboard</a>
                <a href="viewProperties.php">View Your Properties</a>
                <a href="addProperty.php">Add Property</a>
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

                // Fetch Property Type options
                $propTypeID = $rowProperty['propTypeID'];
                $sql = "SELECT id, propType FROM PropertyType";
                $propTypes = mysqli_query($dbConn, $sql);

                $cityID = $rowProperty['cityID'];
                $sql = "SELECT id, city FROM City";
                $cities = mysqli_query($dbConn, $sql);

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
                if (isset($_POST['deleteImg'])) {
                    $imagePath = $_POST['img'];
                    $sqlGetImgID = "SELECT id from Images where imgPath='$imagePath' ";
                    $resImgID = mysqli_query($dbConn, $sqlGetImgID);
                    $imgID = mysqli_fetch_assoc($resImgID)['id'];

                    $sql_delete = "DELETE FROM Images WHERE id = '$imgID'";
                    if (mysqli_query($dbConn, $sql_delete)) {
                        ob_start();
                        // Your script logic and output
                        header("Location: viewSingle.php?id=$propertyId");
                        ob_end_flush(); // Flush and send the output buffer
                
                    } else {
                        echo "Error deleting image.";
                    }
                }
                ?>
            </div>


            <form class="form" method="post" action="" id="propertyForm" enctype="multipart/form-data">
                <div class="property-details">
                    <div class="property-info">
                        <div class="form-control"> <label for="propertyName">Property Name</label>
                            <input type="text" name="propertyName"
                                value="<?php echo htmlspecialchars($rowProperty['propName']); ?>" readonly>
                        </div>

                    </div>


                    <div class="property-info">
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
                    </div>

                    <div class="property-info">
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
                    </div>

                    <div class="property-info">
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
                    </div>

                    <div class="property-info">
                        <div class="form-control">
                            <label>Address</label>
                            <input type="text" name="address" placeholder="Enter additional info"
                                value="<?php echo htmlspecialchars($propAddress); ?>" readonly>
                        </div>
                    </div>

                    <div class="property-info">
                        <div class="form-control">
                            <label>Price</label>
                            <input type="text" name="price" placeholder="Enter additional info"
                                value="<?php echo htmlspecialchars($propPrice); ?>" readonly>
                        </div>
                    </div>

                    <div class="property-info">
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

                    <div class="property-info">
                        <div class="form-control">
                            <label>Available from:</label>
                            <input type="date" name="fromDate" required min="<?php echo date('Y-m-d'); ?>"
                                value="<?php echo htmlspecialchars($fromDate); ?>" disabled>
                        </div>
                    </div>

                    <div class="property-info">
                        <div class="form-control">
                            <label>Available to:</label>
                            <input type="date" name="toDate" required min="<?php echo date('Y-m-d'); ?>"
                                value="<?php echo htmlspecialchars($toDate); ?>" disabled>
                        </div>
                    </div>


                    <div class="property-info">
                        <div class="form-control">
                            <label>Select images:</label>
                            <input type="file" name="images[]" multiple accept="image/*" disabled>
                        </div>
                    </div>


                    <div class="property-info">
                        <div class="form-control">
                            <label>Description</label>
                            <textarea rows="5" cols="180" name="desc"
                                readonly><?php echo htmlspecialchars($propDesc); ?></textarea>
                        </div>
                    </div>
                    <div
                        style="display: flex; flex-direction:row; justify-content: space-between; width: 100%;margin-top:2%;">
                        <button type='button' name='saveChanges' onclick="toggleEditMode(event)">Edit</button>
                        <button type='submit' name='deleteProp'>Delete Property</button>
                    </div>
            </form>

        </div>


        <?php

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            if (isset($_POST['deleteProp'])) {
                $sql = "DELETE FROM Property WHERE id=$propertyId";
                if (mysqli_query($dbConn, $sql)) {
                    echo "<script>window.location.href='viewProperties.php';</script>";
                    exit();
                } else {
                    echo "<p>Error deleting property: " . mysqli_error($dbConn) . "</p>";
                }
            }

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

            // SQL to update property details
            $updateSQL = "UPDATE Property 
                      SET propName='$newName', propTypeID='$newTypeID', cityID='$newCityID', guestNumID='$newGuestNumID', 
                          propAddress='$newAddress', pricePerNight='$newPrice', propDesc='$newDesc' 
                      WHERE id=$propertyId";

            $updateAvailabilitySQL = "UPDATE Availabilities SET fromDate='$newFrom', toDate='$newTo', propStatus='free' WHERE propID=$propertyId";

            if (mysqli_query($dbConn, $updateSQL) && mysqli_query($dbConn, $updateAvailabilitySQL)) {

                // First, delete existing amenities
                $deleteAmenitiesSQL = "DELETE FROM PropAmenities WHERE propID=$propertyId";
                mysqli_query($dbConn, $deleteAmenitiesSQL);

                // Then, insert the new amenities
                if (!empty($newAmenities)) {
                    foreach ($newAmenities as $amenityID) {
                        $insertAmenitiesSQL = "INSERT INTO PropAmenities (propID, amenityID) VALUES ($propertyId, $amenityID)";
                        mysqli_query($dbConn, $insertAmenitiesSQL);
                    }
                }
            }

            if (isset($_FILES['images'])) {
                $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . '/booking system/upload/';
                foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                    $fileName = basename($_FILES['images']['name'][$key]);
                    $targetFilePath = $uploadDirectory . $fileName;

                    // Move the uploaded file to the uploads directory
                    if (move_uploaded_file($tmpName, $targetFilePath)) {
                        // Insert image path into Images table
                        $relativePath = '/booking system/upload/' . $fileName;
                        $sqlImg = "INSERT INTO Images (imgPath) VALUES ('$relativePath')";
                        mysqli_query($dbConn, $sqlImg);

                        // Get the last inserted image ID
                        $imgID = mysqli_insert_id($dbConn);

                        // Link the image to the property
                        $sqlImgToProp = "INSERT INTO ImgToProp (imgID, propertyID) VALUES ('$imgID', '$propertyId')";
                        mysqli_query($dbConn, $sqlImgToProp);
                    }
                }
            }



            if (mysqli_query($dbConn, $updateSQL)) {
            } else {
                echo "<p>Error updating property: " . mysqli_error($dbConn) . "</p>";
            }
        }
        ?>

        <script>
            function toggleEditMode(event) {
                event.preventDefault(); // Prevent default action
                const form = document.getElementById('propertyForm');
                const inputs = form.querySelectorAll('input, textarea, select, checkbox, date, file');
                const button = form.querySelector('button');

                if (button.innerText === "Edit") {
                    inputs.forEach(input => {
                        input.removeAttribute('readonly');
                        input.removeAttribute('disabled');
                    });
                    button.innerText = "Save";
                } else {
                    form.submit(); // Submit the form
                    inputs.forEach(input => {
                        input.setAttribute('readonly', 'readonly');
                        input.setAttribute('disabled', 'disabled');
                    });
                    button.innerText = "Edit";
                }
            }
        </script>

    </div>


</body>

</html>