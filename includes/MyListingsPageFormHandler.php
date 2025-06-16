<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function getItemCardDetailsForUser($offset, $limit, $search = '') {
    try {
        if (!isset($_SESSION["user_id"])) {
            http_response_code(401);
            exit("User not logged in.");
        }

        require "dbh.inc.php";

        $query = "SELECT i.id, i.imagePath, i.itemName, i.price 
                  FROM item i 
                  WHERE i.sellerId = :sellerID";

        if (!empty($search)) {
            $query .= " AND i.itemName LIKE :searchTerm";
        }

        $query .= " ORDER BY i.dateListed DESC LIMIT :_limit OFFSET :_offset";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":sellerID", $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt->bindParam(":_limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":_offset", $offset, PDO::PARAM_INT);

        if (!empty($search)) {
            $searchWildcard = '%' . $search . '%';
            $stmt->bindParam(":searchTerm", $searchWildcard, PDO::PARAM_STR);
        }

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $output = "";
        foreach ($results as $item) {
            $output .= "<form action='../MainPage/UpdateListingPage.php' method='POST' class='item-link' style='all: unset; cursor: pointer;'>
                <input type='hidden' name='itemId' value='{$item['id']}'>
                <button type='submit' style='all: unset; width: 100%; text-align: left;'>
                    <div class='item-card'>
                        <img src='../images/thumbnails/{$item['imagePath']}' class='item-image'>
                        <div class='item-details'>
                            <h3 class='item-name'>" . htmlspecialchars($item['itemName']) . "</h3>
                            <p class='item-price'>R" . number_format($item['price'], 2) . "</p>
                        </div>
                    </div>
                </button>
            </form>";

        }

        echo $output;

    } catch (PDOException $e) {
        http_response_code(500);
        exit("Query failed: " . $e->getMessage());
    }
}



if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest") {
    $offset = isset($_POST["offset"]) ? (int)$_POST["offset"] : 0;
    $limit = isset($_POST["limit"]) ? (int)$_POST["limit"] : 100;
    $search = isset($_POST["search"]) ? trim($_POST["search"]) : '';

    getItemCardDetailsForUser($offset, $limit, $search);
    exit;
}