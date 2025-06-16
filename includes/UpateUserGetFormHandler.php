<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getUserDetails() {
    if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    }else{
        die("No user ID provided");
    }

    try{
        require_once "dbh.inc.php";

        $query = "SELECT fullname, email, Province, City, StreetAddress, PostalCode, userPassword FROM users WHERE id = :userID;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":userID", $userId);

        $stmt->execute();

        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        $pdo = null;
        $stmt = null;

        return $results;

        if(!$results) {
            echo "No user found.";
            exit;
        }

        die();

    }catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}