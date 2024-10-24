
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host</title>
    <link rel="icon" href="fav.png" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        nav {
            background-color: #333;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        nav ul li {
            float: left;
        }
        nav ul li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        nav ul li a:hover {
            background-color: #111;
        }
        main {
            padding: 1rem;
        }
        section {
            background-color: white;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1rem;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Host Dashboard</h1>
    </header>
    <nav>
        <ul>
            <li><a href="accountSettings.php">Account Settings</a></li>
            <li><a href="properties.php">Properties</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Welcome to the Booking Website</h2>
            <p>This is your host dashboard where you can manage your account settings and properties.</p>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Booking Website. All rights reserved.</p>
    </footer>
</body>
</html></ul>