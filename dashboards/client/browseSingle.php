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
    <title>Property Details</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../../css/viewSingle.css" />
    <link rel="stylesheet" href="../../css/addEditAdminHost.css" />
    <link rel="stylesheet" href="../../css/propInfo.css" />
    <link rel="stylesheet" href="../../css/admin.css" />
    <link rel="stylesheet" href="../../css/nav.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Modal image */
        .modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 80%;
            margin-top: 5%;
        }

     

        /* Close button */
        .close {
            position: absolute;
            top: 10px;
            right: 25px;
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }

        /* Navigation arrows */
        .prev,
        .next {
            position: absolute;
            top: 50%;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            user-select: none;
            transform: translateY(-50%);
        }

        .prev {
            left: 15px;
        }

        .next {
            right: 15px;
        }

        .prev:hover,
        .next:hover {
            color: #f1f1f1;
        }
    </style>
</head>

<body>
    <?php
    include "../../config.php";
    ?>
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

                <a href="clientBoard.php">Dashboard</a>
                <a href="yourBookings.php">Bookings</a>
                <a href="../admin/logOut.php">Log Out <i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>

        <div class="inner-flex">
            <div class="cont">
                <?php
                $propertyId = isset($_GET['id']) ? intval($_GET['id']) : 0;

                $res = mysqli_query($dbConn, "SELECT * FROM Property WHERE id=$propertyId");
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

                <div class="property-details">
                    <h3>Additional Details</h3>
                    <div class="property-info">
                        <label>Host Name: </label>
                        <span>
                            <?php
                            $hostResult = mysqli_query($dbConn, "SELECT fullName FROM User WHERE id = {$rowProperty['hostID']}");
                            $hostName = mysqli_fetch_assoc($hostResult)['fullName'];
                            echo htmlspecialchars($hostName);
                            ?>
                        </span>
                    </div>
                    <div class="property-info">
                        <label>Property Type: </label>
                        <span>
                            <?php
                            $typeResult = mysqli_query($dbConn, "SELECT propType FROM PropertyType WHERE id = {$rowProperty['propTypeID']}");
                            $propType = mysqli_fetch_assoc($typeResult)['propType'];
                            echo htmlspecialchars($propType);
                            ?>
                        </span>
                    </div>
                    <div class="property-info">
                        <label>Maximum Guests: </label>
                        <span>
                            <?php
                            $guestResult = mysqli_query($dbConn, "SELECT guestNum FROM GuestNumber WHERE id = {$rowProperty['guestNumID']}");
                            $guestNum = mysqli_fetch_assoc($guestResult)['guestNum'];
                            echo htmlspecialchars($guestNum);
                            ?>
                        </span>
                    </div>
                    <div class="property-info">
                        <label>Check In: </label>
                        <span>
                        12:00 PM
                        </span>
                    </div>
                    <div class="property-info">
                        <label>Check Out: </label>
                        <span>
                        10:00 AM
                        </span>
                    </div>
                </div>

                <?php
                $sqlAvail = "SELECT fromDate, toDate FROM Availabilities WHERE propID = $propertyId AND propStatus = 'free'";
                $availResult = mysqli_query($dbConn, $sqlAvail);
                $availabilityRanges = [];

                while ($availability = mysqli_fetch_assoc($availResult)) {
                    $availabilityRanges[] = [
                        'from' => $availability['fromDate'],
                        'to' => $availability['toDate'],
                    ];
                }

                $sqlReserved = "
                 SELECT fromDate, toDate 
                FROM Availabilities 
                WHERE propID = $propertyId 
                AND propStatus IN ('reserved', 'booked')";
                $reservedResult = mysqli_query($dbConn, $sqlReserved);
                $reservedRanges = [];
                
                while ($reserved = mysqli_fetch_assoc($reservedResult)) {
                    $reservedRanges[] = [
                        'from' => $reserved['fromDate'],
                        'to' => $reserved['toDate'],
                    ];
                }
                $availabilityRanges = [
                    'free' => $availabilityRanges,
                    'reserved' => $reservedRanges,
                ];

                ?>


                <div class="property-details">
                    <div class="property-info">
                        <label>Check-In:</label>
                        <div id="checkInCalendar"></div>
                    </div>
                </div>
                <div class="property-details">
                    <div class="property-info">
                        <label>Check-Out:</label>
                        <div id="checkOutCalendar"></div>
                    </div>
                </div>
                <div class="property-details-amenities">
                    <div class="property-info">
                        <h2>Amenities</h2>
                        <ul style="list-style-type:disc;">
                            <?php
                            $sqlAmenities = "
            SELECT a.amenity 
            FROM PropAmenities pa
            JOIN Amenities a ON pa.amenityID = a.id
            WHERE pa.propID = $propertyId
        ";
                            $amenitiesResult = mysqli_query($dbConn, $sqlAmenities);

                            if (mysqli_num_rows($amenitiesResult) > 0) {
                                while ($amenityRow = mysqli_fetch_assoc($amenitiesResult)) {
                                    echo "<li>" . htmlspecialchars($amenityRow['amenity']) . "</li>";
                                }
                            } else {
                                echo "<li>No amenities listed for this property.</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div id="imageModal" class="modal">
                <span class="close">&times;</span> <!-- Close button -->
                <span class="prev">&#10094;</span> <!-- Previous arrow -->
                <span class="next">&#10095;</span> <!-- Next arrow -->
                <img class="modal-content" id="modalImg">
            </div>

            <script>
                let selectedCheckIn = null;
                let selectedCheckOut = null;

                function handleBooking() {
                    const bookNowLink = document.querySelector("#bookNowLink");
                    const propertyID = "<?php echo htmlspecialchars($propertyId); ?>";
                    const userID = "<?php echo htmlspecialchars($_SESSION['userID']); ?>";

                    if (selectedCheckIn && selectedCheckOut) {
                        bookNowLink.href = `reserveBooking.php?id=${userID}&propertyID=${propertyID}&checkIN=${selectedCheckIn}&checkOUT=${selectedCheckOut}`;
                    } else {
                        alert("Please select both Check-In and Check-Out dates.");
                        event.preventDefault();
                    }
                }
                const availabilityRanges = <?php echo json_encode($availabilityRanges); ?>;
                document.addEventListener("DOMContentLoaded", function () {
                    const normalizeDate = (d) => {
                        const normalized = new Date(d);
                        normalized.setHours(0, 0, 0, 0); // Reset time
                        return normalized;
                    };

                    const isDateDisabled = (date) => {
                        const normalizedDate = normalizeDate(date);
                        const today = normalizeDate(new Date());

                        if (normalizedDate < today) {
                            return true;
                        }

                        // Check if the date falls in any reserved range
                        const isInReservedRange = availabilityRanges.reserved.some(range => {
                            const fromDate = normalizeDate(range.from);
                            const toDate = normalizeDate(range.to);
                            return normalizedDate >= fromDate && normalizedDate <= toDate;
                        });

                        if (isInReservedRange) {
                            return true;
                        }

                        // Check if the date falls in any free range
                        const isInFreeRange = availabilityRanges.free.some(range => {
                            const fromDate = normalizeDate(range.from);
                            const toDate = normalizeDate(range.to);
                            return normalizedDate >= fromDate && normalizedDate <= toDate;
                        });

                        return !isInFreeRange;
                    };

                    const isRangeValid = (checkInDate, checkOutDate) => {
                        const normalizedCheckIn = normalizeDate(checkInDate);
                        const normalizedCheckOut = normalizeDate(checkOutDate);

                        return !availabilityRanges.reserved.some(range => {
                            const fromDate = normalizeDate(range.from);
                            const toDate = normalizeDate(range.to);

                            // Check if reserved range overlaps with the selected range
                            return (
                                (fromDate >= normalizedCheckIn && fromDate <= normalizedCheckOut) ||
                                (toDate >= normalizedCheckIn && toDate <= normalizedCheckOut) ||
                                (normalizedCheckIn >= fromDate && normalizedCheckOut <= toDate)
                            );
                        });
                    };

                    const checkIn = flatpickr("#checkInCalendar", {
                        dateFormat: "Y-m-d",
                        inline: true,
                        disable: [isDateDisabled],
                        onChange: function (selectedDates, dateStr) {
                            selectedCheckIn = dateStr;
                            checkOut.set("minDate", dateStr);
                        },
                    });

                    const checkOut = flatpickr("#checkOutCalendar", {
                        dateFormat: "Y-m-d",
                        inline: true,
                        disable: [isDateDisabled],
                        onChange: function (selectedDates, dateStr) {
                            selectedCheckOut = dateStr;
                            checkIn.set("maxDate", dateStr);

                            // Validate the selected range
                            if (selectedCheckIn && selectedCheckOut && !isRangeValid(selectedCheckIn, selectedCheckOut)) {
                                alert("The selected range includes reserved dates. Please choose different dates.");
                                checkOut.clear(); // Clear the invalid check-out date
                                selectedCheckOut = null;
                            }
                        },
                    });
                });
                // Variables-Modal
                let modal = document.getElementById("imageModal");
                let modalImg = document.getElementById("modalImg");
                let closeBtn = document.querySelector(".close");
                let prevBtn = document.querySelector(".prev");
                let nextBtn = document.querySelector(".next");
                let images = document.querySelectorAll(".service-item img");
                let currentIndex = 0;

                // Open modal and display image
                images.forEach((img, index) => {
                    img.addEventListener("click", () => {
                        modal.style.display = "block";
                        modalImg.src = img.src;
                        currentIndex = index;
                    });
                });

                // Close modal
                closeBtn.addEventListener("click", () => {
                    modal.style.display = "none";
                });

                // Navigate to previous image
                prevBtn.addEventListener("click", () => {
                    currentIndex = (currentIndex - 1 + images.length) % images.length; // Wrap around
                    modalImg.src = images[currentIndex].src;
                });

                // Navigate to next image
                nextBtn.addEventListener("click", () => {
                    currentIndex = (currentIndex + 1) % images.length; // Wrap around
                    modalImg.src = images[currentIndex].src;
                });

                // Close modal when clicking outside the image
                window.addEventListener("click", (event) => {
                    if (event.target === modal) {
                        modal.style.display = "none";
                    }
                });

            </script>

            <a href="#" id="bookNowLink">
                <button class="book-now-button" onclick="handleBooking()">Book</button>
            </a>
        </div>
    </div>
    </div>
</body>

</html>