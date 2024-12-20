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
    <title>Add Property</title>
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <link rel="stylesheet" href="../../css/check.css">
    <link rel="stylesheet" href="../../css/buttonAndSelect.css">
    <link rel="stylesheet" href="../../css/logIn.css">
    <link rel="stylesheet" href="../../css/addEditAdminHost.css">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <style>
        .form-control input,
        .form-control select,
        .form-control textarea {
            box-shadow: 1px 2px 4px 2px #192d2d66;
            color: white;
        }

        .form-control label {
            font-weight: 600;
            color: #333;
            margin-right: 10px;
        }

        .form-control select {
            color: black !important;
        }
    </style>
</head>

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
                </a>
                <a href="hostBoard.php">Dashboard</a>

                <a href="viewProperties.php">View Your Properties</a>
                <a href='../admin/logOut.php'>Log Out <i class="fa-solid fa-right-from-bracket"></i></a>

            </div>
        </div>
        <div class="row">
            <div class="section-title">
                <h1>Add property</h1>
            </div>
        </div>

        <div class="container">
            <form class="form" method="post" enctype="multipart/form-data">
                <div class="form-control">
                    <label>Name:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-control">
                    <label>Property Type:</label>
                    <select name="selectType" id="selectType" required>
                        <?php
                        include '../../config.php';  // Adjust path accordingly
                        $result = mysqli_query($dbConn, "SELECT * FROM PropertyType");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["propType"]) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-control">
                    <label>Guest Number:</label>
                    <select name="selectNum" id="selectNum" required>
                        <?php
                        $result = mysqli_query($dbConn, "SELECT * FROM GuestNumber");
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["guestNum"]) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-control">
                    <label>City:</label>
                    <select name="selectCity" id="selectCity" required>
                        <?php
                        $sql = "SELECT * FROM City";
                        $result = mysqli_query($dbConn, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["city"]) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-control">
                    <label>Address:</label>
                    <input type="text" name="address" required>
                </div>
                <div class="form-control">
                    <label>Price:</label>
                    <input type="text" name="price" required>
                </div>
                <div class="form-control">
                    <label>Select amenities:</label>
                    <div class="checkbox-grid">
                        <?php
                        $sql = "SELECT * FROM Amenities";
                        $res = mysqli_query($dbConn, $sql);

                        if ($res && mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo '<div>';
                                echo '<input type="checkbox" name="amenities[]" id="' . htmlspecialchars($row['id']) . '" value="' . htmlspecialchars($row['id']) . '">';
                                // Debug output to ensure label content is coming through
                                echo '<label class="checkLabel" for="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['amenity']) . '</label>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div>No amenities found.</div>'; // Display this if no rows are fetched
                        }
                        ?>
                    </div>
                </div>

                <div class="form-control">
                    <label>Description:</label>
                    <textarea name="desc" required></textarea>
                </div>
                <div class="form-control">
                    <label>Available from:</label>
                    <input type="date" name="fromDate" id="fromDate" required min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-control">
                    <label>Available to:</label>
                    <input type="date" name="toDate" id="toDate" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <script>
                    document.getElementById('fromDate').addEventListener('change', function () {
                        var fromDate = document.getElementById('fromDate').value;
                        document.getElementById('toDate').setAttribute('min', fromDate); // Set min of toDate to the selected fromDate
                    });
                </script>
                <div class="form-control">
                    <label>Select images:</label>
                    <input type="file" name="images[]" multiple accept="image/*">
                </div>
                <button type="submit" name="addProperty">Publish</button>
            </form>
        </div>

        <?php
        // Include your config file to connect to the database
        include '../../config.php';  // Adjust path accordingly
        
        if (isset($_POST['addProperty'])) {
            $name = mysqli_real_escape_string($dbConn, $_POST['name']);
            $address = mysqli_real_escape_string($dbConn, $_POST['address']);
            $city = mysqli_real_escape_string($dbConn, $_POST['selectCity']);
            $host = mysqli_real_escape_string($dbConn, $_SESSION['name']);
            $type = mysqli_real_escape_string($dbConn, $_POST['selectType']);
            $fromDate = mysqli_real_escape_string($dbConn, $_POST['fromDate']);
            $toDate = mysqli_real_escape_string($dbConn, $_POST['toDate']);
            $num = mysqli_real_escape_string($dbConn, $_POST['selectNum']);
            $price = mysqli_real_escape_string($dbConn, $_POST['price']);
            $desc = mysqli_real_escape_string($dbConn, $_POST['desc']);
            $amenities = isset($_POST['amenities']) ? $_POST['amenities'] : [];
            $availability = 'free';

            // Get IDs from the database
            $sql = "SELECT id FROM City WHERE id = '$city'";
            $res = mysqli_query($dbConn, $sql);
            $cityID = mysqli_fetch_assoc($res)['id'];

            $sql = "SELECT id FROM User WHERE fullName = '$host'";
            $res = mysqli_query($dbConn, $sql);
            $hostID = mysqli_fetch_assoc($res)['id'];

            $sql = "SELECT id FROM PropertyType WHERE id = '$type'";
            $res = mysqli_query($dbConn, $sql);
            $typeID = mysqli_fetch_assoc($res)['id'];

            $sql = "SELECT id FROM GuestNumber WHERE id = '$num'";
            $res = mysqli_query($dbConn, $sql);
            $guestNumID = mysqli_fetch_assoc($res)['id'];

            // Insert the property details into the Property table
            $sqlProperty = "INSERT INTO Property (propTypeID, hostID, cityID, guestNumID, pricePerNight, propAddress, propDesc, propName)
                        VALUES ('$typeID', '$hostID', '$cityID', '$guestNumID', '$price', '$address', '$desc', '$name')";
            if (mysqli_query($dbConn, $sqlProperty)) {
                $propID = mysqli_insert_id($dbConn); // Get the last inserted property ID
        
                // Insert availability details
                $sqlAvailability = "INSERT INTO Availabilities (fromDate, toDate, propStatus, propID)
                                VALUES ('$fromDate', '$toDate', '$availability', '$propID')";
                mysqli_query($dbConn, $sqlAvailability);

                // Insert amenities details
                foreach ($amenities as $amenityID) {
                    $sqlAmenities = "INSERT INTO PropAmenities (propID, amenityID) VALUES ('$propID', '$amenityID')";
                    mysqli_query($dbConn, $sqlAmenities);
                }

                // Handle image uploads
                if (isset($_FILES['images'])) {
                    $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . '/booking system/upload/';
                    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                        $fileName = basename($_FILES['images']['name'][$key]);
                        $targetFilePath = $uploadDirectory . $fileName;

                        // Move the uploaded file to the uploads directory
                        if (move_uploaded_file($tmpName, $targetFilePath)) {
                            // Store the path as a project-root-relative path (e.g., /uploads/image.jpg)
                            $relativePath = '/booking system/upload/' . $fileName;
                            $sqlImg = "INSERT INTO Images (imgPath) VALUES ('$relativePath')";
                            mysqli_query($dbConn, $sqlImg);

                            // Get the last inserted image ID
                            $imgID = mysqli_insert_id($dbConn);

                            // Link the image to the property
                            $sqlImgToProp = "INSERT INTO ImgToProp (imgID, propertyID) VALUES ('$imgID', '$propID')";
                            mysqli_query($dbConn, $sqlImgToProp);
                        }
                    }
                }


            } else {
                echo "Error adding property: " . mysqli_error($dbConn);
            }
        }

        mysqli_close($dbConn); // Close the database connection
        ?>
    </div>
</body>

</html>