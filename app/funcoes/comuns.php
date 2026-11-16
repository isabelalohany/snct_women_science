<?php

/**
 * Escapa conteúdo textual antes de exibi-lo no HTML.
 */
function escapar(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

/**
 * Monta uma URL usando a base configurada no arquivo .env.
 */
function urlAplicacao(string $caminho = ''): string
{
    $urlBase = rtrim(configuracao('APP_URL', ''), '/');
    $caminho = ltrim($caminho, '/');

    if ($caminho === '') {
        return $urlBase;
    }

    return $urlBase . '/' . $caminho;
}

/**
 * Redireciona após operações POST para evitar reenvio de formulários.
 */
function redirecionar(string $caminho): void
{
    header('Location: ' . urlAplicacao($caminho));
    exit;
}
