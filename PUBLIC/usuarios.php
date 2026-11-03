<?php
// Área administrativa: só administradores.
require_once __DIR__ . '/../INFRA/bootstrap.php';
exigir_admin();

$erros = [];
$dados = ['nome_usuario' => '', 'email_usuario' => '', 'telefone_usuario' => '', 'senha_usuario' => '', 'administrador' => 0];

if (eh_post()) {
    exigir_csrf();
    $dados = ler_usuario_post();
    $erros = validar_usuario($dados, true);

    if (!$erros) {
        try {
            inserir_usuario($conexao, $dados, $dados['administrador']);
            flash('ok', 'Usuário cadastrado com sucesso.');
            redirecionar('usuarios.php');
        } catch (mysqli_sql_exception $ex) {
            $erros[] = erro_usuario($ex);
        }
    }
}

// Consulta: filtra por nome ou e-mail. A senha (nem o hash) nunca é selecionada.
$busca = trim((string) ($_GET['q'] ?? ''));
$like = '%' . addcslashes($busca, '%_\\') . '%';

$stmt = $conexao->prepare(
    'SELECT id, nome_usuario, email_usuario, telefone_usuario, administrador
     FROM usuarios
     WHERE nome_usuario LIKE ? OR email_usuario LIKE ?
     ORDER BY nome_usuario'
);
$stmt->bind_param('ss', $like, $like);
$stmt->execute();
$usuarios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$acao = 'usuarios.php';
$botao = 'Adicionar usuário';
$exigeSenha = true;
$mostrarPerfil = true;

$titulo = 'Usuários';
require __DIR__ . '/../INFRA/partials/topo.php';
?>
<div class="grid-2">
    <section class="card">
        <h1>Cadastrar usuário</h1>
        <?php require __DIR__ . '/../INFRA/partials/mensagens.php'; ?>
        <?php require __DIR__ . '/../INFRA/partials/form_usuario.php'; ?>
    </section>

    <section class="card">
        <h2>Usuários cadastrados</h2>

        <form method="GET" action="usuarios.php" class="busca">
            <input type="search" name="q" value="<?= e($busca) ?>" placeholder="Buscar por nome ou e-mail">
            <button type="submit">Buscar</button>
            <?php if ($busca !== ''): ?>
                <a class="botao secundario" href="usuarios.php">Limpar</a>
            <?php endif; ?>
        </form>

        <div class="tabela">
            <table>
                <thead>
                    <tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>Perfil</th><th>Ação</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= e($u['nome_usuario']) ?></td>
                            <td><?= e($u['email_usuario']) ?></td>
                            <td><?= e($u['telefone_usuario']) ?></td>
                            <td><?= (int) $u['administrador'] === 1 ? 'Administrador' : 'Comum' ?></td>
                            <td>
                                <div class="acoes">
                                    <a class="botao" href="usuarios_editar.php?id=<?= (int) $u['id'] ?>">Editar</a>
                                    <?php if ((int) $u['id'] !== (int) $_SESSION['userId']): ?>
                                        <form action="usuarios_apagar.php" method="POST"
                                              onsubmit="return confirm('Tem certeza que deseja apagar este usuário?');">
                                            <?= csrf_campo() ?>
                                            <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                                            <button type="submit" class="perigo">Apagar</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$usuarios): ?>
                        <tr><td colspan="5">Nenhum usuário cadastrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php require __DIR__ . '/../INFRA/partials/rodape.php'; ?>
