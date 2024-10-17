<?php


function setErrorFor(&$error, &$class, $message) {
    $error = $message; // Set the error message
    $class = 'error'; // Set class to error
}

// Function to set success state
function setSuccessFor(&$class) {
    $class = 'success'; // Set class to success
}


?>