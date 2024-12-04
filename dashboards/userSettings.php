<?php
session_start();
include "../config.php";

if (!isset($_SESSION['name'])) {
    header("Location: logIn.php");
    exit();
}

// Retrieve user details
$username = $_SESSION['name'];
$sql = "SELECT * FROM User WHERE fullName = ?";
$stmt = $dbConn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found in the database.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $errors = [];

    // Validate inputs
    if (empty($fullName)) {
        $errors['fullName'] = "Full name cannot be blank.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Enter a valid email address.";
    }
    if (empty($phone) || !preg_match('/^[0-9]{10}$/', $phone)) {
        $errors['phone'] = "Enter a valid phone number.";
    }
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    } else {
        $hashedPassword = $user['passwd']; // Keep current password
    }

    if (empty($errors)) {
        // Update user data
        $sqlUpdate = "UPDATE User SET fullName = ?, email = ?, phone = ?, passwd = ? WHERE id = ?";
        $stmt = $dbConn->prepare($sqlUpdate);
        $stmt->bind_param('ssssi', $fullName, $email, $phone, $hashedPassword, $user['id']);

        if ($stmt->execute()) {
            $_SESSION['name'] = $fullName; // Update session name
            echo "<script>alert('Settings updated successfully.');</script>";
        } else {
            echo "<script>alert('Failed to update settings.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <style>
        /* CSS from your provided styles */
        .main {
            width: 100%;
            height: 127vh;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5) 50%, rgba(0, 0, 0, 0.5) 50%), url(../newImg.jpg);
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        #signUp {
            display: flex;
            flex-direction: column;
            margin: 30px;
            backdrop-filter: blur(10px);
            color: #f9f4f4;
            border-radius: 80px;
            box-shadow: 0 10px 12px rgba(12, 12, 12, 0.814);
            margin: 0 auto;
            justify-content: center;
            width: 60%;
            margin-top: 50px;
            /* Added margin-top */
        }

        .container {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 50%);
            border-radius: 20px;
            width: 50%;
            margin: 0 auto;
            padding-top: 6%;
            padding-bottom: 6%;
        }

        .form {
            padding: 30px 20px;
            margin: 10% 0;
        }

        .form-control {
            margin-bottom: 5px;
            padding-bottom: 10px;
            color: #2d2a26;
        }

        .form-control label {
            display: block;
            margin-bottom: 3%;
            color: rgb(170, 164, 157);
        }

        .form-control input {
            background: #68858775;
            border: 2px solid #000000;
            border-radius: 15px;
            display: block;
            width: 100%;
            font-size: 14px;
            padding: 15px;
        }

        .form-control button,
        .form-control a {
            background-color: #ff7200;
            border: 2px solid #ff7200;
            color: black;
            border-radius: 20px;
            padding: 12px;
            margin-top: 20px;
            display: block;
            margin: 0 auto;
            width: 30%;
            text-align: center;
            text-decoration: none;
        }

        .form-control button:hover,
        .form-control a:hover {
            background-color: #ff7200;
            transition: 0.5s;
            cursor: pointer;
        }

        .greeting {
            color: #624e24;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="main">
        <div id="signUp">
            <div class="container">
                <form action="" method="POST" class="form">
                    <div class="greeting">
                        <h2>Settings for <?php echo htmlspecialchars($_SESSION['name']); ?></h2>
                    </div>

                    <div class="form-control">
                        <label for="fullName">Full Name</label>
                        <input type="text" name="fullName" id="fullName"
                            value="<?php echo htmlspecialchars($user['fullName'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        <small><?php echo $errors['fullName'] ?? ''; ?></small>
                    </div>

                    <div class="form-control">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email"
                            value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        <small><?php echo $errors['email'] ?? ''; ?></small>
                    </div>

                    <div class="form-control">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone"
                            value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                        <small><?php echo $errors['phone'] ?? ''; ?></small>
                    </div>

                    <div class="form-control">
                        <label for="password">New Password (optional)</label>
                        <input type="password" name="password" id="password"
                            placeholder="Leave blank to keep current password">
                    </div>

                    <div class="form-control" style="display:flex;flex-direction:row;">
                        <button type="submit" style="font-size:16px;">Save</button>
                        <a href="javascript:history.back()">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>