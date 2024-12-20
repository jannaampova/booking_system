<?php
session_start();
if (!isset($_SESSION['name'])) {
    header("Location: ../../userEntry/logIn.php"); 
    exit();
}
$hostID = $_SESSION['userID']; 
?>
<!DOCTYPE html>
<html>

<head>
    <title>All Bookings</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../css/table.css">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <link rel="stylesheet" href="../../css/allBookings.css">

</head>

<body>
    <div class="main">
        <div class="left-container">
            <div class="options">
                <?php
                $firstName = explode(' ', $_SESSION['name'])[0];
                ?>
                <a href="hostSettings.php">
                    <i class="fas fa-user-edit"></i>
                    <?php echo htmlspecialchars($firstName); ?>
                </a>
                <a href="hostBoard.php">Dashboard</a>
                <a href='../admin/logOut.php'>Log Out <i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>

        <div class="table-container">
            <?php
            include "../../config.php";

            // Handle the filter form submission
            $filterQuery = "WHERE p.hostID = $hostID";
            if (isset($_POST['filterBy']) && !empty($_POST['filterValue'])) {
                $filterBy = $_POST['filterBy'];
                $filterValue = mysqli_real_escape_string($dbConn, $_POST['filterValue']);

                if ($filterBy === 'status') {
                    $filterQuery = "WHERE bookingStatus = '$filterValue'";
                } elseif ($filterBy === 'client') {
                    $filterQuery = "WHERE u.fullName = '$filterValue'";
                }
            }

            // Query to fetch data with optional filter
            $sqlRole = "
    SELECT 
        b.id AS bookingID,
        b.bookingStatus,
        b.fromDate,
        b.toDate,
        b.clientID,
        b.propID,
        p.propName,
        u.fullName AS clientName,
        uh.fullName AS hostName
    FROM Booking b
    JOIN Property p ON b.propID = p.id
    JOIN User u ON b.clientID = u.id
    JOIN User uh ON p.hostID = uh.id
$filterQuery
";


            // Fetch all hosts and clients for dropdown options
            $statusSql = "SELECT DISTINCT bookingStatus from Booking";
            $status = mysqli_query($dbConn, $statusSql);
            $clients = mysqli_query($dbConn, "SELECT DISTINCT u.fullName AS clientName FROM User u JOIN Booking b ON u.id = b.clientID");
            ?>
            <h1>All Bookings:</h1>
            <form method="post" action="">
                <label for="filterBy">Filter By:</label>
                <select name="filterBy" id="filterBy" onchange="this.form.submit()" required>
                    <option value="">Select Filter</option>
                    <option value="status" <?php echo (isset($_POST['filterBy']) && $_POST['filterBy'] === 'status') ? 'selected' : ''; ?>>Status</option>
                    <option value="client" <?php echo (isset($_POST['filterBy']) && $_POST['filterBy'] === 'client') ? 'selected' : ''; ?>>Client</option>
                </select>

                <label for="filterValue">Select Value:</label>
                <select name="filterValue" id="filterValue" required>
                    <option value="">Select Value</option>
                    <?php
                    if (isset($_POST['filterBy']) && $_POST['filterBy'] === 'status') {
                        if ($status && mysqli_num_rows($status) > 0) {
                            while ($row = mysqli_fetch_assoc($status)) {
                                $selected = (isset($_POST['filterValue']) && $_POST['filterValue'] === $row['bookingStatus']) ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($row['bookingStatus']) . "' $selected>" . htmlspecialchars($row['bookingStatus']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No status found</option>";
                        }
                    } elseif (isset($_POST['filterBy']) && $_POST['filterBy'] === 'client') {
                        if ($clients && mysqli_num_rows($clients) > 0) {
                            while ($row = mysqli_fetch_assoc($clients)) {
                                $selected = (isset($_POST['filterValue']) && $_POST['filterValue'] === $row['clientName']) ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($row['clientName']) . "' $selected>" . htmlspecialchars($row['clientName']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No clients found</option>";
                        }
                    }
                    ?>
                </select>
                <label for="sortBy">Sort By Date:</label>
                <select name="sortBy" id="sortBy" onchange="this.form.submit()">
                    <option value="">Select Filter</option>
                    <option value="asc">Earliest</option>
                    <option value="desc">Latest</option>
                </select>

                <button type="submit">Filter</button>
            </form>

            <!-- Display Bookings -->
            <?php
            if (isset($_POST['sortBy'])) {
                $sortBy = $_POST['sortBy'] ?? '';
                switch ($sortBy) {
                    case 'asc':
                        $sqlRole .= "ORDER BY b.fromDate ASC";
                        break;
                    case 'desc':
                        $sqlRole .= "ORDER BY b.fromDate DESC";
                        break;

                }
            }
            $result = mysqli_query($dbConn, $sqlRole);

            if ($result && mysqli_num_rows($result) > 0): ?>

                <table>
                    <tr>
                        <th>Status</th>
                        <th>Stay</th>
                        <th>Client Name</th>
                        <th>Property Name</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['bookingStatus']); ?></td>
                            <td><?php echo htmlspecialchars($row['fromDate']); ?> to
                                <?php echo htmlspecialchars($row['toDate']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['clientName']); ?></td>
                            <td><?php echo htmlspecialchars($row['propName']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No bookings found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>