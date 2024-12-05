<?php
session_start(); // Start the session
// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: ../../userEntry/logIn.php"); // Redirect to login if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>View Users</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap">
    <script src="https://kit.fontawesome.com/876722883c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../css/table.css">
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <style>
        .main {
            width: 120%;
            flex-direction: row;
            display: flex;
            align-items: flex-start;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="left-container">
            <div class="options">
                <?php
                $fullName = $_SESSION['name'];
                $firstName = explode(' ', $fullName)[0]; // Get the first name
                ?>
                <a href="hostSettings.php">
                    <i class="fas fa-user-edit"></i>
                    <?php echo htmlspecialchars($firstName); ?>
                </a>

                <a href="adminBoard.php">Home page</a>
                <a href='seeUsers.php'>View Users</a>
                <a href='logOut.php'>Log Out <i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>
        <div class="table-container">
            <?php
            include "../../config.php";

            $roleID = isset($_GET['role']) ? intval($_GET['role']) : 0;

            if ($roleID <= 0) {
                die("Invalid role specified.");
            }

            $sqlRole = "SELECT roleName FROM Roles WHERE id = $roleID";
            $roleRes = mysqli_query($dbConn, $sqlRole);
            $roleRow = mysqli_fetch_assoc($roleRes);

            if (!$roleRow) {
                die("Role not found.");
            }
            $roleName = $roleRow['roleName'];
            $sql = "SELECT id, fullName, phone, username, email FROM User WHERE roleID = $roleID";
            $res = mysqli_query($dbConn, $sql);

            echo "<h1>Users with Role: " . ucfirst($roleName) . "</h1>";
            echo "<table border='1'>";
            echo "<tr><th>Name</th><th>Phone</th><th>Username</th><th>Email</th><th>Edit</th><th>Delete</th></tr>";

            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>";
                echo "<td>{$row['fullName']}</td>";
                echo "<td>{$row['phone']}</td>";
                echo "<td>{$row['username']}</td>";
                echo "<td>{$row['email']}</td>";

                echo "<td>
        <a href='editUser.php?id={$row['id']}' class='edit-link'>
            <i class='fas fa-pencil-alt'></i>
        </a>
    </td>";
                echo "<td>
        <form method='post' action='deleteUser.php' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>
            <input type='hidden' name='user_id' value='{$row['id']}'>
            <button type='submit' class='delete-link'>
                <i class='fas fa-trash-alt'></i>
            </button>
        </form>
    </td>";

                echo "</tr>";
            }

            echo "</table>";
            ?>

        </div>
    </div>
</body>

</html>