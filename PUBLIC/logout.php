<?php
require_once __DIR__ . '/../INFRA/bootstrap.php';

if (!eh_post()) {
    redirecionar('login.php');
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}

session_destroy();
redirecionar('login.php');
