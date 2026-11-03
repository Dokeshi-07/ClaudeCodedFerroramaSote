<?php $flash = flash_pegar(); ?>
<?php if ($flash !== null): ?>
    <p class="msg <?= $flash[0] === 'ok' ? 'msg-ok' : 'msg-erro' ?>"><?= e($flash[1]) ?></p>
<?php endif; ?>

<?php foreach (($erros ?? []) as $mensagemErro): ?>
    <p class="msg msg-erro"><?= e($mensagemErro) ?></p>
<?php endforeach; ?>
