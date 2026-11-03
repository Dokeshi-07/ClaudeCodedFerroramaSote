<?php
require_once __DIR__ . '/../INFRA/bootstrap.php';
exigir_login();
exigir_post('sensores.php');

apagar_registro(
    $conexao,
    'sensores',
    'sensores.php',
    'Sensor apagado com sucesso.',
    'Não foi possível apagar o sensor.'
);
