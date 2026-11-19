<?php

require_once dirname(__DIR__) . '/app/config/conexao.php';
require_once dirname(__DIR__) . '/app/funcoes/autenticacao.php';
require_once dirname(__DIR__) . '/app/funcoes/cientistas.php';

$tituloPagina = 'Mulheres na Ciência';
$paginaAtiva = 'inicio';
$usuarioLogado = usuarioAtual();
$mensagemFlash = obterMensagemFlash();
$cientistasRecentes = null;

try {
    $cientistasRecentes = listarCientistasRecentes(obterConexao());
} catch (Throwable $erro) {
    error_log('Erro ao carregar cientistas recentes na página inicial: ' . $erro->getMessage());
}

$destaques = $cientistasRecentes === null ? [] : array_slice($cientistasRecentes, 0, 3);
$outrasCientistasRecentes = $cientistasRecentes === null
    ? []
    : array_slice($cientistasRecentes, 3);

require_once dirname(__DIR__) . '/app/componentes/cabecalho.php';
?>

<main id="conteudo-principal">
    <?php if ($mensagemFlash !== null): ?>
        <div class="container pt-4">
            <div class="alert alert-<?= escapar(classeMensagemFlash($mensagemFlash['tipo'])) ?> mb-0" role="alert">
                <?= escapar($mensagemFlash['texto']) ?>
            </div>
        </div>
    <?php endif; ?>
    <section class="hero">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <p class="sobretitulo">Conhecimento que transforma</p>
                    <h1 class="titulo-hero">Mulheres que fazem a ciência avançar.</h1>
                    <p class="texto-hero mt-4 mb-4">
                        Um espaço para conhecer trajetórias, descobertas e contribuições de mulheres
                        que marcaram — e continuam marcando — a história da ciência.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <a class="btn btn-acento btn-lg px-4" href="<?= escapar(urlAplicacao('catalogo.php')) ?>">
                            Explorar catálogo
                        </a>
                        <a class="btn btn-outline-light btn-lg px-4" href="<?= escapar(urlAplicacao($usuarioLogado === null ? 'cadastro.php' : 'indicar-cientista.php')) ?>">
                            Indicar uma cientista
                        </a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="painel-hero">
                        <div class="painel-hero-icone" aria-hidden="true">⌁</div>
                        <h2 class="h3 mt-4">Ciência com mais visibilidade</h2>
                        <p class="mb-0 text-white-50">
                            O portal reúne histórias de mulheres cientistas e permite que novas
                            contribuições sejam indicadas pela comunidade.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="secao-pagina" aria-labelledby="titulo-proposito">
        <div class="container">
            <div class="mb-5">
                <p class="sobretitulo text-primary">Sobre o projeto</p>
                <h2 class="titulo-secao" id="titulo-proposito">Conhecer é também reconhecer.</h2>
                <p class="texto-secao mt-3 mb-0">
                    Mulheres na Ciência é um portal educativo e colaborativo criado para valorizar
                    cientistas, suas trajetórias profissionais e suas contribuições para o conhecimento.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <article class="cartao-proposito">
                        <div class="icone-cartao" aria-hidden="true">01</div>
                        <h3>Conheça</h3>
                        <p>Explore perfis com biografias, áreas de atuação e descobertas científicas.</p>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="cartao-proposito">
                        <div class="icone-cartao" aria-hidden="true">02</div>
                        <h3>Valorize</h3>
                        <p>Reconheça a presença feminina em diferentes épocas e campos da ciência.</p>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="cartao-proposito">
                        <div class="icone-cartao" aria-hidden="true">03</div>
                        <h3>Colabore</h3>
                        <p>Crie uma conta para indicar mulheres cientistas que merecem estar no catálogo.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="secao-pagina secao-destaques" aria-labelledby="titulo-destaques">
        <div class="container">
            <div class="mb-4">
                <p class="sobretitulo text-primary">Conheça quem transforma a ciência</p>
                <h2 class="titulo-secao" id="titulo-destaques">Destaques</h2>
            </div>

            <?php if ($cientistasRecentes === null): ?>
                <div class="alert alert-danger" role="alert">
                    Não foi possível carregar as cientistas publicadas agora.
                    Você ainda pode visitar o <a href="<?= escapar(urlAplicacao('catalogo.php')) ?>">catálogo</a>.
                </div>
            <?php elseif ($destaques === []): ?>
                <div class="alert alert-info" role="status">
                    Ainda não há cientistas publicadas. As novas trajetórias aparecerão aqui após a aprovação.
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($destaques as $cientista): ?>
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
                                <h3 class="h5 titulo-secao"><?= escapar($cientista['nome_completo']) ?></h3>
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
    </section>

    <?php if ($outrasCientistasRecentes !== []): ?>
        <section class="secao-pagina pt-0" aria-labelledby="titulo-publicacoes-recentes">
            <div class="container">
                <div class="mb-4">
                    <p class="sobretitulo text-primary">Novas histórias no catálogo</p>
                    <h2 class="titulo-secao" id="titulo-publicacoes-recentes">Publicadas recentemente</h2>
                </div>
                <div class="row g-3">
                    <?php foreach ($outrasCientistasRecentes as $cientista): ?>
                        <div class="col-md-4">
                            <article class="cartao-proposito cartao-recente">
                                <h3 class="h5 titulo-secao"><?= escapar($cientista['nome_completo']) ?></h3>
                                <p class="texto-formulario"><?= escapar($cientista['area_atuacao']) ?></p>
                                <a href="<?= escapar(urlAplicacao('cientista.php?id=' . (int) $cientista['id_cientista'])) ?>">
                                    Ver perfil
                                </a>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="secao-chamada" aria-labelledby="titulo-participacao">
        <div class="container">
            <div class="chamada-interna row align-items-center g-4">
                <div class="col-lg-8">
                    <h2 class="titulo-secao mb-2" id="titulo-participacao">Uma história importante pode começar com uma indicação.</h2>
                    <p class="texto-secao mb-0">
                        Após o cadastro, você poderá enviar informações para análise administrativa.
                        Somente perfis aprovados serão publicados no catálogo público.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a class="btn btn-primary btn-lg px-4" href="<?= escapar(urlAplicacao($usuarioLogado === null ? 'cadastro.php' : 'indicar-cientista.php')) ?>">
                        <?= $usuarioLogado === null ? 'Criar minha conta' : 'Indicar uma cientista' ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once dirname(__DIR__) . '/app/componentes/rodape.php'; ?>
