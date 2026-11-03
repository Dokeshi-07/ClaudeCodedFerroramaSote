<?php
require_once __DIR__ . '/../INFRA/bootstrap.php';
exigir_login();
exigir_post('trens.php');

apagar_registro(
    $conexao,
    'trens',
    'trens.php',
    'Trem apagado com sucesso.',
    'Não é possível apagar: este trem possui rotas vinculadas.'
);
