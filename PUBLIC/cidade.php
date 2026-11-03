<?php
require_once __DIR__ . '/../INFRA/bootstrap.php';
exigir_login();

$cidades = [
    'joinville' => [
        'titulo' => 'Joinville - SC',
        'imagem' => 'joinville_arco.jpg',
        'textos' => [
            'A ideia de um metrô em Joinville surge como uma alternativa futura para aliviar o crescimento do trânsito e o aumento da frota de veículos, superando a atual dependência do sistema de ônibus. Contudo, devido ao alto custo de construção e à dispersão urbana da cidade, opções mais práticas e baratas como faixas exclusivas, BRT ou bondes modernos são recomendadas para o curto prazo, seguindo o modelo bem-sucedido de Curitiba.',
            'Portanto, o metrô deve ser considerado um plano estratégico de longo prazo. Para viabilizá-lo, Joinville precisa planejar seu crescimento urbano de forma organizada e integrar seus diferentes meios de transporte desde já.',
        ],
    ],
    'rio' => [
        'titulo' => 'Rio de Janeiro - RJ',
        'imagem' => 'RJ_Cristo.jpg',
        'textos' => [
            'O planejamento urbano do Rio de Janeiro sempre enfrentou problemas de mobilidade. O MetrôRio surgiu para facilitar os deslocamentos e diminuir a dependência dos carros e ônibus. Porém, sua rede ainda é limitada, principalmente em áreas da Zona Oeste, e a integração com outros transportes precisa melhorar. Por isso, o futuro da cidade depende da expansão do metrô, da integração dos transportes e de um planejamento urbano mais eficiente.',
        ],
    ],
    'brasilia' => [
        'titulo' => 'Brasília - DF',
        'imagem' => 'igreja-brasilia.jpg',
        'textos' => [
            'O planejamento urbano de Brasília foi concebido de forma bastante diferente de outras cidades brasileiras, já que nasceu a partir de um projeto moderno e planejado. Idealizada por Lúcio Costa e Oscar Niemeyer, a cidade foi estruturada para priorizar a organização espacial e a circulação de veículos. No entanto, essa priorização do transporte individual acabou gerando, ao longo do tempo, uma forte dependência de carros, o que trouxe desafios para a mobilidade urbana, especialmente com o crescimento das regiões administrativas ao redor do Plano Piloto.',
            'Nesse contexto, o Metrô do Distrito Federal foi implantado como uma alternativa para melhorar o deslocamento da população, principalmente ligando áreas mais afastadas, como Ceilândia e Samambaia, ao centro da capital. Apesar de sua importância, o sistema ainda possui uma cobertura limitada e não atende plenamente todas as regiões, o que faz com que muitos moradores continuem dependentes de ônibus ou transporte individual.',
        ],
    ],
];

$chave = $_GET['c'] ?? '';
if (!isset($cidades[$chave])) {
    redirecionar('home.php');
}

$cidade = $cidades[$chave];
$temImagem = is_file(__DIR__ . '/../assets/img/' . $cidade['imagem']);

$titulo = $cidade['titulo'];
require __DIR__ . '/../INFRA/partials/topo.php';
?>
<div class="cidade card">
    <h1><?= e($cidade['titulo']) ?></h1>

    <?php if ($temImagem): ?>
        <img src="../assets/img/<?= e($cidade['imagem']) ?>" alt="<?= e($cidade['titulo']) ?>">
    <?php endif; ?>

    <?php foreach ($cidade['textos'] as $paragrafo): ?>
        <p><?= e($paragrafo) ?></p>
    <?php endforeach; ?>

    <a class="botao secundario" href="home.php">Voltar</a>
</div>
<?php require __DIR__ . '/../INFRA/partials/rodape.php'; ?>
