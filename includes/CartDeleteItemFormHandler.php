<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if(!isset($_POST['id']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

try{
    $itemId = $_POST['id'];
    $userId = $_SESSION['user_id'];

    require "dbh.inc.php";

    $query = "DELETE FROM cart_items 	WHERE userId = :userID AND itemId = :itemID;";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":userID", $userId);
    $stmt->bindParam(":itemID", $itemId);

    $stmt->execute();

    $newTotal = getTotalPriceAfterDelete();
    echo json_encode(['success' => true, 'newTotal' => $newTotal]);
}catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

function getTotalPriceAfterDelete() {
    try{
        $userId = $_SESSION['user_id'];
        require "dbh.inc.php";

        $query = "SELECT i.price FROM item i INNER JOIN cart_items ci ON ci.itemId = i.id  AND ci.userId = :userID;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":userID", $userId);

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;
        $stmt = null;

        $final = 0.00;

        if (!$results) {
            $final = 0.00;
        } else {
            foreach ($results as $item) {
                $final += $item['price'];
            }
        }

        return number_format($final, 2);
    }catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}