<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST["fullName"];
    $email = $_POST["email"];
    $pass = $_POST["password"];
    $password = password_hash($pass, PASSWORD_DEFAULT);
    $province = $_POST["province"];
    $city = $_POST["city"];
    $userId = $_SESSION['user_id'];
    $streetAddress = $_POST["streetAddress"];
    $postalCode = $_POST["postalCode"];

    try {
        require "dbh.inc.php";

        if ($pass) {
            $query = "UPDATE users
                        SET
                            fullName = :fullName,
                            email = :email,
                            userPassword = :_password,
                            Province = :province,
                            City = :city,
                            StreetAddress = :_address,
                            PostalCode = :postalCode
                        WHERE id = :userID;";
        } else {
            $query = "UPDATE users
                        SET
                            fullName = :fullName,
                            email = :email,
                            Province = :province,
                            City = :city,
                            StreetAddress = :_address,
                            PostalCode = :postalCode
                        WHERE id = :userID;";
        }

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":fullName", $fullName);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":province", $province);
        $stmt->bindParam(":city", $city);
        $stmt->bindParam(":_address", $streetAddress);
        $stmt->bindParam(":postalCode", $postalCode);
        $stmt->bindParam(":userID", $userId);

        if ($pass) {
            $stmt->bindParam(":_password", $password);
        }

        $stmt->execute();

        $pdo = null;
        $stmt = null;

        header("Location: ../Login/login.php");
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../MainPage/CreateListingPage.php");
}