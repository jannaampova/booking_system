<!DOCTYPE html>
<html>
<head>
  <title>View Clients</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap">
  <link rel="stylesheet" href="../../css/admin.css">
  <link rel="stylesheet" href="../../css/table.css">
</head>
<body>
<?php
include "../../config.php";

// Handle the form submission and delete the user
if (isset($_POST['deleteUser'])) {
    $user_id = $_POST['user_id']; 
    $sql_delete = "DELETE FROM User WHERE id = '$user_id'";
    
    if (mysqli_query($dbConn, $sql_delete)) {
        echo "User deleted successfully!";
        // Refresh the page to reflect the deletion
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error deleting user.";
    }
}

// Query to get users
$sql = "SELECT * FROM User";
$result = mysqli_query($dbConn, $sql);

echo "<div class='table-container'>";
echo "<table border='1'>";
echo "<tr><th>Name</th><th>Phone</th><th>Role</th><th>Username</th><th>Email</th><th>Actions</th><th></th></tr>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row['fullName']."</td>";
        echo "<td>".$row['phone']."</td>";

        // Corrected SQL query for fetching roleName
        $sql2 = "SELECT roleName FROM Roles WHERE id=".$row['roleID'];
        $res2 = mysqli_query($dbConn, $sql2);

        // Fetch the role name from the result set
        if ($res2 && $roleRow = mysqli_fetch_assoc($res2)) {
            echo "<td>".$roleRow['roleName']."</td>";
        } else {
            echo "<td>Unknown Role</td>";
        }

        echo "<td>".$row['username']."</td>";
        echo "<td>".$row['email']."</td>";
        
        // Edit link
        echo "<td><a href='editUser.php?id=".$row['id']."'>Edit</a></td>";

        // Delete form with hidden input to pass user ID
        echo "<td>
            <form class='form' name='deleteForm' method='post'>
                <input type='hidden' name='user_id' value='".$row['id']."'>
                <button type='submit' name='deleteUser'>DELETE</button>
            </form>
        </td>";

        echo "</tr>";
   
    }}