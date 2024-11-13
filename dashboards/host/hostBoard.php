<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: ../../userEntry/logIn.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host</title>
    <link rel="icon" href="fav.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/userOptions.css">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>


</head>

<body>
    <div class="section">
        <nav>
            <?php
            $fullName = $_SESSION['name'];
            $firstName = explode(' ', $fullName)[0]; // Get the first name
            ?>
            <a href="hostSettings.php">
                <i class="fas fa-user-edit"></i>
                <?php echo htmlspecialchars($firstName); ?>
            </a>
            <a href="viewProperties.php">View your properties</a>
            <a href="addProperty.php">Input Property</a>
            <a href='../admin/logOut.php'>Log Out</a>
        </nav>
        <header>
            <h1> Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
            <h3>Here you can manage your account settings and properties.</h3>
        </header>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Booking Website. All rights reserved.</p>
    </footer>
</body>

</html>
</ul>