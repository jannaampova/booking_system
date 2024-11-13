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
    <title>Store</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <style>
        .section a {

            color: #00272e;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="left-container">
        </div>
        <div class="column">
           <div class="first-line">
              <header>
                    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1> <!-- Display admin name -->
                </header>
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
                        <a href='seeUsers.php'>View Users</a>
                        <a href='logOut.php'>Log Out</a>
                    </nav>
                </div>
                </div> 

                <div class="info-bubbles">
                    <div class="info-bubble"><p>Properties <br>
<?php
include "../../config.php";
$propertyCounter=0;
$sql="SELECT id FROM Property";
$res=mysqli_query($dbConn,$sql);
while($row=mysqli_fetch_assoc($res)){
$propertyCounter++;
}
echo "$propertyCounter";

?>

                  </p>  </div>
                    <div class="info-bubble">a</div>
                    <div class="info-bubble">a</div>
                </div>
            
        </div>

    </div>


</body>

</html>