<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="logIn.css">
    <title>TJ Easy Stay</title>
    <style>
        a.button {
            display: inline-block;
            padding: 10px 20px;
            background-color: rgb(153, 107, 27); 
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        a.button:hover {
            background-color: rgb(194, 155, 107);
            transition: 0.5s;
        }
        button {
            display: inline-block;
            padding: 10px 20px;
            background-color: rgb(143, 107, 27); 
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    $showLinks = isset($_SESSION['showLinks']) ? $_SESSION['showLinks'] : false;
    if (isset($_POST['toggleLinks'])) {
        $_SESSION['showLinks'] = true; // Set session variable to show links
    }
    ?>
    
    <form action="" method="POST">
        <h2 class="greetings">New here?</h2>
        <button class="button" type="submit" name="toggleLinks">Choose how to sign up!</button>
    </form>
    <?php if ($showLinks): ?>
        <div class="links">
            <a href="logIn/client/clientSignUp.php" class="button">Client</a>
            <a href="logIn/host/hostSignUp.php" class="button">Host</a>
        </div>
    <?php endif; ?>
</body>
</html>
