<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../logIn.css">
</head>
<body>
    <?php include "../../config.php"; ?>
    <div class="container" id="vhod">
        <h2>Логирай се!</h2>
        <form action="" class="form" method="POST" name="logInForm">
            <div class="form-control ">
                <label for="username">Username</label>
                <input type="text" name="username" placeholder="annamariya11" id="username">
                <small>Error message</small>
            </div> 
        
            <div class="form-control">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="password" id="password">
                <small>Error message</small>
            </div>

            <button name="logIn" type="submit">Log In</button> <!-- Changed button label from "Sign Up" to "Log In" -->
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['logIn'])) {
    // Retrieve the form inputs
    $username = trim($_POST['username']);
    $passwd = trim($_POST['password']);

    // Validation logic
    $errors = [];
    if (empty($username)) {
        $errors['username'] = 'Username cannot be blank';
    }
    if (empty($passwd)) {
        $errors['password'] = 'Password cannot be blank';
    }

    // SQL query to check for username existence
    $sqlCheck = "SELECT * FROM User WHERE username = '$username'";
    $resCheck = mysqli_query($dbConn, $sqlCheck);

    if ($resCheck && mysqli_num_rows($resCheck) > 0) {
        $user = mysqli_fetch_assoc($resCheck); // Fetch user details

        // Debugging output
        echo "Stored password hash: " . $user['passwd'] . "<br>"; // Show the stored hash
        echo "Entered password: " . $passwd . "<br>"; // Show the entered password
        
        // Verify password
        if (password_verify($passwd, $user['passwd'])) {
            // Password is correct
            header("Location: ../../dashboards/clientBoard.php");
            exit();
        } else {
            $errors['password'] = 'Wrong password';
        }
    } else {
        $errors['username'] = 'User does not exist!';
    }

    // Display errors if any
    if (!empty($errors)) {
        foreach ($errors as $field => $error) {
            echo "<p>Error in $field: $error</p>";
        }
    }
}

?>
