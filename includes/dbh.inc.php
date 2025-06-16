<?php
$host = "sql207.infinityfree.com";
$dbname = "if0_39152678_my_trader_db";
$dbusername = "if0_39152678";
$dbpassword = "QPf54iyU88N";

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}