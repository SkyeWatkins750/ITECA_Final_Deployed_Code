<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $accessLevel = "user";
    $province = $_POST["province"];
    $city = $_POST["city"];
    $streetAddress = $_POST["street"];
    $postalCode = $_POST["postal"];

    try {
        require_once "dbh.inc.php";

        $query = "INSERT INTO users (accessLevel, email, fullname, userPassword, Province, City, StreetAddress, PostalCode) 
        VALUES (:accessLevel, :email, :fullname, :userPassword, :Province, :City, :StreetAddress, :PostalCode);";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":accessLevel", $accessLevel);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":fullname", $username);
        $stmt->bindParam(":userPassword", $hashedPassword);
        $stmt->bindParam(":Province", $province);
        $stmt->bindParam("City", $city);
        $stmt->bindParam("StreetAddress", $streetAddress);
        $stmt->bindParam("PostalCode", $postalCode);

        $stmt->execute();

        $pdo = null;
        $stmt = null;

        header("Location: ../Login/login.php");

        die();
    }catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}
else{
    header("Location: ../Login/createAccount.php");
}