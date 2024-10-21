<!DOCTYPE html>
<html>

<head>
    <title>Edit Clients</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap">
    <link rel="stylesheet" href="../../css/logIn.css">
</head>

<body>
<?php

include '../../config.php';
include '../../userEntry/functions.php';

if (isset($_GET['id'])) {
    $userID = intval($_GET['id']); // Sanitize user ID

    // Fetch user details from the database
    $sql = "SELECT * FROM User WHERE id = $userID";
    $result = mysqli_query($dbConn, $sql);

    if ($result && $user = mysqli_fetch_assoc($result)) {
        // Display the user's current data in a form for editing
        ?>
        <div class="container">
            <form class='form' method="POST" action=""> <!-- Assuming you will update in another file -->
                <input type="hidden" name="userID" value="<?php echo $userID; ?>">

                <div class="form-control"> <label>Full Name:</label>
                    <input type="text" name="fullName" value="<?php echo htmlspecialchars($user['fullName']); ?>" required><br>
                </div>
                <div class="form-control"> <label>Phone:</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required><br>
                </div>
                <div class="form-control"> <label>Username:</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>
                </div>
                <div class="form-control"> <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>
                </div>
                <div class="form-control">
                    <label>Role:</label>
                    <?php
                    // SQL query for fetching role name
                    $sql2 = "SELECT roleName FROM Roles WHERE id=" . $user['roleID'];
                    $res2 = mysqli_query($dbConn, $sql2);
                    $roleName = "Unknown Role"; // Default value in case no role is found
            
                    if ($res2 && $roleRow = mysqli_fetch_assoc($res2)) {
                        $roleName = $roleRow['roleName']; // Fetch role name if found
                    }
                    ?>
                    <input type="text" name="roleName" value="<?php echo htmlspecialchars($roleName); ?>" ><br>
                </div>
                <button type="submit" name="updateUser">Update User</button>
            </form>
        </div>
        <?php
    } else {
        echo "User not found.";
    }
} else {
    echo "No user ID specified.";
}


if (isset($_POST['updateUser'])) {
    $usernameInvalid = $emailInvalid = $telNumInvalid = $nameInvalid = '';
    // Retrieve the form inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $telNum = trim($_POST['phone']);
    $name = trim($_POST['fullName']);
    $userID = intval($_POST['userID']); // Make sure to retrieve user ID here

    // Validation logic
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailInvalid = "Not a valid email format.";
    }

    // Check for existing users with the same username, email, or phone number
    $sqlCheck = "SELECT * FROM User WHERE (email = '$email' OR username = '$username' OR phone = '$telNum') AND id != $userID";
    $resCheck = mysqli_query($dbConn, $sqlCheck);

    if ($resCheck && mysqli_num_rows($resCheck) > 0) {
        $row = mysqli_fetch_assoc($resCheck);
        if ($row['email'] === $email) {
            $emailInvalid = "Email already taken.";
        }
        if ($row['username'] === $username) {
            $usernameInvalid = "Username already taken.";
        }
        if ($row['phone'] === $telNum) {
            $telNumInvalid = "Phone already taken.";
        }
    }

    // Update user if no validation errors
    if (empty($usernameInvalid) && empty($emailInvalid) && empty($telNumInvalid) && empty($nameInvalid)) {
        // Get role ID based on role name (if needed)
        $roleID = null; // Assuming you want to update the role based on its name
        $sqlRole = "SELECT id FROM Roles WHERE roleName='" . mysqli_real_escape_string($dbConn, $_POST['roleName']) . "'";
        $resRole = mysqli_query($dbConn, $sqlRole);
        
        if ($resRole && $roleRow = mysqli_fetch_assoc($resRole)) {
            $roleID = $roleRow['id'];
        }

        // Update query
        $sql = "UPDATE User SET fullName='$name', email='$email', username='$username', phone='$telNum', roleID='$roleID' WHERE id=$userID";
        if (mysqli_query($dbConn, $sql)) {
            header("Location: seeUsers.php");
            exit(); // Always exit after a header redirect
        } else {
            echo "Error updating user: " . mysqli_error($dbConn);
        }
    }
}
?>
</body>
</html>
