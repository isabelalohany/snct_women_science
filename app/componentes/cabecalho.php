<?php

/**
 * Cabeçalho compartilhado das páginas públicas.
 * Cada página pode definir $tituloPagina e $paginaAtiva antes desta inclusão.
 */

$tituloPagina = $tituloPagina ?? 'Mulheres na Ciência';
$paginaAtiva = $paginaAtiva ?? '';
$urlBase = rtrim(configuracao('APP_URL', ''), '/');
$tituloSeguro = htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8');
$urlBaseSeguro = htmlspecialchars($urlBase, ENT_QUOTES, 'UTF-8');
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
                        <li class="nav-item mt-2 mt-lg-0">
                            <a class="btn btn-outline-light btn-sm px-3" href="<?= $urlBaseSeguro ?>/login.php">Entrar</a>
                        </li>
                        <li class="nav-item mt-2 mt-lg-0">
                            <a class="btn btn-acento btn-sm px-3" href="<?= $urlBaseSeguro ?>/cadastro.php">Criar conta</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
