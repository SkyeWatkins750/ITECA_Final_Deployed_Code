<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function createThumbnail($sourcePath, $destPath, $thumbWidth = 300) {
    $info = getimagesize($sourcePath);
    if ($info ==false) return false;

    [$srcWidth, $srcHeight] = $info;
    $mime = $info['mime'];

    if($mime == 'image/jpeg') {
        $srcImg = imagecreatefromjpeg($sourcePath);
    }
    elseif($mime == 'image/png'){
        $srcImg = imagecreatefrompng($sourcePath);
    }
    else
    {
        return false;
    }

    $thumbHeight = intval($srcHeight * $thumbWidth / $srcWidth);
    $thumbImg = imagecreatetruecolor($thumbWidth, $thumbHeight);
    imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $srcWidth, $srcHeight);

    if($mime == 'image/jpeg') {
        imagejpeg($thumbImg, $destPath, 85);
    }
    elseif($mime == 'image/png'){
        imagepng($thumbImg, $destPath);
    }

    imagedestroy($srcImg);
    imagedestroy($thumbImg);

    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $itemName = $_POST["itemName"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    $condition = $_POST["condition"];
    $description = $_POST["description"];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imageName = $image['name'];
        $imageTmpPath = $image['tmp_name'];
        $fileExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "Invlaid file type. Only JPG, JPEG, and PNG files are allowed";
            exit;
        }

        $userId = $_SESSION['user_id'];
        $newImageName = $userId . '_' . date('Ymd_His') . '.' . $fileExtension;

        $uploadDir = '../images/';
        $thumbDir = $uploadDir . 'thumbnails/';
        $destinationPath = $uploadDir . $newImageName;
        $thumbPath = $thumbDir . $newImageName;

        if (move_uploaded_file($imageTmpPath, $destinationPath)) {
            if (!createThumbnail($destinationPath, $thumbPath)) {
                echo "Thumbnail creation failed.";
                exit;
            }

            try{
                require "dbh.inc.php";

                $query = "INSERT INTO item (categoryId, conditionId, description, imagePath, price, sellerId, itemName) VALUES ( (SELECT id FROM category WHERE categoryName = :category COLLATE utf8mb4_unicode_ci), (SELECT id FROM _condition WHERE conditionName = :condition COLLATE utf8mb4_unicode_ci), :Itemdescription, :imageName, :price, :sellerId, :itemName);";

                $stmt = $pdo->prepare($query);

                $stmt->bindParam(":category", $category);
                $stmt->bindParam(":condition", $condition);
                $stmt->bindParam(":Itemdescription", $description);
                $stmt->bindParam(":imageName", $newImageName);
                $stmt->bindParam(":price", $price);
                $stmt->bindParam(":sellerId", $_SESSION['user_id']);
                $stmt->bindParam(":itemName", $itemName);

                $stmt->execute();

                $pdo = null;
                $stmt = null;

                header("Location: ../MainPage/MainPage.php");

            }catch (PDOException $e) {
                die("Query failed: " . $e->getMessage());
            }
        }else {
            echo "Failed to upload image.";
        }
    }else{
        echo "No image was not uploaded or there was an error with the image.";
    }
}else {
    header ("Location: Location: ../MainPage/CreateListingPage.php");
}