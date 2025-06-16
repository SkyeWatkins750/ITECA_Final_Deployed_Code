<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $itemId = $_POST['id'];
    header("Location: ../MainPage/DetailPage.php?id=" . urlencode($itemId));
    exit();
} elseif ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['id'])) {
    $itemId = $_GET['id'];
    try {
        $item = getItemDetails($itemId);
        if (!$item) {
            die("Item not found.");
        }
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../MainPage/MainPage.php");
    die("No item ID provided");
}

function getItemDetails($itemId) {
    try{
        require "dbh.inc.php";

        $query = "SELECT i.id, i.dateListed, i.imagePath, i.description, i.itemName, i.price, ca.categoryName AS Category_Name, co.conditionName AS Condition_Name, u.PostalCode AS Postal_Code, u.Province, u.StreetAddress, u.email, u.City FROM item i INNER JOIN category ca ON i.categoryId = ca.id INNER JOIN _condition co ON i.conditionId = co.id INNER JOIN users u ON u.id = i.sellerId WHERE i.id = :itemId;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":itemId", $itemId);

        $stmt->execute();

        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        $pdo = null;
        $stmt = null;

        return $results;

        if(!$results) {
            echo "Item is empty";
            exit;
        }

        die();

    }catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function checkCart($itemId) {
    try{
        $userId = $_SESSION['user_id'];
        require "dbh.inc.php";

        $query = "SELECT id FROM cart_items WHERE userId = :userID AND itemId = :itemID;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":userID", $userId);
        $stmt->bindParam(":itemID", $itemId);

        $stmt->execute();

        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        $pdo = null;
        $stmt = null;

        return $results;
        die();

    }catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

$results = checkCart($itemId);
if (!$results) {
    $inCart = false;
}else {
    $inCart = true;
}