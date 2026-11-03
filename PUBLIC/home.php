<?php
require_once __DIR__ . '/../INFRA/bootstrap.php';
exigir_login();

$resumo = [];
foreach (['trens' => 'Trens', 'rotas' => 'Rotas', 'sensores' => 'Sensores'] as $tabela => $rotulo) {
    $resumo[$rotulo] = (int) $conexao->query("SELECT COUNT(*) FROM `$tabela`")->fetch_row()[0];
}
if (eh_admin()) {
    $resumo['Usuários'] = (int) $conexao->query('SELECT COUNT(*) FROM usuarios')->fetch_row()[0];
}

$titulo = 'Início';
require __DIR__ . '/../INFRA/partials/topo.php';
?>
<h1>Olá, <?= e($_SESSION['userName']) ?></h1>

<?php require __DIR__ . '/../INFRA/partials/mensagens.php'; ?>

<div class="resumo">
    <?php foreach ($resumo as $rotulo => $total): ?>
        <div class="card">
            <div class="numero"><?= $total ?></div>
            <div class="rotulo"><?= e($rotulo) ?> cadastrados</div>
        </div>
    <?php endforeach; ?>
</div>

<h2>Nossos projetos</h2>
<div class="cidades">
    <a href="cidade.php?c=joinville">
        <div class="card"><h3>Joinville - SC</h3><p>Conheça nossos projetos para o Sul.</p></div>
    </a>
    <a href="cidade.php?c=rio">
        <div class="card"><h3>Rio de Janeiro - RJ</h3><p>Venha conhecer nossos projetos na cidade maravilhosa.</p></div>
    </a>
    <a href="cidade.php?c=brasilia">
        <div class="card"><h3>Brasília - DF</h3><p>Veja nossos projetos na capital.</p></div>
    </a>
</div>
<?php require __DIR__ . '/../INFRA/partials/rodape.php'; ?>
