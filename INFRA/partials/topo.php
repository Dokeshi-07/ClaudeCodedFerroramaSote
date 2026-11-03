<?php
// Espera: $titulo (string)
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?> - Ferrorama</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="home.php">Ferrorama</a>

    <?php if (usuario_logado()): ?>
        <nav class="menu">
            <a href="trens.php">Trens</a>
            <a href="rotas.php">Rotas</a>
            <a href="sensores.php">Sensores</a>
            <?php if (eh_admin()): ?>
                <a href="usuarios.php">Usuários</a>
            <?php endif; ?>
        </nav>

        <form method="POST" action="logout.php" class="sair">
            <span><?= e($_SESSION['userName'] ?? '') ?></span>
            <button type="submit" class="btn-link">Sair</button>
        </form>
    <?php endif; ?>
</header>

<main class="container">
