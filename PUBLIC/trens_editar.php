<?php
require_once __DIR__ . '/../INFRA/bootstrap.php';
exigir_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('Trem inválido.');
}

$campos = ['nome_trem', 'tipo_trem', 'carga', 'id_usuario'];
$erros = [];

if (eh_post()) {
    exigir_csrf();
    $dados = ler_post($campos);
    $erros = validar_trem($dados);

    if (!$erros) {
        try {
            $idUsuario = (int) $dados['id_usuario'];
            $stmt = $conexao->prepare(
                'UPDATE trens
                 SET nome_trem = ?, tipo_trem = ?, carga = ?, id_usuario = ?
                 WHERE id = ?'
            );
            $stmt->bind_param('sssii', $dados['nome_trem'], $dados['tipo_trem'], $dados['carga'], $idUsuario, $id);
            $stmt->execute();
            $stmt->close();

            flash('ok', 'Trem atualizado com sucesso.');
            redirecionar('trens.php');
        } catch (mysqli_sql_exception $ex) {
            $erros[] = mensagem_erro_banco($ex, 'Não foi possível atualizar o trem.', [
                1452 => 'Selecione um usuário responsável existente.',
            ]);
        }
    }
} else {
    $dados = buscar_por_id(
        $conexao,
        'SELECT nome_trem, tipo_trem, carga, id_usuario FROM trens WHERE id = ?',
        $id
    );

    if (!$dados) {
        http_response_code(404);
        exit('Trem não encontrado.');
    }
}

$usuarios = $conexao->query('SELECT id, nome_usuario FROM usuarios ORDER BY nome_usuario')->fetch_all(MYSQLI_ASSOC);

$acao = 'trens_editar.php?id=' . $id;
$botao = 'Salvar alterações';

$titulo = 'Editar trem';
require __DIR__ . '/../INFRA/partials/topo.php';
?>
<div class="estreito">
    <div class="card">
        <h1>Editar trem</h1>
        <?php require __DIR__ . '/../INFRA/partials/mensagens.php'; ?>
        <?php require __DIR__ . '/../INFRA/partials/form_trem.php'; ?>
        <p><a href="trens.php">Voltar para os trens</a></p>
    </div>
</div>
<?php require __DIR__ . '/../INFRA/partials/rodape.php'; ?>
