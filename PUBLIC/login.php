<?php
require_once __DIR__ . '/../INFRA/bootstrap.php';

if (usuario_logado()) {
    redirecionar('home.php');
}

$erros = [];

if (eh_post()) {
    exigir_csrf();

    $nome  = trim($_POST['nome_usuario'] ?? '');
    $senha = $_POST['senha_usuario'] ?? '';

    if ($nome === '' || $senha === '') {
        $erros[] = 'Informe o nome de usuário e a senha.';
    } else {
        $stmt = $conexao->prepare(
            'SELECT id, nome_usuario, senha_usuario, administrador
             FROM usuarios WHERE nome_usuario = ? LIMIT 1'
        );
        $stmt->bind_param('s', $nome);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($usuario && password_verify($senha, $usuario['senha_usuario'])) {
            session_regenerate_id(true);
            $_SESSION['userId']        = (int) $usuario['id'];
            $_SESSION['userName']      = $usuario['nome_usuario'];
            $_SESSION['administrador'] = (int) $usuario['administrador'];

            redirecionar('home.php');
        }

        $erros[] = 'Nome de usuário ou senha incorretos.';
    }
}

$titulo = 'Login';
require __DIR__ . '/../INFRA/partials/topo.php';
?>
<div class="estreito">
    <div class="card">
        <h1>Entrar</h1>

        <?php require __DIR__ . '/../INFRA/partials/mensagens.php'; ?>

        <form method="POST" action="login.php">
            <?= csrf_campo() ?>

            <label for="nome_usuario">Nome de usuário</label>
            <input type="text" id="nome_usuario" name="nome_usuario" required>

            <label for="senha_usuario">Senha</label>
            <input type="password" id="senha_usuario" name="senha_usuario" required>

            <button type="submit">Entrar</button>
        </form>

        <p>Não tem conta? <a href="registro.php">Cadastre-se</a></p>
    </div>
</div>
<?php require __DIR__ . '/../INFRA/partials/rodape.php'; ?>
