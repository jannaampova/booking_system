<?php 
include 'config.php';

// Create the database if it does not exist
$sqlCreateDatabase = 'CREATE DATABASE IF NOT EXISTS booking_system';
if (mysqli_query($dbConn, $sqlCreateDatabase)) {
    echo "Database created or already exists.<br>";
} else {
    echo "Error creating database: " . mysqli_error($dbConn) . "<br>";
}

// Select the database
$dbName = 'booking_system';
if (!mysqli_select_db($dbConn, $dbName)) {
    die('Could not select the database: ' . mysqli_error($dbConn));
}

// Create Roles table
$roleTable = "CREATE TABLE IF NOT EXISTS Roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    roleName VARCHAR(30)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $roleTable)) {
    echo "Roles table created or already exists.<br>";
} else {
    echo "Error creating Roles table: " . mysqli_error($dbConn) . "<br>";
}

// Create User table
$UserTable = "CREATE TABLE IF NOT EXISTS User (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    roleID INT UNSIGNED,
    username VARCHAR(30) UNIQUE,
    email VARCHAR(30) UNIQUE,
    fullName VARCHAR(30),
    phone VARCHAR(30) UNIQUE,
    passwd VARCHAR(30),
    FOREIGN KEY (roleID) REFERENCES Roles(id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $UserTable)) {
    echo "User table created or already exists.<br>";
} else {
    echo "Error creating User table: " . mysqli_error($dbConn) . "<br>";
}
?>
