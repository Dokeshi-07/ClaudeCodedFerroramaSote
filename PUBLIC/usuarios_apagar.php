<?php
require_once __DIR__ . '/../INFRA/bootstrap.php';
exigir_admin();
exigir_post('usuarios.php');

if ((int) ($_POST['id'] ?? 0) === (int) $_SESSION['userId']) {
    flash('erro', 'Você não pode apagar o seu próprio usuário.');
    redirecionar('usuarios.php');
}

apagar_registro(
    $conexao,
    'usuarios',
    'usuarios.php',
    'Usuário apagado com sucesso.',
    'Não é possível apagar: este usuário é responsável por trens cadastrados.'
);
