<?php
session_start();
include "../../config.php";

if (!isset($_SESSION['name'])) {
    header("Location: logIn.php");
    exit();
}

$username = $_SESSION['name'];
$id = $_SESSION['userID'];
$totalPrice = $_GET['totalPrice'];
$sql = "SELECT * FROM User WHERE id = ?";
$stmt = $dbConn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if (!$user) {
    die("User not found in the database.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/payment.css">
    <title>Payment</title>
   
</head>

<body>
    <div class="main">
        <div id="signUp">
            <div class="container">
                <form action="" method="POST" class="form">
                    <div class="greeting">
                        <?php echo "<h2>Payment for $$totalPrice </h2>"; ?>
                    </div>

                    <div class="form-control">
                        <label for="fullName">Full Name</label>
                        <input type="text" name="fullName" id="fullName"
                            value="<?php echo htmlspecialchars($user['fullName'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                        <small><?php echo $errors['fullName'] ?? ''; ?></small>
                    </div>

                    <div class="form-control">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email"
                            value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        <small><?php echo $errors['email'] ?? ''; ?></small>
                    </div>

                    <div class="form-control">
                        <label for="password">Type in the recieved code</label>
                        <input type="text" name="code" id="code">
                    </div>

                    <div class="form-control" style="display:flex;flex-direction:row;">
                        <button type="submit" style="font-size:16px;" name="getCode">Get Code</button>
                        <button type="submit" style="font-size:16px;" name="confirm">Confirm Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
<?php

include "../../emailing.php";
include "../../config.php";
function generateRandomCode($length = 6)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomCode = '';
    for ($i = 0; $i < $length; $i++) {
        $randomCode .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomCode;
}

$bookID = $_GET['bookId'];
$source = $_GET['source'];
$propId = $_GET['propId'];
$from = $_GET['from'];
$to = $_GET['to'];

$sql = "SELECT u.fullName as clientName, u.email as email, p.propName as propName From Booking b
join User u on u.id=b.clientID 
join Property p on p.id=b.propID where
b.id=$bookID";
$res = mysqli_query($dbConn, $sql);
$row = mysqli_fetch_assoc($res);
$name = $row['clientName'];
$email = $row['email'];
$propName = $row['propName'];

if (isset($_POST['getCode'])) {
    $code = generateRandomCode();
    $_SESSION['generated_code'] = $code;
    sendEmail($email, $name, '3', $propName, '', $code);
}

if (isset($_POST['confirm'])) {
    $inputCode = $_POST['code'];

    if (isset($_SESSION['generated_code']) && $inputCode === $_SESSION['generated_code']) {
        $updatePaySql = "UPDATE Payment SET paymentStatus = 'paid', amount = $totalPrice WHERE bookingID = $bookID";
        mysqli_query($dbConn, $updatePaySql);
        unset($_SESSION['generated_code']);
        if ($source === 'cancelBooking') {
            $sql = "UPDATE Availabilities SET propStatus = 'free' WHERE propID = $propId AND fromDate = '$from' AND toDate = '$to'";
            mysqli_query($dbConn, $sql);
            $sql = "UPDATE Booking SET bookingStatus = 'cancelled' WHERE propID = $propId AND fromDate = '$from' AND toDate = '$to'";
            mysqli_query($dbConn, $sql);
        }
        echo "<script type='text/javascript'>window.location.href = 'yourBookings.php';</script>";
        exit();
    } else {
        echo "Invalid code. Please try again.";
    }
}

?>