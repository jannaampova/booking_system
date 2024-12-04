<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: ../../userEntry/logIn.php");
    exit();
}
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Properties</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../../css/viewProp.css" />
    <link rel="stylesheet" href="../../css/admin.css" />
    <link rel="stylesheet" href="../../css/buttonAndSelect.css" />
    <link rel="stylesheet" href="../../css/displayImages.css" />
    <link rel="stylesheet" href="../../css/nav.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>

    <style>
  .left-container {
    width: 200px; /* Adjust width to fit your design */
    height: 100vh; /* Full height of the viewport */
    position: fixed; /* Stay fixed on the left */
    top: 0;
    left: 0;
    background-color: #333; /* Set background for visibility */
    z-index: 10; /* Ensure it stays on top */
}

.cont{
    margin-left: 20%!important;
}
    </style>
</head>

<body>
        <div class="left-container">
            <div class="options">
                <?php
                $fullName = $_SESSION['name'];
                $firstName = explode(' ', $fullName)[0]; // Get the first name
                ?>
                <a href="../userSettings.php">
                    <i class="fas fa-user-edit"></i>
                    <?php echo htmlspecialchars($firstName); ?>
                </a>
                <a href="hostBoard.php">Dashboard</a>
                <a href="addProperty.php">Add Property</a>
                <a href='../admin/logOut.php'>Log Out <i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>

        <section class="service-section" id="vynl"> <!--С хипервръзка свързано с мени-->
            <div class="row">
                <div class="section-title">
                    <h1>Your Properties</h1>
                </div>
            </div>
            <div class="cont">
                <?php
                include "../../config.php";
                $sql = "SELECT * FROM Property WHERE hostID={$_SESSION['userID']}";
                $res = mysqli_query($dbConn, $sql);
                $propertiesWithImages = [];

                while ($row = mysqli_fetch_assoc($res)) {
                    // Fetch images for each property
                    $sqlImgID = "SELECT imgID FROM imgToProp WHERE propertyID={$row['id']}";
                    $imgRes = mysqli_query($dbConn, $sqlImgID);

                    $images = [];
                    while ($imgRow = mysqli_fetch_assoc($imgRes)) {
                        // Fetch image path from the Images table
                        $sqlImgPath = "SELECT imgPath FROM Images WHERE id={$imgRow['imgID']}";
                        $pathRes = mysqli_query($dbConn, $sqlImgPath);
                        if ($pathRow = mysqli_fetch_assoc($pathRes)) {
                            $images[] = $pathRow['imgPath'];
                        }
                    }

                    $propertiesWithImages[] = [
                        'id' => $row['id'],
                        'name' => $row['propName'], // Assuming the property name is stored in the 'name' field
                        'images' => $images
                    ];
                }

                // Now output the HTML for each property
                foreach ($propertiesWithImages as $propertyData) {
                    echo "<div class='row' style='margin-top:10%;'>

        <div class='service-item'>
            <div class='service-item-inner'>
                <div class='swiper-container'>
                    <div class='swiper-wrapper'>";

                    foreach ($propertyData['images'] as $imagePath) {

                        echo "<div class='swiper-slide'>
                 <a href='viewSingle.php?id=" . htmlspecialchars($propertyData['id']) . "'>
                <img src='" . htmlspecialchars($imagePath) . "' alt='Property Image'>
              </div>";
                    }

                    echo " </div> <!-- swiper-wrapper -->
                <div class='swiper-pagination'></div>
                <div class='swiper-button-next'></div>
                <div class='swiper-button-prev'></div>
            </div> <!-- swiper-container -->
            <div class='overlay'>
                <h3>" . htmlspecialchars($propertyData['name']) . "</h3> <!-- Display Property Name -->

            </div>
        </a>
        </div>
    </div>
</div>";
                }
                ?>

                <!-- Swiper Initialization Script -->
                <script>
                    const swipers = document.querySelectorAll('.swiper-container');
                    swipers.forEach(swiperContainer => {
                        const swiper = new Swiper(swiperContainer, {
                            loop: true,
                            pagination: {
                                el: swiperContainer.querySelector('.swiper-pagination'),
                                clickable: true,
                            },
                            navigation: {
                                nextEl: swiperContainer.querySelector('.swiper-button-next'),
                                prevEl: swiperContainer.querySelector('.swiper-button-prev'),
                            },
                        });
                    });
                </script>
            </div>
        </section>
</body>

</html>