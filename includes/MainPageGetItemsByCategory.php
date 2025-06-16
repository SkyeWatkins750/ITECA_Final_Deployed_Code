<?
function getItemsForCards() {
    $htmlOutput = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {
        $selectedCategories = $_POST['categories'] ?? [];
        if (is_array($selectedCategories)) {
            try {
                require_once "dbh.inc.php";
                $placeholders = implode(',', array_fill(0, count($selectedCategories), '?'));
                $query = "SELECT  i.id, i.imagePath, i.itemName, i.price FROM item i WHERE categoryId IN ($placeholders) ORDER BY i.dateListed DESC;";

                $stmt = $pdo->prepare($query);

                $stmt->execute($selectedCategories);

                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $pdo = null;
                $stmt = null;
            }catch (PDOException $e) {
                die("Query failed: " . $e->getMessage());
            }
        }else {
            try{
                require "dbh.inc.php";

                $query = "SELECT  i.id, i.imagePath, i.itemName, i.price FROM item i ORDER BY i.dateListed DESC;";

                $stmt = $pdo->prepare($query);

                $stmt->execute();

                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $pdo = null;
                $stmt = null;
            }catch (PDOException $e) {
                die("Query failed: " . $e->getMessage());
            }
        }

        foreach ($results as $item) {
            $htmlOutput .= "
                            <form method='POST' action='../MainPage/DetailPage.php' class='item-form'>
                                <input type='hidden' name='id' value='" . htmlspecialchars($item['id']) . "'>
                                <button type='submit' class='item-card item-link' style='border:none; background:none; padding:0;'>
                                    <img src='../images/" . htmlspecialchars($item['imagePath']) . "' class='item-image'>
                                    <div class='item-details'>
                                        <h3 class='item-name'>" . htmlspecialchars($item['itemName']) . "</h3>
                                        <p class='item-price'>R" . number_format($item['price'], 2) . "</p>
                                    </div>
                                </button>
                            </form>
                            ";


        }
                    
    }
    echo $htmlOutput;
    exit;
}