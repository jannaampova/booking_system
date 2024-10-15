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
        <!-- Add method="POST" to submit form data to the PHP -->
        <form action="" class="form" method="POST" name="signUpForm">
            <div class="form-control ">
                <label for="name">Full Name</label>
                <input type="text" name="name" placeholder="Jana Ampova" id="name">
                <small>Error message</small>
            </div> 
            <div class="form-control ">
                <label for="username">Username</label>
                <input type="text" name="username" placeholder="annamariya11" id="username">
                <small>Error message</small>
            </div> 
            <div class="form-control">
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="hello@annamariya.com" id="email">
                <small>Error message</small>
            </div>

            <div class="form-control">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="password" id="password">
                <small>Error message</small>
            </div>

            <div class="form-control">
                <label for="password2">Repeat Password</label>
                <input type="password" name="password2" placeholder="check password" id="password2">
                <small>Error message</small>
            </div>

            <div class="form-control">
                <label for="telNum">Phone</label>
                <input type="text" name="telNum" placeholder="Phone number" id="telNum">
                <small>Error message</small>
            </div>

            <button name="signUp" type="submit">Sign Up</button>
        </form>
    </div>
</body>
</html>


<?php
if (isset($_POST['signUp'])) 
{
    // Retrieve the form inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $telNum = trim($_POST['telNum']);
    $name = trim($_POST['name']);
    $passwd = trim($_POST['password']);
    $passwd2 = trim($_POST['password2']);
    
    // Validation logic
    $errors = [];

    // Check if username is empty
    if (empty($username)) {
        $errors['username'] = 'Username cannot be blank';
    }

    // Check if email is valid
    if (empty($email)) {
        $errors['email'] = 'Email cannot be blank';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Not a valid email';
    }

    // Check if phone number is provided
    if (empty($telNum)) {
        $errors['telNum'] = 'Phone number cannot be blank';
    }

    // Check if name is provided
    if (empty($name)) {
        $errors['name'] = 'Name cannot be blank';
    }

    // Check if passwords are valid and match
    if (empty($passwd)) {
        $errors['password'] = 'Password cannot be blank';
    }

    if (empty($passwd2)) {
        $errors['password2'] = 'Password confirmation cannot be blank';
    } elseif ($passwd !== $passwd2) {
        $errors['password2'] = 'Passwords do not match';
    }

    // Check for existing users with the same username, email, or phone number
    $sqlCheck = "SELECT * FROM User WHERE email = '$email' OR username = '$username' OR phone = '$telNum'";
    $resCheck = mysqli_query($dbConn, $sqlCheck);
    
    if ($resCheck && mysqli_num_rows($resCheck) > 0) {
        $row = mysqli_fetch_assoc($resCheck);
        if ($row['email'] === $email) {
            $errors['email'] = 'Email is already taken';
        }
        if ($row['username'] === $username) {
            $errors['username'] = 'Username is already taken';
        }
        if ($row['phone'] === $telNum) {
            $errors['telNum'] = 'Phone number is already taken';
        }
    }

    // If no errors, proceed with database insertion
    if (empty($errors)) {
        $hashedPassword = password_hash($passwd, PASSWORD_BCRYPT); // Hash password
        
        // SQL query to insert the user into the database
        $sql = "INSERT INTO User (fullName, email, username, phone, passwd, roleID) 
                VALUES ('$name', '$email', '$username', '$telNum', '$hashedPassword', 2)";

        // Execute the query
        $result = mysqli_query($dbConn, $sql);

        if ($result) {
            // Redirect to another page after successful registration
            header("Location: ../../homePage.php");
            exit(); // Always exit after a header redirect
        } else {
            // Handle error
            die('Database Error: ' . mysqli_error($dbConn));
        }
    } else {
        // Display errors to the user
        foreach ($errors as $field => $error) {
            echo "<p>Error in $field: $error</p>";
        }
    }
}
?>
