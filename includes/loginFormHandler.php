<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $errors = [];

    $result = checkEmailExists($email);

    if (empty($result)) {
        $errors['emailError'] = "Email does not exist";
    }
    // elseif ($result['userPassword'] != $password) {
    //     $errors['passwordError'] = "Password is incorrect";
    // }
    elseif (!password_verify($password, $result['userPassword'])) {
        $errors['passwordError'] = "Password is incorrect";
    }

    if (!empty($errors)) {
        $_SESSION = array_merge($_SESSION, $errors);
        $_SESSION['email'] = $email;
        header("Location: ../Login/login.php");
        exit();
    }else{
        // session_unset();
        $_SESSION["email"] = $email;
        header("Location: ../MainPage/MainPage.php");
    }

    // $_SESSION["email"] = $email;
    // header("Location: ../MainPage/MainPage.php");
}else{
    header("Location: ../Login/login.php");
}

function checkEmailExists($email) {    
    try {
        require_once "dbh.inc.php";

        $query = "SELECT email, userPassword FROM users WHERE email = :useremail;";

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