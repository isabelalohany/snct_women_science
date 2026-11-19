<?php

require_once dirname(__DIR__) . '/app/config/conexao.php';
require_once dirname(__DIR__) . '/app/funcoes/cientistas.php';

$tituloPagina = 'Catálogo de cientistas | Mulheres na Ciência';
$paginaAtiva = 'catalogo';
$cientistas = null;

try {
    $cientistas = listarCientistasPublicadas(obterConexao());
} catch (Throwable $erro) {
    error_log('Erro ao carregar catálogo público: ' . $erro->getMessage());
}

require_once dirname(__DIR__) . '/app/componentes/cabecalho.php';
?>

<main id="conteudo-principal" class="pagina-formulario">
    <div class="container">
        <header class="mb-5">
            <p class="sobretitulo text-primary">Conheça suas trajetórias</p>
            <h1 class="titulo-secao">Catálogo de cientistas</h1>
            <p class="texto-secao">
                Mulheres que contribuíram e contribuem para o avanço da ciência.
            </p>
        </header>

        <?php if ($cientistas === null): ?>
            <div class="alert alert-danger" role="alert">
                Não foi possível carregar o catálogo agora. Tente novamente mais tarde.
            </div>
        <?php elseif ($cientistas === []): ?>
            <div class="alert alert-info" role="status">
                Ainda não há perfis publicados no catálogo.
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($cientistas as $cientista): ?>
                    <div class="col-sm-6 col-lg-4">
                        <article class="cartao-proposito d-flex flex-column">
                            <?php if ($cientista['imagem_path'] !== null): ?>
                                <img class="imagem-cartao-cientista mb-3"
                                    src="<?= escapar(urlAplicacao('imagem.php?id=' . (int) $cientista['id_cientista'])) ?>"
                                    alt="Fotografia de <?= escapar($cientista['nome_completo']) ?>"
                                    loading="lazy">
                            <?php else: ?>
                                <div class="imagem-cartao-cientista imagem-cartao-sem-foto mb-3" aria-hidden="true">
                                    <span>✦</span>
                                </div>
                            <?php endif; ?>

                            <h2 class="h5 titulo-secao"><?= escapar($cientista['nome_completo']) ?></h2>
                            <p class="texto-formulario"><?= escapar($cientista['area_atuacao']) ?></p>
                            <a class="btn btn-outline-primary mt-auto align-self-start"
                                href="<?= escapar(urlAplicacao('cientista.php?id=' . (int) $cientista['id_cientista'])) ?>">
                                Conhecer trajetória
                            </a>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once dirname(__DIR__) . '/app/componentes/rodape.php'; ?>
