<?php
require_once __DIR__ . '/../INFRA/bootstrap.php';
exigir_login();

$campos = ['nome_trem', 'tipo_trem', 'carga', 'id_usuario'];
$dados = array_fill_keys($campos, '');
$erros = [];

if (eh_post()) {
    exigir_csrf();
    $dados = ler_post($campos);
    $erros = validar_trem($dados);

    if (!$erros) {
        try {
            $idUsuario = (int) $dados['id_usuario'];
            $stmt = $conexao->prepare(
                'INSERT INTO trens (nome_trem, tipo_trem, carga, id_usuario) VALUES (?, ?, ?, ?)'
            );
            $stmt->bind_param('sssi', $dados['nome_trem'], $dados['tipo_trem'], $dados['carga'], $idUsuario);
            $stmt->execute();
            $stmt->close();

            flash('ok', 'Trem cadastrado com sucesso.');
            redirecionar('trens.php');
        } catch (mysqli_sql_exception $ex) {
            $erros[] = mensagem_erro_banco($ex, 'Não foi possível cadastrar o trem.', [
                1452 => 'Selecione um usuário responsável existente.',
            ]);
        }
    }
}

$usuarios = $conexao->query('SELECT id, nome_usuario FROM usuarios ORDER BY nome_usuario')->fetch_all(MYSQLI_ASSOC);

$trens = $conexao->query(
    'SELECT t.id, t.nome_trem, t.tipo_trem, t.carga, u.nome_usuario
     FROM trens t
     INNER JOIN usuarios u ON u.id = t.id_usuario
     ORDER BY t.id DESC'
)->fetch_all(MYSQLI_ASSOC);

$acao = 'trens.php';
$botao = 'Cadastrar trem';

$titulo = 'Trens';
require __DIR__ . '/../INFRA/partials/topo.php';
?>
<div class="grid-2">
    <section class="card">
        <h1>Cadastrar trem</h1>
        <?php require __DIR__ . '/../INFRA/partials/mensagens.php'; ?>
        <?php require __DIR__ . '/../INFRA/partials/form_trem.php'; ?>
    </section>

    <section class="card">
        <h2>Trens cadastrados</h2>
        <div class="tabela">
            <table>
                <thead>
                    <tr><th>Nome</th><th>Tipo</th><th>Carga</th><th>Responsável</th><th>Ação</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($trens as $trem): ?>
                        <tr>
                            <td><?= e($trem['nome_trem']) ?></td>
                            <td><?= e($trem['tipo_trem']) ?></td>
                            <td><?= e($trem['carga']) ?></td>
                            <td><?= e($trem['nome_usuario']) ?></td>
                            <td>
                                <div class="acoes">
                                    <a class="botao" href="trens_editar.php?id=<?= (int) $trem['id'] ?>">Editar</a>
                                    <form action="trens_apagar.php" method="POST"
                                          onsubmit="return confirm('Tem certeza que deseja apagar este trem?');">
                                        <?= csrf_campo() ?>
                                        <input type="hidden" name="id" value="<?= (int) $trem['id'] ?>">
                                        <button type="submit" class="perigo">Apagar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$trens): ?>
                        <tr><td colspan="5">Nenhum trem cadastrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php require __DIR__ . '/../INFRA/partials/rodape.php'; ?>
