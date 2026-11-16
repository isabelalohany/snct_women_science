<?php

require_once dirname(__DIR__) . '/app/config/bootstrap.php';
require_once dirname(__DIR__) . '/app/funcoes/autenticacao.php';

$tituloPagina = 'Mulheres na Ciência';
$paginaAtiva = 'inicio';
$mensagemFlash = obterMensagemFlash();

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
                        <a class="btn btn-outline-light btn-lg px-4" href="<?= escapar(urlAplicacao('cadastro.php')) ?>">
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
                    <a class="btn btn-primary btn-lg px-4" href="<?= escapar(urlAplicacao('cadastro.php')) ?>">
                        Criar minha conta
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once dirname(__DIR__) . '/app/componentes/rodape.php'; ?>
