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
    username VARCHAR(120) UNIQUE,
    email VARCHAR(30) UNIQUE,
    fullName VARCHAR(30),
    phone VARCHAR(30) UNIQUE,
    passwd VARCHAR(120),
    FOREIGN KEY (roleID) REFERENCES Roles(id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $UserTable)) {
    echo "User table created or already exists.<br>";
} else {
    echo "Error creating User table: " . mysqli_error($dbConn) . "<br>";
}

// Create City table
$cityTable = "CREATE TABLE IF NOT EXISTS City (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(120) UNIQUE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $cityTable)) {
    echo "City table created or already exists.<br>";
} else {
    echo "Error creating City table: " . mysqli_error($dbConn) . "<br>";
}

// Create PropertyType table
$propType = "CREATE TABLE IF NOT EXISTS PropertyType (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    propType VARCHAR(120)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $propType)) {
    echo "PropertyType table created or already exists.<br>";
} else {
    echo "Error creating PropertyType table: " . mysqli_error($dbConn) . "<br>";
}

// Create GuestNumber table
$guestNum = "CREATE TABLE IF NOT EXISTS GuestNumber (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    guestNum INT
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $guestNum)) {
    echo "GuestNumber table created or already exists.<br>";
} else {
    echo "Error creating GuestNumber table: " . mysqli_error($dbConn) . "<br>";
}

// Create Amenities table
$amenities = "CREATE TABLE IF NOT EXISTS Amenities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    amenity VARCHAR(120) UNIQUE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $amenities)) {
    echo "Amenities table created or already exists.<br>";
} else {
    echo "Error creating Amenities table: " . mysqli_error($dbConn) . "<br>";
}

// Create Property table
$propertyTable = "CREATE TABLE IF NOT EXISTS Property (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    propTypeID INT UNSIGNED,
    hostID INT UNSIGNED,
    cityID INT UNSIGNED,
    guestNumID INT UNSIGNED,
    pricePerNight INT,
    propAddress VARCHAR(120),
    propDesc VARCHAR(1000),
    propName VARCHAR(30),
    review INT,
    FOREIGN KEY (propTypeID) REFERENCES PropertyType(id) ON DELETE CASCADE,
    FOREIGN KEY (hostID) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (cityID) REFERENCES City(id) ON DELETE CASCADE,
    FOREIGN KEY (guestNumID) REFERENCES GuestNumber(id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $propertyTable)) {
    echo "Property table created or already exists.<br>";
} else {
    echo "Error creating Property table: " . mysqli_error($dbConn) . "<br>";
}

// Create Property Amenities table
$propertyAmenities = "CREATE TABLE IF NOT EXISTS PropAmenities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    propID INT UNSIGNED,
    amenityID INT UNSIGNED,
    FOREIGN KEY (propID) REFERENCES Property(id) ON DELETE CASCADE,
    FOREIGN KEY (amenityID) REFERENCES Amenities(id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $propertyAmenities)) {
    echo "Property Amenities table created or already exists.<br>";
} else {
    echo "Error creating Property Amenities table: " . mysqli_error($dbConn) . "<br>";
}

// Create Availability table
$availability = "CREATE TABLE IF NOT EXISTS Availabilities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fromDate DATE,
    toDate DATE,
    propStatus VARCHAR(10) CHECK (propStatus IN ('free','reserved','booked')),
    propID INT UNSIGNED,
    FOREIGN KEY (propID) REFERENCES Property(id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $availability)) {
    echo "Availability table created or already exists.<br>";
} else {
    echo "Error creating Availability table: " . mysqli_error($dbConn) . "<br>";
}

// Create Booking table
$booking = "CREATE TABLE IF NOT EXISTS Booking (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    clientID INT UNSIGNED,
    propID INT UNSIGNED,
    bookingStatus VARCHAR(10) CHECK (bookingStatus IN ('approved','declined','pending')),
    fromDate DATE,
    toDate DATE,
    totalPrice INT,
    FOREIGN KEY (propID) REFERENCES Property(id) ON DELETE CASCADE,
    FOREIGN KEY (clientID) REFERENCES User(id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $booking)) {
    echo "Booking table created or already exists.<br>";
} else {
    echo "Error creating Booking table: " . mysqli_error($dbConn) . "<br>";
}

// Create Payment table
$payment = "CREATE TABLE IF NOT EXISTS Payment (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bookingID INT UNSIGNED,
    paymentStatus VARCHAR(10) CHECK (paymentStatus IN ('paid','cancelled','pending')),
    paymentMethod VARCHAR(10) CHECK (paymentMethod IN ('cash','card','bank')),
    amount INT,
    FOREIGN KEY (bookingID) REFERENCES Booking(id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $payment)) {
    echo "Payment table created or already exists.<br>";
} else {
    echo "Error creating Payment table: " . mysqli_error($dbConn) . "<br>";
}

// Create Messages table
$messages = "CREATE TABLE IF NOT EXISTS Messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    senderID INT UNSIGNED,
    receiverID INT UNSIGNED,
    timeAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    msg TEXT,
    FOREIGN KEY (senderID) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (receiverID) REFERENCES User(id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $messages)) {
    echo "Messages table created or already exists.<br>";
} else {
    echo "Error creating Messages table: " . mysqli_error($dbConn) . "<br>";
}

// SQL to create Images table
$img = "CREATE TABLE IF NOT EXISTS Images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    imgPath VARCHAR(255) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $img)) {
    echo "Img table created or already exists.<br>";
} else {
    echo "Error creating Img table: " . mysqli_error($dbConn) . "<br>";
}

// SQL to create ImgToProp table
$imgToProperty = "CREATE TABLE IF NOT EXISTS ImgToProp (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    imgID INT UNSIGNED,
    propertyID INT UNSIGNED,
    FOREIGN KEY (imgID) REFERENCES Images(id) ON DELETE CASCADE,
    FOREIGN KEY (propertyID) REFERENCES Property(id) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

if (mysqli_query($dbConn, $imgToProperty)) {
    echo "ImgToProp table created or already exists.<br>";
} else {
    echo "Error creating ImgToProp table: " . mysqli_error($dbConn) . "<br>";
}

mysqli_close($dbConn); // Close the database connection
