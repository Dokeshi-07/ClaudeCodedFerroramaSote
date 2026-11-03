<?php
// Espera: $dados, $acao, $botao, $rotas (id, nome_rota)
?>
<form method="POST" action="<?= e($acao) ?>">
    <?= csrf_campo() ?>

    <label for="tipo_sensor">Tipo de sensor</label>
    <select id="tipo_sensor" name="tipo_sensor" required>
        <option value="">Selecione o tipo</option>
        <?php foreach (TIPOS_SENSOR as $tipo): ?>
            <option value="<?= e($tipo) ?>" <?= $dados['tipo_sensor'] === $tipo ? 'selected' : '' ?>>
                <?= e($tipo) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="id_rota">Rota</label>
    <select id="id_rota" name="id_rota" required>
        <option value="">Selecione uma rota</option>
        <?php foreach ($rotas as $rota): ?>
            <option value="<?= (int) $rota['id'] ?>"
                <?= (string) $rota['id'] === (string) $dados['id_rota'] ? 'selected' : '' ?>>
                <?= e($rota['nome_rota']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="dados_sensor">Dados do sensor</label>
    <textarea id="dados_sensor" name="dados_sensor" rows="3" required
              placeholder="Ex.: sensor instalado no eixo dianteiro"><?= e($dados['dados_sensor']) ?></textarea>

    <label for="valor_capturado">Valor capturado (opcional)</label>
    <input type="text" id="valor_capturado" name="valor_capturado" inputmode="decimal"
           value="<?= e($dados['valor_capturado']) ?>">

    <label for="limite_disparo">Limite de disparo (opcional)</label>
    <input type="text" id="limite_disparo" name="limite_disparo" inputmode="decimal"
           value="<?= e($dados['limite_disparo']) ?>">

    <button type="submit"><?= e($botao) ?></button>
</form>
