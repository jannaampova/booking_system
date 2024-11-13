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
    <title>Host Properties Dashboard</title>
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <link rel="stylesheet" href="../../css/check.css">
    <link rel="stylesheet" href="../../css/buttonAndSelect.css">
    <link rel="stylesheet" href="../../css/logIn.css">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>

    <style>
        .left-container{
    width: 20%;
    height: 100vh;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.5) 50%, rgba(0, 0, 0, 0.5) 50%), url(../../newImg.jpg);
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
     position: fixed; /* Make the container fixed during scrolling */
    top: 0; /* Pin it to the top of the page */
    left: 0; /* Pin it to the left of the page */
    z-index: 1000; /* Ensure it stays above other content */

}
        .main {
  width: 100%;
  height: 127vh;
  background:antiquewhite;
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
}
body {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  font-family: 'Poppins', sans-serif;
  background-color:antiquewhite;
}
.container {

  background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 50%);
  border-radius: 20px;
  width: 50%; /* Adjust to desired width on mobile */
  height: auto; /* Allow height to adjust dynamically */
  overflow: hidden;
margin-left:40%;  display: block; /* Ensure the button is displayed as a block element */
}
.form {
  padding: 30px 20px;
  color: #242321;

}
    </style>

</head>

<body>
    <div class="main">
        <div class="left-container">
            <div class="options">
                <?php
                $fullName = $_SESSION['name'];
                $firstName = explode(' ', $fullName)[0]; // Get the first name
                ?>
                <a href="hostSettings.php">
                <i class="fas fa-user-edit"></i>
                <?php echo htmlspecialchars($firstName); ?>
                </a>
                <a href="hostBoard.php">Home page</a>

                <a href="viewProperties.php">View Your Properties</a>
                <a href='logOut.php'>Log Out <i class="fa-solid fa-right-from-bracket"></i></a>

            </div>
        </div>


        <div class="row">
            <div class="section-title">
                <h1>Add Property</h1>
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
                        $sql = "SELECT * FROM PropertyType";
                        $result = mysqli_query($dbConn, $sql);
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
                        $sql = "SELECT * FROM GuestNumber";
                        $result = mysqli_query($dbConn, $sql);
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
                        if (mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo '<div>';
                                echo '<input type="checkbox" name="amenities[]" id="amenity-' . htmlspecialchars($row['id']) . '" value="' . htmlspecialchars($row['id']) . '">';
                                echo '<label for="amenity-' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['amenity']) . '</label>';
                                echo '</div>';
                            }
                        } else {
                            echo 'No amenities found.';
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
                    <input type="date" name="fromDate" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-control">
                    <label>Available to:</label>
                    <input type="date" name="toDate" required min="<?php echo date('Y-m-d'); ?>">
                </div>
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

                header("Location: hostBoard.php");

            } else {
                echo "Error adding property: " . mysqli_error($dbConn);
            }
        }

        mysqli_close($dbConn); // Close the database connection
        ?>
    </div>
</body>

</html>