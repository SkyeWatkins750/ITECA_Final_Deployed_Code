<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] !== 'admin') {
    header("Location: ../Login/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Login/login.php");
    exit();
}

$action = $_POST['action'] ?? '';

if ($action === 'delete') {
    deleteUser();
} elseif ($action === 'update') {
    updateUser();
}

function deleteUser(){
    try {
        $userId = $_POST['id'];
        require_once "dbh.inc.php";

        $query = "DELETE FROM users WHERE id = :userID;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":userID", $userId);

        $stmt->execute();

        $pdo = null;
        $stmt = null;

        die();
    }catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

function updateUser(){
    try {
        $userId = $_POST['id'];
        require_once "dbh.inc.php";
        $fullName = $_POST['fullName'];
        $email = $_POST['email'];
        $password = $_POST['userPassword'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $accessLevel = $_POST['accessLevel'];
        $province = $_POST['Province'];
        $city = $_POST['City'];
        $address = $_POST['StreetAddress'];
        $postalCode = $_POST['PostalCode'];

        $query = "UPDATE users SET fullName = :fullName, email = :email, userPassword = :_password, Province = :province, accessLevel = :accessLevel, City = :city, StreetAddress = :_address, PostalCode = :postalCode WHERE id = :userID;";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":fullName", $fullName);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":_password", $hashedPassword);
        $stmt->bindParam(":province", $province);
        $stmt->bindParam(":accessLevel", $accessLevel);
        $stmt->bindParam(":city", $city);
        $stmt->bindParam(":_address", $address);
        $stmt->bindParam(":postalCode", $postalCode);
        $stmt->bindParam(":userID", $userId);

        $stmt->execute();

        $query = "SELECT * FROM users WHERE id = :userID;";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":userID", $userId);

        $stmt->execute();

        $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($updatedUser);

        $pdo = null;
        $stmt = null;

        die();
    }catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}