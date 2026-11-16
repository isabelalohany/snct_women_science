<?php

/**
 * Guarda uma mensagem para a próxima página carregada após um redirecionamento.
 */
function adicionarMensagemFlash(string $tipo, string $texto): void
{
    $_SESSION['mensagem_flash'] = [
        'tipo' => $tipo,
        'texto' => $texto,
    ];
}

/**
 * Retorna a mensagem temporária e a remove da sessão.
 */
function obterMensagemFlash(): ?array
{
    if (empty($_SESSION['mensagem_flash'])) {
        return null;
    }

    $mensagem = $_SESSION['mensagem_flash'];
    unset($_SESSION['mensagem_flash']);

    return $mensagem;
}

/**
 * Converte os tipos usados pela aplicação em classes visuais do Bootstrap.
 */
function classeMensagemFlash(string $tipo): string
{
    $classes = [
        'sucesso' => 'success',
        'erro' => 'danger',
        'aviso' => 'warning',
    ];

    return $classes[$tipo] ?? 'info';
}
