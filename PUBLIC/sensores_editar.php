<?php
require_once __DIR__ . '/../INFRA/bootstrap.php';
exigir_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('Sensor inválido.');
}

$campos = ['tipo_sensor', 'dados_sensor', 'id_rota', 'valor_capturado', 'limite_disparo'];
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
                'UPDATE sensores
                 SET tipo_sensor = ?, dados_sensor = ?, id_rota = ?, valor_capturado = ?, limite_disparo = ?
                 WHERE id = ?'
            );
            $stmt->bind_param('ssiddi', $dados['tipo_sensor'], $dados['dados_sensor'], $idRota, $valor, $limite, $id);
            $stmt->execute();
            $stmt->close();

            flash('ok', 'Sensor atualizado com sucesso.');
            redirecionar('sensores.php');
        } catch (mysqli_sql_exception $ex) {
            $erros[] = mensagem_erro_banco($ex, 'Não foi possível atualizar o sensor.', [
                1452 => 'Selecione uma rota existente.',
            ]);
        }
    }
} else {
    $dados = buscar_por_id(
        $conexao,
        'SELECT tipo_sensor, dados_sensor, id_rota, valor_capturado, limite_disparo FROM sensores WHERE id = ?',
        $id
    );

    if (!$dados) {
        http_response_code(404);
        exit('Sensor não encontrado.');
    }
    $dados['valor_capturado'] = (string) ($dados['valor_capturado'] ?? '');
    $dados['limite_disparo']  = (string) ($dados['limite_disparo'] ?? '');
}

$rotas = $conexao->query('SELECT id, nome_rota FROM rotas ORDER BY nome_rota')->fetch_all(MYSQLI_ASSOC);

$acao = 'sensores_editar.php?id=' . $id;
$botao = 'Salvar alterações';

$titulo = 'Editar sensor';
require __DIR__ . '/../INFRA/partials/topo.php';
?>
<div class="estreito">
    <div class="card">
        <h1>Editar sensor</h1>
        <?php require __DIR__ . '/../INFRA/partials/mensagens.php'; ?>
        <?php require __DIR__ . '/../INFRA/partials/form_sensor.php'; ?>
        <p><a href="sensores.php">Voltar para os sensores</a></p>
    </div>
</div>
<?php require __DIR__ . '/../INFRA/partials/rodape.php'; ?>
