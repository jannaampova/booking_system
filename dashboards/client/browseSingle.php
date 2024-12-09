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
    <link rel="stylesheet" href="../../css/buttonAndSelect.css" />
    <link rel="stylesheet" href="../../css/client.css" />
    <link rel="stylesheet" href="../../css/addEditAdminHost.css" />
    <link rel="stylesheet" href="../../css/propInfo.css" />
    <link rel="stylesheet" href="../../css/admin.css" />
    <link rel="stylesheet" href="../../css/nav.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    include "../../config.php";
    ?>

    <div class="main">
        <div class="left-container">
            <div class="options">
                <?php
                $fullName = $_SESSION['name'];
                $firstName = explode(' ', $fullName)[0]; // Get the first name
                ?>
                <a href="../userSettings.php">
                    <i class="fas fa-user-edit"></i>
                    <?php echo htmlspecialchars($_SESSION['name']); ?>
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
                $sql = "SELECT * FROM Property WHERE id=$propertyId";
                $res = mysqli_query($dbConn, $sql);
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
                            $hostQuery = "SELECT fullName FROM User WHERE id = {$rowProperty['hostID']}";
                            $hostResult = mysqli_query($dbConn, $hostQuery);
                            $hostName = mysqli_fetch_assoc($hostResult)['fullName'];
                            echo htmlspecialchars($hostName);
                            ?>
                        </span>
                    </div>
                    <div class="property-info">
                        <label>Property Type: </label>
                        <span>
                            <?php
                            $typeQuery = "SELECT propType FROM PropertyType WHERE id = {$rowProperty['propTypeID']}";
                            $typeResult = mysqli_query($dbConn, $typeQuery);
                            $propType = mysqli_fetch_assoc($typeResult)['propType'];
                            echo htmlspecialchars($propType);
                            ?>
                        </span>
                    </div>
                    <div class="property-info">
                        <label>Maximum Guests: </label>
                        <span>
                            <?php
                            $guestQuery = "SELECT guestNum FROM GuestNumber WHERE id = {$rowProperty['guestNumID']}";
                            $guestResult = mysqli_query($dbConn, $guestQuery);
                            $guestNum = mysqli_fetch_assoc($guestResult)['guestNum'];
                            echo htmlspecialchars($guestNum);
                            ?>
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
                AND propStatus IN ('reserved', 'booked')
";
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

                    const isDateDisabled = (date) => {
                        const normalizeDate = (d) => {
                            const normalized = new Date(d);
                            normalized.setHours(0, 0, 0, 0); // Reset time
                            return normalized;
                        };

                        const normalizedDate = normalizeDate(date);
                        const today = normalizeDate(new Date());

                        if (normalizedDate < today) {
                            return true;
                        }

                        const isInReservedRange = availabilityRanges.reserved.some(range => {
                            const fromDate = normalizeDate(range.from);
                            const toDate = normalizeDate(range.to);
                            return normalizedDate >= fromDate && normalizedDate <= toDate;
                        });

                        if (isInReservedRange) {
                            return true;
                        }

                        const isInFreeRange = availabilityRanges.free.some(range => {
                            const fromDate = normalizeDate(range.from);
                            const toDate = normalizeDate(range.to);
                            return normalizedDate >= fromDate && normalizedDate <= toDate;
                        });

                        return !isInFreeRange;
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
                        },
                    });

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