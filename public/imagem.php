<?php

require_once dirname(__DIR__) . '/app/config/conexao.php';
require_once dirname(__DIR__) . '/app/funcoes/autenticacao.php';
require_once dirname(__DIR__) . '/app/funcoes/cientistas.php';
require_once dirname(__DIR__) . '/app/funcoes/upload.php';

$idValidado = isset($_GET['id']) && is_string($_GET['id'])
    ? filter_var($_GET['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
    : false;

if ($idValidado === false) {
    http_response_code(404);
    exit;
}

try {
    $cientista = buscarCientistaAdministrativamente(obterConexao(), (int) $idValidado);
} catch (Throwable $erro) {
    error_log('Erro ao localizar fotografia de cientista: ' . $erro->getMessage());
    http_response_code(500);
    exit;
}

if ($cientista === null) {
    http_response_code(404);
    exit;
}

$usuario = usuarioAtual();
$administradorLogado = $usuario !== null && $usuario['tipo_usuario'] === 'administrador';

if ($cientista['status'] !== 'publicada' && !$administradorLogado) {
    http_response_code(404);
    exit;
}

$caminhoImagem = localizarImagemCientista($cientista['imagem_path']);

if ($caminhoImagem === null || !is_readable($caminhoImagem)) {
    http_response_code(404);
    exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);

if ($finfo === false) {
    error_log('Não foi possível verificar o tipo da fotografia solicitada.');
    http_response_code(500);
    exit;
}

$tipoMime = finfo_file($finfo, $caminhoImagem);
finfo_close($finfo);

if (!in_array($tipoMime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
    error_log('Uma fotografia armazenada possui tipo inválido.');
    http_response_code(404);
    exit;
}

$tamanho = filesize($caminhoImagem);

if ($tamanho === false) {
    http_response_code(404);
    exit;
}

header('Content-Type: ' . $tipoMime);
header('Content-Length: ' . $tamanho);
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
readfile($caminhoImagem);
