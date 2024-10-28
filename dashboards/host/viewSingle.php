<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Single</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../../css/viewSingle.css" />
    <link rel="stylesheet" href="../../css/viewSingleForm.css" />
    <link rel="stylesheet" href="../../css/select.css" />
</head>

<body>
    <div class="section">
        <nav>
            <a href="hostBoard.php">Home page</a>
            <a href="../admin/logOut.php">Log Out</a>
        </nav>
    </div>

    <div class="cont">
        <?php
        include "../../config.php";
        $propertyId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $sql = "SELECT * FROM Property WHERE id=$propertyId";
        $res = mysqli_query($dbConn, $sql);

        if ($row = mysqli_fetch_assoc($res)) {
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
                echo "<div class='kol'><img src='" . htmlspecialchars($imagePath) . "' alt='Property Image' style='max-width:100%; height:auto;'></div>";
            }
        }

        // Fetch Property Type options
        $propTypeID = $row['propTypeID'];
        $sql = "SELECT id, propType FROM PropertyType";
        $propTypes = mysqli_query($dbConn, $sql);

        // Fetch City options
        $cityID = $row['cityID'];
        $sql = "SELECT id, city FROM City";
        $cities = mysqli_query($dbConn, $sql);

        // Fetch Guest Number options
        $guestNumID = $row['guestNumID'];
        $sql = "SELECT id, guestNum FROM GuestNumber";
        $guestNumbers = mysqli_query($dbConn, $sql);
        ?>
    </div>

    <form class="form" method="post" action="" id="propertyForm">
        <div class="form-control">
            <label for="propertyName">Property Name</label>
            <input type="text" name="propertyName" value="<?php echo htmlspecialchars($row['propName']); ?>" readonly>
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
            <input type="text" name="address" placeholder="Enter additional info" value="<?php echo htmlspecialchars($row['propAddress']); ?>" readonly>
        </div>
        <div class="form-control">
            <label>Price</label>
            <input type="text" name="price" placeholder="Enter additional info" value="<?php echo htmlspecialchars($row['pricePerNight']); ?>" readonly>
        </div>
        <div class="description">
            <label>Description</label>
            <textarea rows="5" cols="180" name="desc" readonly><?php echo htmlspecialchars($row['propDesc']); ?></textarea>
        </div>
        <button type='button' name='saveChanges' onclick="toggleEditMode(event)">Edit</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Retrieve and sanitize input data
        $newName = mysqli_real_escape_string($dbConn, $_POST['propertyName']);
        $newTypeID = (int) $_POST['propertyType'];
        $newCityID = (int) $_POST['city'];
        $newGuestNumID = (int) $_POST['guests'];
        $newAddress = mysqli_real_escape_string($dbConn, $_POST['address']);
        $newPrice = (float) $_POST['price'];
        $newDesc = mysqli_real_escape_string($dbConn, $_POST['desc']);

        // SQL to update property details
        $updateSQL = "UPDATE Property 
                      SET propName='$newName', propTypeID='$newTypeID', cityID='$newCityID', guestNumID='$newGuestNumID', 
                          propAddress='$newAddress', pricePerNight='$newPrice', propDesc='$newDesc' 
                      WHERE id=$propertyId";

        if (mysqli_query($dbConn, $updateSQL)) {
            exit(); // Ensure the script stops after redirection
        } else {
            echo "<p>Error updating property: " . mysqli_error($dbConn) . "</p>";
        }
    }
    ?>

    <script>
        function toggleEditMode(event) {
            event.preventDefault(); // Prevent default action
            const form = document.getElementById('propertyForm');
            const inputs = form.querySelectorAll('input, textarea, select');
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

    
</body>

</html>
