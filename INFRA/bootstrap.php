<?php
// Incluído no topo de TODA página: sessão, conexão e funções comuns.
if (PHP_SAPI !== 'cli' && session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/helpers.php';
