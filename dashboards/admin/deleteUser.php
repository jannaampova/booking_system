<?php
include "../../config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = intval($_POST['user_id']);
    $sql = "DELETE FROM User WHERE id = $user_id";

    if (mysqli_query($dbConn, $sql)) {
        header("Location: seeUsers.php");
        exit();
    } else {
        header("Location: seeUsers.php");
        exit();
    }
}

mysqli_close($dbConn);
