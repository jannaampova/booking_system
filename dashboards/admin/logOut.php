<?php
session_unset();
session_destroy();
header("Location: ../../userEntry/logIn.php");
exit();
?>