<?php
require_once __DIR__ . '/../INFRA/bootstrap.php';
exigir_login();

$campos = ['tipo_sensor', 'dados_sensor', 'id_rota', 'valor_capturado', 'limite_disparo'];
$dados = array_fill_keys($campos, '');
$erros = [];

if (eh_post()) {
    exigir_csrf();
    $dados = ler_post($campos);
    $erros = validar_sensor($dados);

    if (!$erros) {
        try {
            $idRota = (int) $dados['id_rota'];
            $valor  = numero_ou_nulo($dados['valor_capturado']);
            $limite = numero_ou_nulo($dados['limite_disparo']);

            $stmt = $conexao->prepare(
                'INSERT INTO sensores (tipo_sensor, dados_sensor, id_rota, valor_capturado, limite_disparo)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->bind_param('ssidd', $dados['tipo_sensor'], $dados['dados_sensor'], $idRota, $valor, $limite);
            $stmt->execute();
            $stmt->close();

            flash('ok', 'Sensor cadastrado com sucesso.');
            redirecionar('sensores.php');
        } catch (mysqli_sql_exception $ex) {
            $erros[] = mensagem_erro_banco($ex, 'Não foi possível cadastrar o sensor.', [
                1452 => 'Selecione uma rota existente.',
            ]);
        }
    }
}

$rotas = $conexao->query('SELECT id, nome_rota FROM rotas ORDER BY nome_rota')->fetch_all(MYSQLI_ASSOC);

$sensores = $conexao->query(
    'SELECT s.id, s.tipo_sensor, s.dados_sensor, s.valor_capturado, s.limite_disparo, r.nome_rota
     FROM sensores s
     INNER JOIN rotas r ON r.id = s.id_rota
     ORDER BY s.id DESC'
)->fetch_all(MYSQLI_ASSOC);

$acao = 'sensores.php';
$botao = 'Cadastrar sensor';

$titulo = 'Sensores';
require __DIR__ . '/../INFRA/partials/topo.php';
?>
<div class="grid-2">
    <section class="card">
        <h1>Cadastrar sensor</h1>
        <?php require __DIR__ . '/../INFRA/partials/mensagens.php'; ?>
        <?php require __DIR__ . '/../INFRA/partials/form_sensor.php'; ?>
    </section>

    <section class="card">
        <h2>Sensores cadastrados</h2>
        <div class="tabela">
            <table>
                <thead>
                    <tr><th>Tipo</th><th>Rota</th><th>Dados</th><th>Valor</th><th>Limite</th><th>Status</th><th>Ação</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($sensores as $sensor): ?>
                        <?php $status = status_sensor($sensor['valor_capturado'], $sensor['limite_disparo']); ?>
                        <tr>
                            <td><?= e($sensor['tipo_sensor']) ?></td>
                            <td><?= e($sensor['nome_rota']) ?></td>
                            <td><?= e($sensor['dados_sensor']) ?></td>
                            <td><?= e($sensor['valor_capturado'] ?? '-') ?></td>
                            <td><?= e($sensor['limite_disparo'] ?? '-') ?></td>
                            <td>
                                <span class="tag <?= $status === 'Disparado' ? 'tag-disparado' : ($status === 'Normal' ? 'tag-normal' : '') ?>">
                                    <?= e($status) ?>
                                </span>
                            </td>
                            <td>
                                <div class="acoes">
                                    <a class="botao" href="sensores_editar.php?id=<?= (int) $sensor['id'] ?>">Editar</a>
                                    <form action="sensores_apagar.php" method="POST"
                                          onsubmit="return confirm('Tem certeza que deseja apagar este sensor?');">
                                        <?= csrf_campo() ?>
                                        <input type="hidden" name="id" value="<?= (int) $sensor['id'] ?>">
                                        <button type="submit" class="perigo">Apagar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$sensores): ?>
                        <tr><td colspan="7">Nenhum sensor cadastrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php require __DIR__ . '/../INFRA/partials/rodape.php'; ?>
