<?php
session_start();
include "../../config.php";

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
    <title>Payment</title>
    <link rel="stylesheet" href="../../css/payment.css">  
</head>

<body>
    <div class="main">
        <div id="signUp">
            <div class="container">s
                <form action="" method="POST" class="form">
                    <div class="greeting">
                        <?php
                        $price = $_GET['totalPrice'];
                        echo "<h2>Payment</h2> <br><h3>You need to pay $price before cancelling your booking</h3>";
                        ?>

                    </div>
                    <div class="rightside">
                        <h1>CheckOut</h1>
                        <h2>Payment Information</h2>
                        <p>Cardholder Name</p>
                        <input type="text" class="inputbox" name="name" required />
                        <p>Card Number</p>
                        <input type="number" class="inputbox" name="card_number" id="card_number" required />

                        <p>Card Type</p>
                        <select class="inputbox" name="card_type" id="card_type" required>
                            <option value="">--Select a Card Type--</option>
                            <option value="Visa">Visa</option>
                            <option value="RuPay">RuPay</option>
                            <option value="MasterCard">MasterCard</option>
                        </select>
                        <div class="expcvv">

                            <p class="expcvv_text">Expiry</p>
                            <input type="date" class="inputbox" name="exp_date" id="exp_date" required />

                            <p class="expcvv_text2">CVV</p>
                            <input type="password" class="inputbox" name="cvv" id="cvv" required />
                        </div>
                        <p></p>
                        <button type="submit" class="button">CheckOut</button>
                </form>
            </div>

            </form>
        </div>
    </div>
    </div>
</body>

</html>