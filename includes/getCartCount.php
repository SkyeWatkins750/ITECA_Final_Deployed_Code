<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['count' => 0]);
        die();
    }

    $userId = $_SESSION['user_id'];
    require "dbh.inc.php";

    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM cart_items WHERE userId = :userID");
    $stmt->bindParam(":userID", $userId);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['count' => $result['total']]);

    $pdo = null;
    $stmt = null;
    die();

} catch (PDOException $e) {
    echo json_encode(['count' => 0]);
    die();
}