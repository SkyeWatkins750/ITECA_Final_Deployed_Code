<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$items = getItemCardDetails();
$categories = getCategories();

function getItemCardDetails(){
    try{
        require "dbh.inc.php";

        $query = "SELECT  i.id, i.imagePath, i.itemName, i.price FROM item i ORDER BY i.dateListed DESC;";

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

function getCategories() {
    try{
        require "dbh.inc.php";

        $query = "SELECT * from category";

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

function getUserInfo() {
    try {
        require "dbh.inc.php";

        $email = $_SESSION["email"];

        $query = "SELECT id, Province, accessLevel FROM users WHERE email = :useremail;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":useremail", $email);

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
$userInfo = getUserInfo();
$_SESSION["user_id"] = $userInfo["id"];
$_SESSION["user_province"] = $userInfo["Province"];
$_SESSION['accessLevel'] = $userInfo["accessLevel"];

function getItemsForCards() {
    $htmlOutput = "";

    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 30;
    $searchTerm = isset($_POST['search']) ? trim($_POST['search']) : '';

    try {
        require "dbh.inc.php";

        $currentProvince = $_SESSION['user_province'] ?? null;

        $baseQuery = "
            SELECT i.id, i.imagePath, i.itemName, i.price
            FROM item i
            INNER JOIN users u ON i.sellerId = u.id
            WHERE u.province = ?
        ";

        $params = [$currentProvince];

        // Search term
        if (!empty($searchTerm)) {
            $baseQuery .= " AND i.itemName LIKE ?";
            $params[] = '%' . $searchTerm . '%';
        }

        // Category filter
        $applyCategoryFilter = false;
        if (isset($_POST['categories']) && is_array($_POST['categories'])) {
            $selectedCategories = array_filter($_POST['categories']);
            if (count($selectedCategories) > 0) {
                $_SESSION['selected_categories'] = $selectedCategories;
                $applyCategoryFilter = true;
            } else {
                unset($_SESSION['selected_categories']);
            }
        } elseif (!empty($_SESSION['selected_categories'])) {
            $selectedCategories = $_SESSION['selected_categories'];
            $applyCategoryFilter = true;
        }

        if ($applyCategoryFilter) {
            $placeholders = implode(',', array_fill(0, count($selectedCategories), '?'));
            $baseQuery .= " AND i.categoryId IN ($placeholders)";
            $params = array_merge($params, $selectedCategories);
        }

        $baseQuery .= " ORDER BY i.dateListed DESC, id DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $pdo->prepare($baseQuery);

        foreach ($params as $index => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($index + 1, $value, $paramType);
        }

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $item) {
            $htmlOutput .= "
                            <a href='../MainPage/DetailPage.php?id=" . urlencode($item['id']) . "' class='item-link' style='all: unset; display: block; width: 100%;'>
                                <div class='item-card'>
                                    <img src='../images/thumbnails/" . htmlspecialchars($item['imagePath']) . "' class='item-image'>
                                    <div class='item-details'>
                                        <h3 class='item-name'>" . htmlspecialchars($item['itemName']) . "</h3>
                                        <p class='item-price'>R" . number_format($item['price'], 2) . "</p>
                                    </div>
                                </div>
                            </a>
                        ";


        }

        $pdo = null;
        $stmt = null;

    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }

    return $htmlOutput;
}


if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {
    echo getItemsForCards();
    exit;
}