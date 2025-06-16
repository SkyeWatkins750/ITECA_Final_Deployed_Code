<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'EncryptionHandler.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $userId = $_SESSION['user_id'];
        require_once "dbh.inc.php";

        $cardHolderName = $_POST['cardName'];
        $cardNumber = $_POST['cardNumber'];
        $expiryDate = $_POST['expiryDate'];

        $country = $_POST['country'];
        $province = $_POST['province'];
        $city = $_POST['city'];
        $address = $_POST['address'];
        $postalCode = $_POST['postalCode'];

        $pdo->beginTransaction();

        $stmt1 = $pdo->prepare("INSERT INTO purchased_cart_group (userId) VALUES (:userID);");
        $stmt1->execute([':userID' => $userId]);

        $lastCartId = $pdo->lastInsertId();

        $stmt2 =$pdo->prepare("INSERT INTO purchaseditems (
                                id,
                                itemName,
                                price,
                                description,
                                imagePath,
                                dateListed,
                                categoryId,
                                conditionId,
                                sellerId
                            )
                            SELECT
                                i.id,
                                i.itemName,
                                i.price,
                                i.description,
                                i.imagePath,
                                i.dateListed,
                                i.categoryId,
                                i.conditionId,
                                i.sellerId
                            FROM item i
                            INNER JOIN cart_items ci ON  
                                ci.itemId = i.id
                                AND ci.userId = :userID;");
        
        $stmt2->execute([':userID' => $userId]);

        $stmt3 =$pdo->prepare("INSERT INTO purchasedcarts (
                                    id,
                                    itemId,
                                    userId,
                                    cartGroupId
                                )
                                SELECT 
                                    ci.id,
                                    ci.itemId,
                                    ci.userId,
                                    :cartGroupId
                                FROM cart_items ci
                                WHERE
                                ci.userId = :userID;");
        
        $stmt3->execute([':cartGroupId' => $lastCartId ,':userID' => $userId]);

        $stmt4 =$pdo->prepare("INSERT INTO _transaction (
                                purchasedCartId
                            )
                            VALUES(
                               :cartGroupId
                            );");
        
        $stmt4->execute([':cartGroupId' => $lastCartId]);

        $transactionId = $pdo->lastInsertId();

        $stmt5 = $pdo->prepare("INSERT INTO shippingdetails (
                                    transactionId, country, province, city, streetAddress, postalCode
                                ) VALUES (
                                    :transactionId, :country, :province, :city, :address, :postalCode
                                );");
        $stmt5->execute([
            ':transactionId' => $transactionId,
            ':country' => $country,
            ':province' => $province,
            ':city' => $city,
            ':address' => $address,
            ':postalCode' => $postalCode
        ]);

        $encryptedCardHolderName = encrypt($cardHolderName, $key);
        $encryptedCardNumber = encrypt($cardNumber, $key);
        $encryptedExpiryDate = encrypt($expiryDate, $key);

        $stmt6 = $pdo->prepare("INSERT INTO paymentdetails (
                                    transactionId, cardHolderName, cardNumber, expiryDate
                                ) VALUES (
                                    :transactionId, :cardHolderName, :cardNumber, :expiryDate
                                );");
        $stmt6->execute([
            ':transactionId' => $transactionId,
            ':cardHolderName' => $encryptedCardHolderName,
            ':cardNumber' => $encryptedCardNumber,
            ':expiryDate' => $encryptedExpiryDate
        ]);

        $stmt7 =$pdo->prepare("DELETE i
                                FROM item i
                                INNER JOIN cart_items ci ON  
                                    ci.itemId = i.id
                                    AND ci.userId = :userID;");
        
        $stmt7->execute([':userID' => $userId]);

        $stmt8 =$pdo->prepare("DELETE
                                FROM cart_items
                                WHERE userId = :userID;");
        
        $stmt8->execute([':userID' => $userId]);

        $pdo->commit();
        $_SESSION['order_id'] = $transactionId;
        header("Location: ../Cart/OrderCompletedPage.php");
        die("Transaction completed successfully.");
    }catch (PDOException $e) {
        $pdo->rollBack();
        die("Query failed: " . $e->getMessage());
    }
}else{
    header("Location: ../Cart/CartMainPage.php");
}

$pdo = null;
$stmt1 = null;
$stmt2= null;
$stmt3 = null;
$stmt4 = null;
$stmt5 = null;
$stmt6 = null;
$stmt7 = null;
$stmt8 = null;