<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try{
        $itemID = $_POST["itemId"];
        require_once "dbh.inc.php";

        $query = "DELETE FROM item WHERE id = :itemId;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":itemId", $itemID);

        $stmt->execute();

        $pdo = null;
        $stmt = null;

        header("Location: ../MainPage/MyListingsPage.php");
        die();

    }catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}else{
    header("Location: ../MainPage/MyListingsPage.php");
}