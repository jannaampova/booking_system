<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Single</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../../css/viewSingle.css" />
    <link rel="stylesheet" href="../../css/viewSingleForm.css" />
    <style>
       
    </style>
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

        // Displaying images associated with the property
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

            // Display each image
            foreach ($images as $imagePath) {
                echo "<div class='kol'>
                        <img src='" . htmlspecialchars($imagePath) . "' alt='Property Image' style='max-width:100%; height:auto;'>
                      </div>";
            }
        }
        ?>
    </div>

        <form class="form">
            <div class="form-control">
                <label for="propertyName">Property Name</label>
                <input type="text" id="propertyName" value="<?php echo htmlspecialchars($row['propName']); ?>" readonly>
            </div>
            <div class="form-control">
                <label for="propertyDesc">Property Description</label>
                <input type="textarea" id="propertyDesc" value="<?php echo htmlspecialchars($row['propDesc']); ?>" readonly>
            </div>
            <!-- Add additional fields here -->
            <div class="form-control">
                <label for="additionalField1">Additional Field 1</label>
                <input type="text" id="additionalField1" placeholder="Enter additional info">
            </div>
            <div class="form-control">
                <label for="additionalField2">Additional Field 2</label>
                <input type="text" id="additionalField2" placeholder="Enter additional info">
            </div>
            <div class="form-control">
                <label for="additionalField3">Additional Field 3</label>
                <input type="text" id="additionalField3" placeholder="Enter additional info">
            </div>
            <div class="form-control">
                <label for="additionalField4">Additional Field 4</label>
                <input type="text" id="additionalField4" placeholder="Enter additional info">
            </div>
            <button type="submit">Submit</button>
       </form>

</body>
</html>
