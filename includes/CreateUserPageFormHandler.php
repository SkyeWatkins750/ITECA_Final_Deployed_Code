<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['accessLevel'] !== 'admin') {
    header("Location: ../Login/login.php");
}

try {
    $fullName = $_POST["fullName"];
    $email = $_POST["email"];
    $pass = $_POST["password"];
    $password = password_hash($pass, PASSWORD_DEFAULT);
    $province = $_POST["province"];
    $city = $_POST["city"];
    $streetAddress = $_POST["streetAddress"];
    $postalCode = $_POST["postalCode"];
    $accessLevel = $_POST["accessLevel"];

    require_once "dbh.inc.php";

    $query = "INSERT INTO users (accessLevel, email, fullname, userPassword, Province, City, StreetAddress, PostalCode) 
        VALUES (:accessLevel, :email, :fullname, :userPassword, :Province, :City, :StreetAddress, :PostalCode);";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":accessLevel", $accessLevel);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":fullname", $fullName);
    $stmt->bindParam(":userPassword", $password);
    $stmt->bindParam(":Province", $province);
    $stmt->bindParam("City", $city);
    $stmt->bindParam("StreetAddress", $streetAddress);
    $stmt->bindParam("PostalCode", $postalCode);

    $stmt->execute();

    $results = $stmt->fetch(PDO::FETCH_ASSOC);

    $pdo = null;
    $stmt = null;

    header("Location: ../Admin/ViewUsersPage.php");
    die();
}catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}