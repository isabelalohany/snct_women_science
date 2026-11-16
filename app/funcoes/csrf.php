<?php

/**
 * Cria e mantém um token de proteção CSRF na sessão atual.
 */
function gerarTokenCsrf(): string
{
    if (empty($_SESSION['token_csrf'])) {
        $_SESSION['token_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['token_csrf'];
}

/**
 * Compara o token recebido com o token da sessão de forma segura.
 */
function tokenCsrfEhValido(string $tokenRecebido): bool
{
    if (empty($_SESSION['token_csrf']) || $tokenRecebido === '') {
        return false;
    }

    return hash_equals($_SESSION['token_csrf'], $tokenRecebido);
}
