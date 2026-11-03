<?php
// Espera: $dados, $acao, $botao, $usuarios (id, nome_usuario)
?>
<form method="POST" action="<?= e($acao) ?>">
    <?= csrf_campo() ?>

    <label for="nome_trem">Nome</label>
    <input type="text" id="nome_trem" name="nome_trem" maxlength="100"
           value="<?= e($dados['nome_trem']) ?>" required>

    <label for="tipo_trem">Tipo</label>
    <input type="text" id="tipo_trem" name="tipo_trem" maxlength="100"
           value="<?= e($dados['tipo_trem']) ?>" required>

    <label for="carga">Carga</label>
    <input type="text" id="carga" name="carga" maxlength="100"
           value="<?= e($dados['carga']) ?>" required>

    <label for="id_usuario">Usuário responsável</label>
    <select id="id_usuario" name="id_usuario" required>
        <option value="">Selecione um usuário</option>
        <?php foreach ($usuarios as $usuario): ?>
            <option value="<?= (int) $usuario['id'] ?>"
                <?= (string) $usuario['id'] === (string) $dados['id_usuario'] ? 'selected' : '' ?>>
                <?= e($usuario['nome_usuario']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit"><?= e($botao) ?></button>
</form>
