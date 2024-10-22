<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: ../../userEntry/logIn.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Store</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap">
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>

<h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1> <!-- Display admin name -->

<div class="section">
    <nav>
    <a href='seeUsers.php'>View Users</a>
    <a href='logOut.php'>Log Out</a>
    </nav>
</div>

<footer id="footer">
    <?php echo date("Y"); ?> &deg; <span id="current-time"></span>
</footer>

<script>
    function updateTime() {
        var currentTime = new Date().toLocaleTimeString('en-BG', {timeZone: 'Europe/Sofia', hour12: false, hour: "2-digit", minute: "2-digit"});
        document.getElementById('current-time').textContent = currentTime;
    }

    setInterval(updateTime, 1000);
</script>
</body>
</html>
