<?php
// Espera: $dados, $acao, $botao, $exigeSenha (bool), $mostrarPerfil (bool)
?>
<form method="POST" action="<?= e($acao) ?>">
    <?= csrf_campo() ?>

    <label for="nome_usuario">Nome de usuário</label>
    <input type="text" id="nome_usuario" name="nome_usuario" maxlength="100"
           value="<?= e($dados['nome_usuario']) ?>" required>

    <label for="email_usuario">E-mail</label>
    <input type="email" id="email_usuario" name="email_usuario" maxlength="150"
           value="<?= e($dados['email_usuario']) ?>" required>

    <label for="telefone_usuario">Telefone</label>
    <input type="tel" id="telefone_usuario" name="telefone_usuario" maxlength="15"
           inputmode="numeric" placeholder="(47) 99999-9999"
           pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}"
           value="<?= e($dados['telefone_usuario']) ?>" required>

    <label for="senha_usuario">Senha<?= $exigeSenha ? '' : ' (deixe em branco para manter a atual)' ?></label>
    <input type="password" id="senha_usuario" name="senha_usuario" minlength="6"
           autocomplete="new-password" <?= $exigeSenha ? 'required' : '' ?>>

    <?php if ($mostrarPerfil): ?>
        <label for="administrador">Perfil</label>
        <select id="administrador" name="administrador">
            <option value="0" <?= (int) $dados['administrador'] === 0 ? 'selected' : '' ?>>Comum</option>
            <option value="1" <?= (int) $dados['administrador'] === 1 ? 'selected' : '' ?>>Administrador</option>
        </select>
    <?php endif; ?>

    <button type="submit"><?= e($botao) ?></button>
</form>
<script src="../assets/js/telefone.js"></script>
