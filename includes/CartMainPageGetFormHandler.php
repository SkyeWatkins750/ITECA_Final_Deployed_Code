<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getCartItemDetails() {
    try{
        $userId = $_SESSION['user_id'];
        // $userId = 2;
        require "dbh.inc.php";

        $query = "SELECT i.id, i.itemName, i.imagePath, i.price, ca.categoryName, co.conditionName FROM item i INNER JOIN cart_items ci ON ci.itemId = i.id  AND ci.userId = :userID INNER JOIN category ca ON i.categoryId = ca.id INNER JOIN _condition co ON i.conditionId = co.id ORDER BY ci.addedAt DESC;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":userID", $userId);

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;
        $stmt = null;

        $output = "";

        if ($results) {
            foreach ($results as $item) {
                $output .= "
                            <form method='post' action='../MainPage/DetailPage.php' class='cart-form'>
                                <input type='hidden' name='id' value='" . htmlspecialchars($item['id']) . "'>
                                <div class='cart-item' onclick='this.closest(\"form\").submit()'>
                                    <img src='../images/thumbnails/{$item['imagePath']}' class='cart-img' alt='Item'>
                                    <div class='cart-details'>
                                        <h3>" . htmlspecialchars($item['itemName']) . "</h3>
                                        <p>Category: " . htmlspecialchars($item['categoryName']) . "</p>
                                        <p>Condition: " . htmlspecialchars($item['conditionName']) . "</p>
                                        <p class='cart-price'>R" . htmlspecialchars(number_format($item['price'], 2)) . "</p>
                                    </div>
                                    <i class='bi bi-trash3 delete-icon' data-id='" . htmlspecialchars($item['id']) . "' onclick='event.stopPropagation(); deleteCartItem(this);'></i>
                                </div>
                            </form>";
            }
        } else {
            $output = "<div class='cart-item'>
                            <div class='cart-details'>
                                <h3>Cart is empty.</h3>
                            </div>
                        </div>";
        }

        return $output;
    }catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function getTotalPrice() {
    try{
        $userId = $_SESSION['user_id'];
        // $userId = 2;
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