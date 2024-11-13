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
    function view($res)
    {
        include "../../config.php";

        $propertiesWithImages = [];

        while ($row = mysqli_fetch_assoc($res)) {
            // Fetch images for each property
            $sqlImgID = "SELECT imgID FROM imgToProp WHERE propertyID={$row['propertyID']}";
            $imgRes = mysqli_query($dbConn, $sqlImgID);
            $images = [];
            while ($imgRow = mysqli_fetch_assoc($imgRes)) {
                $sqlImgPath = "SELECT imgPath FROM Images WHERE id={$imgRow['imgID']}";
                $pathRes = mysqli_query($dbConn, $sqlImgPath);
                if ($pathRow = mysqli_fetch_assoc($pathRes)) {
                    $images[] = $pathRow['imgPath'];
                }
            }

            $propertiesWithImages[] = [
                'id' => $row['propertyID'],
                'name' => $row['propName'], // Assuming the property name is stored in 'propName'
                'images' => $images
            ];
        }

        foreach ($propertiesWithImages as $propertyData) {
            echo "<div class='row'>
                <div class='service-item'>
                    <div class='service-item-inner'>
                        <div class='swiper-container'>
                            <div class='swiper-wrapper'>";

            foreach ($propertyData['images'] as $imagePath) {
                echo "<a href='viewSingle.php?id=" . htmlspecialchars($propertyData['id']) . "' class='swiper-slide'>
                                        <img src='" . htmlspecialchars($imagePath) . "' alt='Property Image'>
                                      </a>"; // Close swiper-slide with the <a> tag wrapping it
            }


            echo "      </div> <!-- Close swiper-wrapper -->
                        <div class='swiper-pagination'></div>
                        <div class='swiper-button-next'></div>
                        <div class='swiper-button-prev'></div>
                    </div> <!-- Close swiper-container -->
                    <div class='overlay'>
                     <a href='browseSingle.php?id=" . htmlspecialchars($propertyData['id']) . "' class='imgTitle'>
                                       <h3>" . htmlspecialchars($propertyData['name']) . "</h3>
                                      </a>
                    </div>
                </div> <!-- Close service-item-inner -->
            </div> <!-- Close service-item -->
        </div> <!-- Close row -->";
        }
    }
    ?>


    <!-- Swiper Initialization Script -->
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