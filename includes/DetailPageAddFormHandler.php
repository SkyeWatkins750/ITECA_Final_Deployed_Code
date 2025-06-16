<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false]);
            die();
        }

        $itemId = $_POST['id'];
        $userId = $_SESSION['user_id'];
        require "dbh.inc.php";

        $stmt = $pdo->prepare("INSERT INTO cart_items (itemId, userId) VALUES (:itemID, :userID)");
        $stmt->bindParam(":itemID", $itemId);
        $stmt->bindParam(":userID", $userId);
        $success = $stmt->execute();

        echo json_encode(['success' => $success]);

        $pdo = null;
        $stmt = null;
        die();

    } catch (PDOException $e) {
        echo json_encode(['success' => false]);
        die();
    }
}