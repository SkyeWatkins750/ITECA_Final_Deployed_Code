<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['accessLevel'] !== 'admin') {
    header("Location: ../Login/login.php");
}

try {
    $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 100;
    $search = isset($_POST['search']) ? $_POST['search'] : '';



    require_once "dbh.inc.php";

    $sql = "SELECT * FROM users";
    $params = [];

    if (!empty($search)) {
        $sql .= " WHERE fullName LIKE ? OR email LIKE ?";
        $searchTerm = "%" . $search . "%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    $sql .= " ORDER BY createdAt DESC LIMIT ?, ?";
    $params[] = $offset;
    $params[] = $limit;

    $stmt = $pdo->prepare($sql);

    $bindIndex = 1;
    foreach ($params as $param) {
        $type = is_int($param) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $stmt->bindValue($bindIndex++, $param, $type);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $pdo = null;
    $stmt = null;

    if(!empty($results)) {
        foreach ($results as $row) {
                echo "<tr data-id='".$row['id']."'>";
                echo "<td>".$row['id']."</td>";
                echo "<td contenteditable='true'>".$row['fullName']."</td>";
                echo "<td contenteditable='true'>".$row['email']."</td>";
                echo "<td contenteditable='true'>".$row['userPassword']."</td>";
                echo "<td contenteditable='true'>".$row['accessLevel']."</td>";
                echo "<td contenteditable='true'>".$row['Province']."</td>";
                echo "<td contenteditable='true'>".$row['City']."</td>";
                echo "<td contenteditable='true'>".$row['StreetAddress']."</td>";
                echo "<td contenteditable='true'>".$row['PostalCode']."</td>";
                echo "<td contenteditable='true'>".$row['createdAt']."</td>";
                echo "<td class='action-buttons'>
                        <i class='bi bi-pencil-square' onclick='saveChanges(this)'></i>
                        <i class='bi bi-trash' onclick='deleteUser(this)'></i>
                        </td>";
                echo "</tr>";
            }
    }
}catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}