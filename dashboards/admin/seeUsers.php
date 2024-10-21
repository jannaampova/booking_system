<!DOCTYPE html>
<html>
<head>
  <title>View Clients</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap">
  <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
<?php
include "../../config.php";
$sql = "SELECT * FROM User";
$result = mysqli_query($dbConn,$sql);

echo "<div class='table-container'>";
echo "<table border='1'>";
echo "<tr><th>Name</th><th>Phone</th><th>Role</th><th>Username</th><th>Email</th></tr>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row['fullName']."</td>";
        echo "<td>".$row['phone']."</td>";

        // Corrected SQL query for fetching roleName
        $sql2 = "SELECT roleName FROM Roles WHERE id=".$row['roleID']; // Fixed syntax for concatenation
        $res2 = mysqli_query($dbConn, $sql2);

        // Fetch the role name from the result set
        if ($res2 && $roleRow = mysqli_fetch_assoc($res2)) {
            echo "<td>".$roleRow['roleName']."</td>"; // Display the role name
        } else {
            echo "<td>Unknown Role</td>"; // Fallback if no role found
        }

        echo "<td>".$row['username']."</td>";
        echo "<td>".$row['email']."</td>";
        echo "<td><a href='editUser.php?id=".$row['id']."'>Edit</a></td>"; // Pass user ID in URL
        echo "<td><a href='deleteUser.php?id=" . $row['id'] . "'>Delete</a></td>"; // Pass user ID for delete as well
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='2'>0 results</td></tr>";
}
echo "</table>";
echo "</div>";

?>
<div class="links-container">
  <a href='adminBoard.php' class="home-link">home</a>
</div>
</body>
</html>
