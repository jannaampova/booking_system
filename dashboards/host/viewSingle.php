<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Single</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../../css/viewSingle.css" />
    <link rel="stylesheet" href="../../css/table.css" />
</head>

<body>
    <div class="section">
        <nav>
            <a href="hostBoard.php">Home page</a>
            <a href="../admin/logOut.php">Log Out</a>
        </nav>
    </div>

    <div class="cont">
        <?php
        include "../../config.php";
        $propertyId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $sql = "SELECT * FROM Property WHERE id=$propertyId";
        $res = mysqli_query($dbConn, $sql);
        $res1 = mysqli_query($dbConn, $sql);



        while ($row = mysqli_fetch_assoc($res)) {
            $sqlImgID = "SELECT imgID FROM imgToProp WHERE propertyID=$propertyId";
            $imgRes = mysqli_query($dbConn, $sqlImgID);

            $images = [];
            while ($imgRow = mysqli_fetch_assoc($imgRes)) {
                $sqlImgPath = "SELECT imgPath FROM Images WHERE id={$imgRow['imgID']}";
                $pathRes = mysqli_query($dbConn, $sqlImgPath);
                if ($pathRow = mysqli_fetch_assoc($pathRes)) {
                    $images[] = $pathRow['imgPath'];
                }
            }
        }

        foreach ($images as $imagePath) {
            echo "<div class='kol'>
                    <img src='" . htmlspecialchars($imagePath) . "' alt='Property Image'>
                  </div>";
        }
        echo "</div>";

        $row1 = mysqli_fetch_assoc($res1);
        $name = $row1['propName'];
        $desc = $row1['propDesc'];

        echo "<div class='table-container'>";
        echo "<form class=form>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<div class=form-control><td><input type='text'  value='" . htmlspecialchars($name) . "' readonly>";
        echo "<td><input type='text'  value='" . htmlspecialchars($desc) . "' readonly></td></div>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><input type='text' value='" . htmlspecialchars($name) . "' readonly>";
        echo "<td><input type='text' value='" . htmlspecialchars($name) . "' readonly>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><input type='text' value='" . htmlspecialchars($name) . "' readonly>";
        echo "<td><input type='text' value='" . htmlspecialchars($name) . "' readonly>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><input type='text' value='" . htmlspecialchars($name) . "' readonly>";
        echo "<td><input type='text' value='" . htmlspecialchars($name) . "' readonly>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><input type='text' value='" . htmlspecialchars($name) . "' readonly>";
        echo "<td><input type='text' value='" . htmlspecialchars($name) . "' readonly>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><input type='text' value='" . htmlspecialchars($name) . "' readonly>";
        echo "<td><input type='text' value='" . htmlspecialchars($name) . "' readonly>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><input type='text' value='" . htmlspecialchars($name) . "' readonly>";
        echo "<td><input type='text' value='" . htmlspecialchars($name) . "' readonly>";
        echo "</tr>";
        echo "</table>";
        echo "</form>";
        echo "</div>";
        ?>



</body>

</html>