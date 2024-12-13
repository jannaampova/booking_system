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
    <title>Users</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <style>
        .info-bubbles a {
            text-decoration: none;
            color: black;
        }

        .info-bubbles a:hover {
            background-color: #688587a2;
        }
    </style>
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
                <a href='seeUsers.php'>View Users</a>
                <a href='adminBoard.php'>Dashboard</a>
                <a href='logOut.php'>Log Out <i class="fa-solid fa-right-from-bracket"></i></a>

            </div>
        </div>
        <div class="column">
            <div class="first-line">
                <header>
                    <h1 style="margin-top:15%;">All registered users</h1>
                </header>
            </div>
            <div class="info-bubbles">
                <?php
                include "../../config.php";
                $sql = "SELECT id, roleName FROM Roles";
                $res = mysqli_query($dbConn, $sql);
                if ($res) {
                    while ($role = mysqli_fetch_assoc($res)) {
                        $roleID = $role['id'];
                        $roleName = $role['roleName'];
                        $userCount = mysqli_fetch_assoc(mysqli_query($dbConn, "SELECT COUNT(*) AS userCount FROM User WHERE roleID = $roleID"))['userCount'];
                        echo "
            <a href='userDetails.php?role=$roleID' id='info-bubble' class='info-bubble'>
                <p><b>" . ucfirst($roleName) . "s</b><br>
                    <i>$userCount</i><br>
                    <i class='fas fa-users'></i>
                </p>
            </a>";
                    }
                } else {
                    echo "<p>Error fetching roles.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>