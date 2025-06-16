<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Redirect to login page
header("Location: Login/login.php");
exit();
