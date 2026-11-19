<?php

require_once dirname(__DIR__) . '/app/config/conexao.php';
require_once dirname(__DIR__) . '/app/funcoes/cientistas.php';

$idValidado = isset($_GET['id']) && is_string($_GET['id'])
    ? filter_var($_GET['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
    : false;

if ($idValidado === false) {
    http_response_code(404);
    exit;
}

try {
    $cientista = buscarCientistaPublicada(obterConexao(), (int) $idValidado);
} catch (Throwable $erro) {
    error_log('Erro ao carregar perfil público de cientista: ' . $erro->getMessage());
    http_response_code(500);
    exit;
}

if ($cientista === null) {
    http_response_code(404);
    exit;
}

$tituloPagina = $cientista['nome_completo'] . ' | Mulheres na Ciência';
$paginaAtiva = 'catalogo';
require_once dirname(__DIR__) . '/app/componentes/cabecalho.php';
?>

<main id="conteudo-principal" class="pagina-formulario">
    <article class="container">
        <a class="link-voltar-catalogo" href="<?= escapar(urlAplicacao('catalogo.php')) ?>">
            &larr; Voltar ao catálogo
        </a>

        <header class="perfil-cientista-cabecalho mt-4 mb-5">
            <?php if ($cientista['imagem_path'] !== null): ?>
                <img class="imagem-perfil-cientista"
                    src="<?= escapar(urlAplicacao('imagem.php?id=' . (int) $cientista['id_cientista'])) ?>"
                    alt="Fotografia de <?= escapar($cientista['nome_completo']) ?>">
            <?php endif; ?>
            <div>
                <p class="sobretitulo text-primary">Perfil publicado</p>
                <h1 class="titulo-secao"><?= escapar($cientista['nome_completo']) ?></h1>
                <p class="h5 texto-formulario"><?= escapar($cientista['area_atuacao']) ?></p>
            </div>
        </header>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <section class="mb-5" aria-labelledby="titulo-trajetoria">
                    <h2 class="h4 titulo-secao mb-3" id="titulo-trajetoria">Biografia e trajetória</h2>
                    <p class="texto-perfil-cientista"><?= nl2br(escapar($cientista['biografia'])) ?></p>
                </section>

                <section class="mb-5" aria-labelledby="titulo-contribuicoes">
                    <h2 class="h4 titulo-secao mb-3" id="titulo-contribuicoes">Descobertas e contribuições</h2>
                    <p class="texto-perfil-cientista"><?= nl2br(escapar($cientista['contribuicoes'])) ?></p>
                </section>

                <?php if ($cientista['nacionalidade'] !== null || $cientista['data_nascimento'] !== null): ?>
                    <section class="mb-5" aria-labelledby="titulo-dados">
                        <h2 class="h4 titulo-secao mb-3" id="titulo-dados">Dados biográficos</h2>
                        <dl class="row">
                            <?php if ($cientista['nacionalidade'] !== null): ?>
                                <dt class="col-sm-4">Nacionalidade</dt>
                                <dd class="col-sm-8"><?= escapar($cientista['nacionalidade']) ?></dd>
                            <?php endif; ?>
                            <?php if ($cientista['data_nascimento'] !== null): ?>
                                <dt class="col-sm-4">Data de nascimento</dt>
                                <dd class="col-sm-8"><?= escapar(date('d/m/Y', strtotime($cientista['data_nascimento']))) ?></dd>
                            <?php endif; ?>
                        </dl>
                    </section>
                <?php endif; ?>

                <?php if ($cientista['informacoes_adicionais'] !== null && $cientista['informacoes_adicionais'] !== ''): ?>
                    <section class="mb-5" aria-labelledby="titulo-informacoes">
                        <h2 class="h4 titulo-secao mb-3" id="titulo-informacoes">Informações adicionais</h2>
                        <p class="texto-perfil-cientista"><?= nl2br(escapar($cientista['informacoes_adicionais'])) ?></p>
                    </section>
                <?php endif; ?>

                <section aria-labelledby="titulo-fontes">
                    <h2 class="h4 titulo-secao mb-3" id="titulo-fontes">Fontes e referências</h2>
                    <p class="texto-perfil-cientista"><?= nl2br(escapar($cientista['fontes_referencias'])) ?></p>
                </section>
            </div>
        </div>
    </article>
</main>

<?php require_once dirname(__DIR__) . '/app/componentes/rodape.php'; ?>
