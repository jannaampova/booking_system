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
    <style>
        .main {
            width: 100%;
            height: 127vh;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5) 50%, rgba(0, 0, 0, 0.5) 50%), url(../../newImg.jpg);
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
            backdrop-filter: blur(15px);
            color: #f9f4f4;
            border-radius: 60px;
            box-shadow: 0 10px 12px rgba(12, 12, 12, 0.814);
            margin: 0 auto;
            justify-content: center;
            width: 60%;
            margin-top: 50px;
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
            padding: 10px 10px;
            margin: 10% 0;
        }

        .form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #ff7200;
        }

        .form-control {
            margin-bottom: 5px;
            padding-bottom: 10px;
            color: #2d2a26;
        }

        ::placeholder {
            color: rgb(252, 246, 246);
            opacity: 1;
            /* Firefox */
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
            color: rgb(252, 246, 246);
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

        .rightside {
    background-color: #ffffff;
	width: 35rem;
	border-bottom-right-radius: 1.5rem;
    border-top-right-radius: 1.5rem;
    padding: 1rem 2rem 3rem 3rem;
}

p{
    display:block;
    font-size: 1.1rem;
    font-weight: 400;
    margin: .8rem 0;
}

.inputbox
{
    color:#030303;
	width: 100%;
    padding: 0.5rem;
    border: none;
    border-bottom: 1.5px solid #ccc;
    margin-bottom: 1rem;
    border-radius: 0.3rem;
    font-family: 'Roboto', sans-serif;
    color: #615a5a;
    font-size: 1.1rem;
    font-weight: 500;
  outline:none;
}

.expcvv {
    display:flex;
    justify-content: space-between;
    padding-top: 0.6rem;
}

.expcvv_text{
    padding-right: 1rem;
}
.expcvv_text2{
    padding:0 1rem;
}

.button{
    background: linear-gradient(
135deg
, #753370 0%, #298096 100%);
    padding: 15px;
    border: none;
    border-radius: 50px;
    color: white;
    font-weight: 400;
    font-size: 1.2rem;
    margin-top: 10px;
    width:100%;
    letter-spacing: .11rem;
    outline:none;
}

.button:hover
{
	transform: scale(1.05) translateY(-3px);
    box-shadow: 3px 3px 6px #38373785;
}

@media only screen and (max-width: 1000px) {
    .card{
        flex-direction: column;
        width: auto;
      
    }

    .leftside{
        width: 100%;
        border-top-right-radius: 0;
        border-bottom-left-radius: 0;
      border-top-right-radius:0;
      border-radius:0;
    }

    .rightside{
        width:auto;
        border-bottom-left-radius: 1.5rem;
        padding:0.5rem 3rem 3rem 2rem;
      border-radius:0;
    }
    </style>
</head>

<body>
    <div class="main">
        <div id="signUp">
            <div class="container">s
                <form action="" method="POST" class="form">
                    <div class="greeting">
                    <?php
                    $price=$_GET['totalPrice'];
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