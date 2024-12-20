
<?php
session_start();

if (!isset($_SESSION['name'])) {
    header("Location: ../../userEntry/logIn.php"); // Redirect to login if not logged in
    exit();

}
$ID=$_SESSION['userID'];
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../../css/logIn.css">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="main" id="main">
        <div class="icon">
            <a href="../home.php" class="logo">
                <h3>TJ</h3>
                <p>
                    <h6>EasyStay</h6>
                </p>
            </a>
        </div>
        <?php
        include "../../config.php";
        include "../../userEntry/functions.php";
        include "../../emailing.php";


        // Check if the form is submitted
        if (isset($_POST['signUp'])) {
            $usernameInvalid = $emailInvalid = $telNumInvalid = $nameInvalid = $passwdInvalid = $passwd2Invalid = '';
            $classUsername = $classEmail = $classTelNum = $className = $classPasswd = $classPasswd2 = '';
            // Retrieve the form inputs
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $telNum = trim($_POST['telNum']);
            $name = trim($_POST['name']);
            $passwd = trim($_POST['password']);
            $passwd2 = trim($_POST['password2']);

            // Validation logic
            // Check if username is empty
            if (empty($username)) {
                setErrorFor($usernameInvalid, $classUsername, "Username is required.");
            } else {
                setSuccessFor($classUsername);
            }

            // Check if email is valid
            if (empty($email)) {
                setErrorFor($emailInvalid, $classEmail, "Email cannot be blank.");
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                setErrorFor($emailInvalid, $classEmail, "Not a valid email format.");
            } else {
                setSuccessFor($classEmail);
            }


            // Check if phone number is provided
            if (empty($telNum)) {
                setErrorFor($telNumInvalid, $classTelNum, "Phone number cannot be blank.");
            } else {
                setSuccessFor($classTelNum);
            }

            // Check if name is provided
            if (empty($name)) {
                setErrorFor($nameInvalid, $className, "Name cannot be blank.");
            } else {
                setSuccessFor($className);
            }

            // Check if passwords are valid and match
            if (empty($passwd)) {
                setErrorFor($passwdInvalid, $classPasswd, "Password cannot be blank.");
            } else {
                setSuccessFor($classPasswd);
            }

            if (empty($passwd2)) {
                setErrorFor($passwd2Invalid, $classPasswd2, "Repeat password.");
            } elseif ($passwd !== $passwd2) {
                setErrorFor($passwd2Invalid, $classPasswd2, "Passwords do not match.");
            } else {
                setSuccessFor($classPasswd2);
            }

            // Check for existing users with the same username, email, or phone number
            $sqlCheck = "SELECT * FROM User WHERE email = '$email' OR username = '$username' OR phone = '$telNum'";
            $resCheck = mysqli_query($dbConn, $sqlCheck);

            if ($resCheck && mysqli_num_rows($resCheck) > 0) {
                $row = mysqli_fetch_assoc($resCheck);
                if ($row['email'] === $email) {
                    setErrorFor($emailInvalid, $classEmail, "Email already taken");

                }
                if ($row['username'] === $username) {
                    setErrorFor($usernameInvalid, $classUsername, "Username already taken.");

                }
                if ($row['phone'] === $telNum) {
                    setErrorFor($telNumInvalid, $classTelNum, "Phone already taken.");

                }
            }

            if (empty($usernameInvalid) && empty($emailInvalid) && empty($telNumInvalid) && empty($nameInvalid) && empty($passwdInvalid) && empty($passwd2Invalid)) {
                $hashedPassword = password_hash($passwd, PASSWORD_BCRYPT); 
                $sql = "INSERT INTO User (fullName, email, username, phone, passwd, roleID) 
                VALUES ('$name', '$email', '$username', '$telNum', '$hashedPassword', 1)";

                $result = mysqli_query($dbConn, $sql);

                if ($result) {
                    sendEmail($email, $name, 3, '', '$ID','');
                } else {
                    die('Database Error: ' . mysqli_error($dbConn));
                }
            }
        }
        ?>
        <!-----------------------------------------------------------------HTML-------------------------------------------------------------->
        <div class="container" id="vhod">
            <form action="" class="form" method="POST" name="signUpForm" id="signUp" >

                <div class="form-control <?php echo htmlspecialchars($className); ?>">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" placeholder="Jana Ampova" id="name">
                    <i class="fas fa-check-circle"></i>
                    <i class="fas fa-exclamation-circle"></i>
                    <small><?php echo htmlspecialchars($nameInvalid); ?></small>
                </div>
                <div class="form-control <?php echo htmlspecialchars($classUsername); ?>">
                    <label for="username">Username</label>
                    <input type="text" name="username" placeholder="jana" id="username">
                    <i class="fas fa-check-circle"></i>
                    <i class="fas fa-exclamation-circle"></i>
                    <small><?php echo htmlspecialchars($usernameInvalid); ?></small>
                </div>
                <div class="form-control <?php echo htmlspecialchars($classEmail); ?>">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="jana@mail.com" id="email">
                    <i class="fas fa-check-circle"></i>
                    <i class="fas fa-exclamation-circle"></i>
                    <small><?php echo htmlspecialchars($emailInvalid); ?></small>
                </div>
                <div class="form-control <?php echo htmlspecialchars($classPasswd); ?>">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="" id="password">
                    <i class="fas fa-check-circle"></i>
                    <i class="fas fa-exclamation-circle"></i>
                    <small><?php echo htmlspecialchars($passwdInvalid); ?></small>
                </div>
                <div class="form-control <?php echo htmlspecialchars($classPasswd2); ?>">
                    <label for="password2">Repeat Password</label>
                    <input type="password" name="password2" placeholder="" id="password2">
                    <i class="fas fa-check-circle"></i>
                    <i class="fas fa-exclamation-circle"></i>
                    <small><?php echo htmlspecialchars($passwd2Invalid); ?></small>
                </div>
                <div class="form-control <?php echo htmlspecialchars($classTelNum); ?>">
                    <label for="telNum">Phone</label>
                    <input type="tel" name="telNum" id="telNum" placeholder="+359 XXX XXX XXX"
                        pattern="\+359\s?\d{3}\s?\d{3}\s?\d{3}">
                    <i class="fas fa-check-circle"></i>
                    <i class="fas fa-exclamation-circle"></i>
                    <small><?php echo htmlspecialchars($telNumInvalid); ?></small>
                </div>
                <div class="form-control">
                    <button name="signUp" type="submit">Create an Admin account</button>
                </div>

            </form>

        </div>
    </div>
</body>

</html>