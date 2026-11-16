<?php

require_once dirname(__DIR__) . '/app/config/bootstrap.php';
require_once dirname(__DIR__) . '/app/funcoes/autenticacao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$tokenCsrf = $_POST['token_csrf'] ?? '';

if (!is_string($tokenCsrf) || !tokenCsrfEhValido($tokenCsrf)) {
    http_response_code(403);
    exit;
}

encerrarSessaoUsuario();
adicionarMensagemFlash('sucesso', 'Sessão encerrada com segurança.');
redirecionar('index.php');
