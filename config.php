<?php
ini_set("session.use_only_cookies",1);
ini_set("session.use_strict_mode",1);

session_set_cookie_params([
    'lifetime' => 0,
    'domain' => 'myTrader.free.nf', // this is just localhost for new but change it to particular website
    'path' => '/',
    'secure' => true,
    'httponly' => true
]);

session_start();
if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}else {
    $interval = 60 * 30;
    // $interval = 10;

    if (time() - $_SESSION['last_regeneration'] >= $interval) {
        $backup = $_SESSION;
        session_regenerate_id(true);
        $_SESSION = $backup;
        $_SESSION['last_regeneration'] = time();
    }
}