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
    <title>Client</title>
    <link rel="icon" href="fav.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/client.css">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>


</head>

<body>
    <div class="main" id="main">
        <div class="section">

            <nav>
                <?php
                $fullName = $_SESSION['name'];
                $firstName = explode(' ', $fullName)[0];
                ?>
                <a href="hostSettings.php">
                    <i class="fas fa-user-edit"></i>
                    <?php echo htmlspecialchars($firstName); ?>
                </a>
                <a href='../admin/logOut.php'>Log Out</a>
            </nav>

            <header>
                <h1> Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
                <div class="search-bubble">
                    <div class="search-field">
                        <input type="search" placeholder="Where?">
                    </div>
                    <div class="search-field">
                        <input type="text" placeholder="Check In" onfocus="(this.type='date')">
                    </div>
                    <div class="search-field">
                    <input type="text" placeholder="Check Out" onfocus="(this.type='date')">
                    </div>
                    <div class="search-field">
                        <input type="search" placeholder="Travelers">
                    </div>
                    <button type="'submit" name="search"><i class="fas fa-search"></i></button>
                </div>
            </header>
            <?php



            ?>

        </div>
    </div>

</body>

</html>
</ul>