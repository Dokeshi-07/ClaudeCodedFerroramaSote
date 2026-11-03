<?php
require_once __DIR__ . '/../INFRA/bootstrap.php';
exigir_admin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('Usuário inválido.');
}

$erros = [];

if (eh_post()) {
    exigir_csrf();
    $dados = ler_usuario_post();
    $erros = validar_usuario($dados, false);

    if ($id === (int) $_SESSION['userId'] && $dados['administrador'] !== 1) {
        $erros[] = 'Você não pode remover o seu próprio acesso de administrador.';
    }

    if (!$erros) {
        try {
            if ($dados['senha_usuario'] !== '') {
                $hash = password_hash($dados['senha_usuario'], PASSWORD_DEFAULT);
                $stmt = $conexao->prepare(
                    'UPDATE usuarios
                     SET nome_usuario = ?, email_usuario = ?, telefone_usuario = ?, senha_usuario = ?, administrador = ?
                     WHERE id = ?'
                );
                $stmt->bind_param('ssssii', $dados['nome_usuario'], $dados['email_usuario'],
                    $dados['telefone_usuario'], $hash, $dados['administrador'], $id);
            } else {
                $stmt = $conexao->prepare(
                    'UPDATE usuarios
                     SET nome_usuario = ?, email_usuario = ?, telefone_usuario = ?, administrador = ?
                     WHERE id = ?'
                );
                $stmt->bind_param('sssii', $dados['nome_usuario'], $dados['email_usuario'],
                    $dados['telefone_usuario'], $dados['administrador'], $id);
            }
            $stmt->execute();
            $stmt->close();

            if ($id === (int) $_SESSION['userId']) {
                $_SESSION['userName'] = $dados['nome_usuario'];
            }

            flash('ok', 'Usuário atualizado com sucesso.');
            redirecionar('usuarios.php');
        } catch (mysqli_sql_exception $ex) {
            $erros[] = erro_usuario($ex);
        }
    }
} else {
    $dados = buscar_por_id(
        $conexao,
        'SELECT nome_usuario, email_usuario, telefone_usuario, administrador FROM usuarios WHERE id = ?',
        $id
    );

    if (!$dados) {
        http_response_code(404);
        exit('Usuário não encontrado.');
    }
    $dados['senha_usuario'] = '';
}

$acao = 'usuarios_editar.php?id=' . $id;
$botao = 'Salvar alterações';
$exigeSenha = false;
$mostrarPerfil = true;

$titulo = 'Editar usuário';
require __DIR__ . '/../INFRA/partials/topo.php';
?>
<div class="estreito">
    <div class="card">
        <h1>Editar usuário</h1>
        <?php require __DIR__ . '/../INFRA/partials/mensagens.php'; ?>
        <?php require __DIR__ . '/../INFRA/partials/form_usuario.php'; ?>
        <p><a href="usuarios.php">Voltar para os usuários</a></p>
    </div>
</div>
<?php require __DIR__ . '/../INFRA/partials/rodape.php'; ?>
