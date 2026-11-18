<?php

require_once dirname(__DIR__, 2) . '/app/config/conexao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/autenticacao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/cientistas.php';

exigirAdministrador();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$tokenCsrf = $_POST['token_csrf'] ?? '';
$idRecebido = $_POST['id_cientista'] ?? '';
$statusRecebido = $_POST['novo_status'] ?? '';
$idCientista = is_string($idRecebido)
    ? filter_var($idRecebido, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
    : false;
$statusSolicitado = is_string($statusRecebido) ? $statusRecebido : '';

if (!is_string($tokenCsrf) || !tokenCsrfEhValido($tokenCsrf)) {
    http_response_code(403);
    exit;
}

if ($idCientista === false || !in_array($statusSolicitado, ['publicada', 'rejeitada', 'oculta'], true)) {
    http_response_code(400);
    exit;
}

$usuario = usuarioAtual();

try {
    $alterado = alterarStatusCientista(
        obterConexao(),
        (int) $idCientista,
        $statusSolicitado,
        (int) $usuario['id_usuario']
    );

    if ($alterado) {
        $mensagens = [
            'publicada' => 'O perfil foi publicado.',
            'rejeitada' => 'A indicação foi rejeitada.',
            'oculta' => 'O perfil foi ocultado.',
        ];
        adicionarMensagemFlash('sucesso', $mensagens[$statusSolicitado]);
    } else {
        adicionarMensagemFlash(
            'erro',
            'Não foi possível aplicar essa alteração. Verifique a situação atual do perfil.'
        );
    }
} catch (Throwable $erro) {
    error_log('Erro ao alterar situação de cientista: ' . $erro->getMessage());
    adicionarMensagemFlash('erro', 'Não foi possível alterar a situação agora. Tente novamente mais tarde.');
}

redirecionar('admin/cientistas.php');
