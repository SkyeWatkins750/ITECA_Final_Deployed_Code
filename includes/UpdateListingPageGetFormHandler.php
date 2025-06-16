<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getCategoriesCreateForUpdate() {
    try{
        require "dbh.inc.php";

        $query = "SELECT categoryName FROM category;";

        $stmt = $pdo->prepare($query);

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;
        $stmt = null;
        return $results;
        die();
    }catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function getConditionsForUpdate() {
    try{
        require "dbh.inc.php";

        $query = "SELECT conditionName FROM _condition;";

        $stmt = $pdo->prepare($query);

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;
        $stmt = null;
        return $results;
        die();
    }catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itemId'])) {
    $itemId = $_POST['itemId'];
    try {
        $item = getItemDetailsForUpdate($itemId);
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}
else {
    header("Location: ../MainPage/MainPage.php");
    die("No item ID provided");
}

function getItemDetailsForUpdate($itemId) {
    try{
        require "dbh.inc.php";

        $query = "SELECT  i.id, i.dateListed, i.imagePath, i.description, i.itemName, i.price, ca.categoryName as Category_Name, co.conditionName as Condition_Name FROM item i INNER JOIN category ca ON i.categoryId = ca.id INNER JOIN _condition co ON i.conditionId = co.id WHERE i.id = :itemId;";

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