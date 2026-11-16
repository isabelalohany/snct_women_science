<?php

require_once __DIR__ . '/comuns.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/flash.php';

/**
 * Registra na sessão somente os dados necessários para identificar o acesso.
 */
function iniciarSessaoUsuario(array $usuario): void
{
    session_regenerate_id(true);

    $_SESSION['usuario'] = [
        'id_usuario' => (int) $usuario['id_usuario'],
        'nome_completo' => $usuario['nome_completo'],
        'tipo_usuario' => $usuario['tipo_usuario'],
    ];
}

/**
 * Retorna os dados do usuário autenticado ou null quando não há login.
 */
function usuarioAtual(): ?array
{
    if (empty($_SESSION['usuario']) || !is_array($_SESSION['usuario'])) {
        return null;
    }

    return $_SESSION['usuario'];
}

/**
 * Interrompe acessos de visitantes em páginas restritas.
 */
function exigirLogin(): void
{
    if (usuarioAtual() !== null) {
        return;
    }

    adicionarMensagemFlash('aviso', 'Entre na sua conta para acessar esta página.');
    redirecionar('login.php');
}

/**
 * Interrompe acessos de usuários comuns à área administrativa.
 */
function exigirAdministrador(): void
{
    $usuario = usuarioAtual();

    if ($usuario === null) {
        adicionarMensagemFlash('aviso', 'Entre na sua conta para acessar esta página.');
        redirecionar('login.php');
    }

    if ($usuario['tipo_usuario'] !== 'administrador') {
        adicionarMensagemFlash('erro', 'Você não possui permissão para acessar esta página.');
        redirecionar('index.php');
    }
}

/**
 * Remove a conta da sessão e cria uma nova sessão para a mensagem de logout.
 */
function encerrarSessaoUsuario(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $parametros = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $parametros['path'],
            'domain' => $parametros['domain'],
            'secure' => $parametros['secure'],
            'httponly' => $parametros['httponly'],
            'samesite' => $parametros['samesite'] ?? 'Lax',
        ]);
    }

    session_destroy();
    session_start();
    session_regenerate_id(true);
}
