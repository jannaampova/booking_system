<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: ../../userEntry/logIn.php"); // Redirect to login if not logged in
    exit();
}
include "../../config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Bookings</title>
    <link rel="stylesheet" href="../../css/addEditAdminHost.css" />
    <link rel="stylesheet" href="../../css/propInfo.css" />
    <link rel="stylesheet" href="../../css/admin.css" />
    <link rel="stylesheet" href="../../css/nav.css" />
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <style>
        h2,
        h3 {
            margin-top: 0;
            color: #222;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .property-details h3 {
            color: orange;
            margin: 0;
        }

        button {
            background-color: #688587;
            color: white;
            border: none;
            font-size: 14px;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #688587a2;
        }

        .property-info {
            margin-bottom: 25px;
            padding: 0;
            border-bottom: 1px solid #eee;
        }

        .property-details {
            width: 100%;
            font-family: 'Poppins', sans-serif;
            color: #444;
            line-height: 1.8;
            font-size: 18px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 15px;
            background-color: #fff;
            margin-top: 5%;
        }

        .inner-flex-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 500px;
        }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: darkslategray;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="left-container">
            <div class="options">
                <a href="../userSettings.php">
                    <i class="fas fa-user-edit"></i>
                    <?php echo htmlspecialchars($_SESSION['name']); ?>
                </a>
                <a href="adminBoard.php">Dashboard</a>
                <a href="seeUsers.php">View Users</a>
                <a href="logOut.php">Log Out <i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>

        <div class="inner-flex">
            <div class="inner-flex-header">
                <h2 class="section-title">Host's details</h2>
            </div>
            <div class="detail">
                <?php
                $role = $_GET['role'];
                $sql = "SELECT id, fullName, phone, username, email FROM User WHERE roleID = $role";
                $res = mysqli_query($dbConn, $sql);

                if (mysqli_num_rows($res) > 0) {
                    while ($mainRow = mysqli_fetch_assoc($res)) {
                        $host = $mainRow['id'];

                        $sqlCounts = "SELECT 
                                        (SELECT COUNT(DISTINCT b.clientID) 
                                         FROM Booking b 
                                         JOIN Property p ON b.propID = p.id 
                                         WHERE p.hostID = $host) AS clientCount,
                                        (SELECT COUNT(b.id) 
                                         FROM Booking b 
                                         JOIN Property p ON b.propID = p.id 
                                         WHERE p.hostID = $host) AS bookingCount,
                                        (SELECT COUNT(p.id) 
                                         FROM Property p 
                                         WHERE p.hostID = $host) AS propertyCount";
                        $result = mysqli_query($dbConn, $sqlCounts);

                        if ($counts = mysqli_fetch_assoc($result)) {
                            $clientCount = $counts['clientCount'];
                            $bookingCount = $counts['bookingCount'];
                            $propertyCount = $counts['propertyCount'];
                        } else {
                            $clientCount = $bookingCount = $propertyCount = 0;
                        }

                        echo "
                            <div class='property-details'>
                                <div class='property-info'>
                                    <h3>" . htmlspecialchars($mainRow['fullName']) . "</h3>
                                    <p><b>Clients:</b> " . htmlspecialchars($clientCount) . "</p>
                                    <p><b>Bookings:</b> " . htmlspecialchars($bookingCount) . "</p>
                                    <p><b>Properties:</b> " . htmlspecialchars($propertyCount) . "</p>
                                </div>
                                <div class='property-info-buttons'>
                                <form method='post' action='viewHostProperties.php?hostID=$host'>
                                    <input type='hidden' name='hostID' value='<?php echo $host; ?>'>
                                    <button type='submit'>View Properties</button>
                                </form>
                                </div>
                            </div>
                            
                        ";
                    }
                } else {
                    echo "<p>No hosts</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>