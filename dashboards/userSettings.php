<?php
session_start();
include "../config.php";

if (!isset($_SESSION['name'])) {
    header("Location: logIn.php");
    exit();
}

// Retrieve user details
$username = $_SESSION['name'];
$id = $_SESSION['userID'];
$sql = "SELECT * FROM User WHERE id = ?";
$stmt = $dbConn->prepare($sql);
$stmt->bind_param("s", $id);
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
    <link rel="stylesheet" href="../css/payment.css">

    <title>User Settings</title>
    <style>
        .main {
    background: linear-gradient(to top, rgba(0, 0, 0, 0.5) 50%, rgba(0, 0, 0, 0.5) 50%), url(../newImg.jpg);
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
}
    </style>
  
</head>

<body>
    <div class="main">
        <div id="signUp">
            <div class="container">
                <form action="" method="POST" class="form">
                    <div class="greeting">
                        <h2>Settings</h2>
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