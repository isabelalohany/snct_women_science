<?php

/**
 * Cabeçalho compartilhado das páginas públicas.
 * Cada página pode definir $tituloPagina e $paginaAtiva antes desta inclusão.
 */

$tituloPagina = $tituloPagina ?? 'Mulheres na Ciência';
$paginaAtiva = $paginaAtiva ?? '';
require_once dirname(__DIR__) . '/funcoes/comuns.php';

$usuarioLogado = function_exists('usuarioAtual') ? usuarioAtual() : null;
$tituloSeguro = escapar($tituloPagina);
$urlBaseSeguro = escapar(urlAplicacao());
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Portal educativo para valorizar mulheres que contribuíram para a ciência.">
    <title><?= $tituloSeguro ?></title>
    <link rel="stylesheet" href="<?= $urlBaseSeguro ?>/assets/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $urlBaseSeguro ?>/assets/css/estilos.css">
</head>
<body>
    <a class="atalho-conteudo" href="#conteudo-principal">Pular para o conteúdo principal</a>

    <header class="cabecalho-site shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-dark" aria-label="Navegação principal">
            <div class="container">
                <a class="navbar-brand marca-site" href="<?= $urlBaseSeguro ?>/index.php">
                    <span class="marca-simbolo" aria-hidden="true">✦</span>
                    Mulheres na Ciência
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#menuPrincipal" aria-controls="menuPrincipal"
                    aria-expanded="false" aria-label="Abrir menu de navegação">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="menuPrincipal">
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                        <li class="nav-item">
                            <a class="nav-link <?= $paginaAtiva === 'inicio' ? 'active' : '' ?>"
                                <?= $paginaAtiva === 'inicio' ? 'aria-current="page"' : '' ?>
                                href="<?= $urlBaseSeguro ?>/index.php">Início</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $urlBaseSeguro ?>/catalogo.php">Catálogo</a>
                        </li>
                        <?php if ($usuarioLogado !== null): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $urlBaseSeguro ?>/indicar-cientista.php">Indicar cientista</a>
                            </li>
                            <li class="nav-item mt-2 mt-lg-0">
                                <span class="navbar-text nome-usuario">Olá, <?= escapar($usuarioLogado['nome_completo']) ?></span>
                            </li>
                            <li class="nav-item mt-2 mt-lg-0">
                                <form method="post" action="<?= $urlBaseSeguro ?>/logout.php">
                                    <input type="hidden" name="token_csrf" value="<?= escapar(gerarTokenCsrf()) ?>">
                                    <button class="btn btn-outline-light btn-sm px-3" type="submit">Sair</button>
                                </form>
                            </li>
                        <?php else: ?>
                            <li class="nav-item mt-2 mt-lg-0">
                                <a class="btn btn-outline-light btn-sm px-3" href="<?= $urlBaseSeguro ?>/login.php">Entrar</a>
                            </li>
                            <li class="nav-item mt-2 mt-lg-0">
                                <a class="btn btn-acento btn-sm px-3" href="<?= $urlBaseSeguro ?>/cadastro.php">Criar conta</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
