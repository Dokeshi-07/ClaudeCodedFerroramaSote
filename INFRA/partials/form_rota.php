<?php
// Espera: $dados, $acao, $botao, $trens (id, nome_trem)
?>
<form method="POST" action="<?= e($acao) ?>">
    <?= csrf_campo() ?>

    <label for="nome_rota">Nome da rota</label>
    <input type="text" id="nome_rota" name="nome_rota" maxlength="100"
           value="<?= e($dados['nome_rota']) ?>" required>

    <label for="estado_rota">Estado</label>
    <select id="estado_rota" name="estado_rota" required>
        <option value="">Selecione um estado</option>
        <?php foreach (ESTADOS as $estado): ?>
            <option value="<?= e($estado) ?>" <?= $dados['estado_rota'] === $estado ? 'selected' : '' ?>>
                <?= e($estado) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="local_rota">Localização</label>
    <input type="text" id="local_rota" name="local_rota" maxlength="150"
           value="<?= e($dados['local_rota']) ?>" required>

    <label for="id_trem">Trem</label>
    <select id="id_trem" name="id_trem" required>
        <option value="">Selecione um trem</option>
        <?php foreach ($trens as $trem): ?>
            <option value="<?= (int) $trem['id'] ?>"
                <?= (string) $trem['id'] === (string) $dados['id_trem'] ? 'selected' : '' ?>>
                <?= e($trem['nome_trem']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit"><?= e($botao) ?></button>
</form>
