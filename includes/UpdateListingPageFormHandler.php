<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function createThumbnail($sourcePath, $destPath, $thumbWidth = 300) {
    $info = getimagesize($sourcePath);
    if ($info == false) return false;

    [$srcWidth, $srcHeight] = $info;
    $mime = $info['mime'];

    if ($mime == 'image/jpeg') {
        $srcImg = imagecreatefromjpeg($sourcePath);
    } elseif ($mime == 'image/png') {
        $srcImg = imagecreatefrompng($sourcePath);
    } else {
        return false;
    }

    $thumbHeight = intval($srcHeight * $thumbWidth / $srcWidth);
    $thumbImg = imagecreatetruecolor($thumbWidth, $thumbHeight);
    imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $srcWidth, $srcHeight);

    if ($mime == 'image/jpeg') {
        imagejpeg($thumbImg, $destPath, 85);
    } elseif ($mime == 'image/png') {
        imagepng($thumbImg, $destPath);
    }

    imagedestroy($srcImg);
    imagedestroy($thumbImg);

    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemName = $_POST["itemName"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    $condition = $_POST["condition"];
    $description = $_POST["description"];
    $userId = $_SESSION['user_id'];
    $itemId = $_POST["itemId"]; // Make sure this is posted for update

    $imageUploaded = isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK;

    $newImageName = null;

    if ($imageUploaded) {
        $image = $_FILES['image'];
        $imageName = $image['name'];
        $imageTmpPath = $image['tmp_name'];
        $fileExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "Invalid file type. Only JPG, JPEG, and PNG files are allowed";
            exit;
        }

        $newImageName = $userId . '_' . date('Ymd_His') . '.' . $fileExtension;
        $uploadDir = '../images/';
        $thumbDir = $uploadDir . 'thumbnails/';
        $destinationPath = $uploadDir . $newImageName;
        $thumbPath = $thumbDir . $newImageName;

        if (!move_uploaded_file($imageTmpPath, $destinationPath)) {
            echo "Failed to upload image.";
            exit;
        }

        if (!createThumbnail($destinationPath, $thumbPath)) {
            echo "Thumbnail creation failed.";
            exit;
        }
    }

    try {
        require "dbh.inc.php";

        if ($imageUploaded) {
            $query = "UPDATE item
                        SET 
                            categoryId = (SELECT id FROM category WHERE categoryName = :_category COLLATE utf8mb4_unicode_ci),
                            conditionId = (SELECT id FROM _condition WHERE conditionName = :_condition COLLATE utf8mb4_unicode_ci),
                            description = :_description,
                            imagePath = :_imagePath,
                            price = :_price,
                            itemName = :_itemName
                        WHERE id = :_itemId;";
        } else {
            $query = "UPDATE item
                        SET 
                            categoryId = (SELECT id FROM category WHERE categoryName = :_category COLLATE utf8mb4_unicode_ci),
                            conditionId = (SELECT id FROM _condition WHERE conditionName = :_condition COLLATE utf8mb4_unicode_ci),
                            description = :_description,
                            price = :_price,
                            itemName = :_itemName
                        WHERE id = :_itemId;";
        }

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":_category", $category);
        $stmt->bindParam(":_condition", $condition);
        $stmt->bindParam(":_description", $description);
        $stmt->bindParam(":_price", $price);
        $stmt->bindParam(":_itemName", $itemName);
        $stmt->bindParam(":_itemId", $itemId);

        if ($imageUploaded) {
            $stmt->bindParam(":_imagePath", $newImageName);
        }

        $stmt->execute();

        $pdo = null;
        $stmt = null;

        header("Location: ../MainPage/MyListingsPage.php");
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../MainPage/CreateListingPage.php");
}