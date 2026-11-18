<?php

require_once dirname(__DIR__, 2) . '/app/config/conexao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/autenticacao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/cientistas.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/upload.php';

exigirAdministrador();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$tokenCsrf = $_POST['token_csrf'] ?? '';
$idRecebido = $_POST['id_cientista'] ?? '';
$idCientista = is_string($idRecebido)
    ? filter_var($idRecebido, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
    : false;

if (!is_string($tokenCsrf) || !tokenCsrfEhValido($tokenCsrf)) {
    http_response_code(403);
    exit;
}

if ($idCientista === false) {
    http_response_code(400);
    exit;
}

try {
    $conexao = obterConexao();
    $cientista = buscarCientistaAdministrativamente($conexao, (int) $idCientista);

    if ($cientista === null) {
        http_response_code(404);
        exit;
    }

    if (!excluirCientistaAdministrativamente($conexao, (int) $idCientista)) {
        throw new RuntimeException('O perfil não foi excluído.');
    }

    if (!removerImagemCientista($cientista['imagem_path'])) {
        adicionarMensagemFlash(
            'aviso',
            'O perfil foi excluído, mas não foi possível remover a fotografia do armazenamento.'
        );
    } else {
        adicionarMensagemFlash('sucesso', 'O perfil da cientista foi excluído.');
    }
} catch (Throwable $erro) {
    error_log('Erro ao excluir perfil na área administrativa: ' . $erro->getMessage());
    adicionarMensagemFlash('erro', 'Não foi possível excluir o perfil agora. Tente novamente mais tarde.');
}

redirecionar('admin/cientistas.php');
