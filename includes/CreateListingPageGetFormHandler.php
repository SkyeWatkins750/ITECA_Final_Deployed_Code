<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getCategoriesCreate() {
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

function getConditions() {
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