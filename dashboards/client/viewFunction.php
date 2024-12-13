<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../../css/displayImages.css">
</head>

<body>
    <section class="service-section">
        <div class="cont">
            <?php
            function view($res, $source)
            {
                include "../../config.php";

                $propertiesWithImages = [];

                while ($row = mysqli_fetch_assoc($res)) {
                    // Fetch images for each property using the ImgToProp table
                    $propertyID = $row['propertyID']; 
                    $price = $row['price'];
                    $sqlImg = "SELECT Images.imgPath 
                   FROM Images
                   JOIN ImgToProp ON Images.id = ImgToProp.imgID
                   WHERE ImgToProp.propertyID = $propertyID";
                    $imgRes = mysqli_query($dbConn, $sqlImg);

                    $images = [];
                    while ($imgRow = mysqli_fetch_assoc($imgRes)) {
                        $images[] = $imgRow['imgPath'];
                    }

                    // Store property details along with associated images
                    $propertiesWithImages[] = [
                        'id' => $propertyID,
                        'name' => $row['propName'],
                        'city' => $row['city'],
                        'guests' => $row['guestNum'],
                        'price' => $price,
                        'type' => $row['propType'],
                        'host' => $row['fullName'],
                        'images' => $images
                    ];
                }

                // Render each property
                foreach ($propertiesWithImages as $propertyData) {
                    echo "<div class='row'>
            <div class='service-item'>
                <div class='service-item-inner'>
                    <div class='swiper-container'>
                        <div class='swiper-wrapper'>";

                    foreach ($propertyData['images'] as $imagePath) {
                        if (!$source) {
                            echo "<a href='browseSingle.php?id=" . htmlspecialchars($propertyData['id']) . "' class='swiper-slide'>
                                    <img src='" . htmlspecialchars($imagePath) . "' alt='Property Image'>
                                  </a>";
                        }
                        else {
                             echo "<a href='#' class='swiper-slide' style='cursor:default;'>
                                    <img src='" . htmlspecialchars($imagePath) . "' alt='Property Image'>
                                  </a>";
                        }
                    }
                    echo "          </div> <!-- Close swiper-wrapper -->
                        <div class='swiper-pagination'></div>
                        <div class='swiper-button-next'></div>
                        <div class='swiper-button-prev'></div>
                    </div> <!-- Close swiper-container -->
                </div> <!-- Close service-item-inner -->
                <div class='details'>
                    <h3>" . htmlspecialchars($propertyData['name']) . " </h3>
                    <p>" . htmlspecialchars($propertyData['type']) . " in " . htmlspecialchars($propertyData['city']) . " for " . htmlspecialchars($propertyData['guests']) . " guest(s)</p>
                    <p>BGN " . htmlspecialchars($propertyData['price']) . " per night</p>
                    <p>Hosted by " . htmlspecialchars($propertyData['host']) . "</p>
                </div>
            </div> <!-- Close service-item -->
        </div> <!-- Close row -->";
                }
            }
            ?>



            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    document.querySelectorAll('.swiper-container').forEach((swiperContainer) => {
                        new Swiper(swiperContainer, {
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
                });

            </script>
        </div>
    </section>
</body>

</html>