<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host Properties Dashboard</title>
    <link rel="stylesheet" href="path/to/your/styles.css">
</head>
<body>
    <header>
        <h1>Host Properties Dashboard</h1>
    </header>
    <main>
    <form action="" method="post" enctype="multipart/form-data">
    <label>Select Image to Upload:</label>
    <input type="file" name="image" accept="image/*">
    <input type="submit" name="upload" value="Upload">
</form>
<?php
// Include your config file to connect to the database
include '../../config.php';  // Adjust path accordingly

if (isset($_POST['upload'])) {
    // Set the upload directory
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the uploaded file is an image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        // Limit file size (example: 500KB)
        if ($_FILES["image"]["size"] > 900000000) {
            echo "Sorry, your file is too large.";
        } else {
            // Allow only certain file types (JPG, JPEG, PNG, GIF)
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowed_types)) {
                echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
            } else {
                // Move the uploaded file to the 'uploads' directory
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // Prepare the SQL query to insert the file path into the database
                    $query = "INSERT INTO images (imgPath) VALUES ('$target_file')";

                    // Execute the query using mysqli
                    if (mysqli_query($dbConn, $query)) {
                        echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
                    } else {
                        echo "Sorry, there was an error saving the file path to the database: " . mysqli_error($dbConn);
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
    } else {
        echo "File is not an image.";
    }
}
?>


<?php
include "../../config.php";

$query = "SELECT imgPath FROM images";
$result = mysqli_query($dbConn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $imagePath = $row['imgPath'];
        if (file_exists($imagePath)) {
            echo '<img src="' . $imagePath . '" alt="Property Image" width="300px">';
        } else {
            echo 'Image not found';
        }
    }
} else {
    echo "Error: " . mysqli_error($dbConn);
}
?>


    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Your Company Name. All rights reserved.</p>
    </footer>
</body>
</html>