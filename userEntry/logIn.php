<?php 
session_start(); // Start the session
include "../config.php";
include "functions.php";

if (isset($_POST['logIn'])) {
    $invalidUserName = "";
    $usernameClass = " ";
    $invalidPasswd = "";
    $passwdClass = " ";
    $invalidRole = " ";
    $roleClass = " ";
    $username = trim($_POST['username']);
    $passwd = trim($_POST['password']);
    $role = $_POST['selectRole'];

    $sql = "SELECT id FROM roles WHERE roleName='$role'";
    $res = mysqli_query($dbConn, $sql);
    $row = mysqli_fetch_assoc($res);
    $roleID = $row['id'];

    // Validation logic
    $errors = [];
    if (empty($username)) {
        setErrorFor($invalidUserName, $usernameClass, "Username cannot be blank.");
    }
    if (empty($passwd)) {
        setErrorFor($invalidPasswd, $passwdClass, "Password cannot be blank.");
    }

    // SQL query to check for username existence
    $sqlCheck = "SELECT * FROM User WHERE username = '$username'";
    $resCheck = mysqli_query($dbConn, $sqlCheck);

    if ($resCheck && mysqli_num_rows($resCheck) > 0) {
        $user = mysqli_fetch_assoc($resCheck); // Fetch user details

        // Verify password
        if (password_verify($passwd, $user['passwd'])) {
            // Password is correct
            if ($roleID == $user['roleID']) {
                // Store username in session
                $_SESSION['name'] = $user['fullName']; // Store admin name in session

                // Redirect based on role
                if ($roleID == 1) {
                    header("Location: ../dashboards/admin/adminBoard.php");
                } else if ($roleID == 3) {
                    header("Location: ../dashboards/client/clientBoard.php");
                } else if ($roleID == 2) {
                    header("Location: ../dashboards/host/hostBoard.php");
                } else {
                    setErrorFor($invalidRole, $roleClass, "Invalid role.");
                }
                exit();
            } else {
                setErrorFor($invalidRole, $roleClass, "User incompatible with the chosen role!");
            }
        } else {
            setErrorFor($invalidPasswd, $passwdClass, "Wrong password.");
        }
    } else {
        setErrorFor($invalidUserName, $usernameClass, "Username does not exist.");
    }

    // Display errors if any
    if (empty($invalidUserName) && empty($invalidPasswd)) {
        foreach ($errors as $field => $error) {
            echo "<p>Error in $field: $error</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="../css/logIn.css">
    <link rel="stylesheet" href="../css/select.css">
    <link rel="stylesheet" href="../css/button.css">
</head>
<body>
    <div class="container" id="vhod">
        <h2 class="greeting">Log In <br>Welcome!</h2>
        <form action="" class="form" method="POST" name="logInForm">
            <div class="form-control <?php echo htmlspecialchars($roleClass); ?>">
                <label for="name">Choose how you want to log in</label>
                <select name="selectRole" id="selectRole" required>
                    <?php
                    $sql = "SELECT * from roles";
                    $result = mysqli_query($dbConn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row["roleName"] . "'>" . $row["roleName"] . "</option>";
                    }
                    ?>
                </select>
                <i class="fas fa-check-circle"></i>
                <i class="fas fa-exclamation-circle"></i>
                <small><?php echo htmlspecialchars($invalidRole); ?></small>
            </div>
            <div class="form-control <?php echo htmlspecialchars($usernameClass); ?>">
                <label for="username">Username</label>
                <input type="text" name="username" placeholder="annamariya11" id="username">
                <small><?php echo htmlspecialchars($invalidUserName); ?></small>
            </div>
            <div class="form-control <?php echo htmlspecialchars($passwdClass); ?>">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="password" id="password">
                <small><?php echo htmlspecialchars($invalidPasswd); ?></small>
            </div>
            <button name="logIn" type="submit">Log In</button>
        </form>
    </div>
    <div class="links">
            <a href="signUp.php" class="button">Dont have an account? Sign Up!</a>
        </div>
</body>
</html>
