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
    <title>Host</title>
    <link rel="icon" href="fav.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/admin.css">

</head>

<body>
    <div class="section">
        <nav>
            <a href="accountSettings.php">Account Settings</a>
            <a href="viewProperties.php">View your properties</a>
            <a href="addProperty.php">Input Property</a>
            <a href='../admin/logOut.php'>Log Out</a>

        </nav>
        <header>
        <h1> Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
            <h3>Here you can manage your account settings and properties.</h3></header>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Booking Website. All rights reserved.</p>
    </footer>
</body>

</html>
</ul>