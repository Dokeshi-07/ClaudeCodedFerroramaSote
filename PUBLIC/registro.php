<?php
// Cadastro público: todo usuário novo nasce como usuário comum.
require_once __DIR__ . '/../INFRA/bootstrap.php';

if (usuario_logado()) {
    redirecionar('home.php');
}

$erros = [];
$dados = ['nome_usuario' => '', 'email_usuario' => '', 'telefone_usuario' => '', 'senha_usuario' => '', 'administrador' => 0];

if (eh_post()) {
    exigir_csrf();
    $dados = ler_usuario_post();
    $erros = validar_usuario($dados, true);

    if (!$erros) {
        try {
            inserir_usuario($conexao, $dados, 0);
            flash('ok', 'Cadastro realizado. Faça login para continuar.');
            redirecionar('login.php');
        } catch (mysqli_sql_exception $ex) {
            $erros[] = erro_usuario($ex);
        }
    }
}

$acao = 'registro.php';
$botao = 'Criar conta';
$exigeSenha = true;
$mostrarPerfil = false;

$titulo = 'Criar conta';
require __DIR__ . '/../INFRA/partials/topo.php';
?>
<div class="estreito">
    <div class="card">
        <h1>Criar conta</h1>

        <?php require __DIR__ . '/../INFRA/partials/mensagens.php'; ?>
        <?php require __DIR__ . '/../INFRA/partials/form_usuario.php'; ?>

        <p><a href="login.php">Voltar para o login</a></p>
    </div>
</div>
<?php require __DIR__ . '/../INFRA/partials/rodape.php'; ?>
