<?php

require_once dirname(__DIR__, 2) . '/app/config/conexao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/autenticacao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/cientistas.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/upload.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/validacao.php';

exigirAdministrador();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$idRecebido = $_POST['id_cientista'] ?? '';
$idCientista = null;

if ($idRecebido !== '') {
    $idValidado = is_string($idRecebido)
        ? filter_var($idRecebido, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
        : false;

    if ($idValidado === false) {
        http_response_code(400);
        exit;
    }

    $idCientista = (int) $idValidado;
}

$dados = [
    'nome_completo' => '',
    'nacionalidade' => '',
    'data_nascimento' => '',
    'area_atuacao' => '',
    'biografia' => '',
    'contribuicoes' => '',
    'informacoes_adicionais' => '',
    'fontes_referencias' => '',
    'status' => '',
];

foreach ($dados as $campo => $valor) {
    $recebido = $_POST[$campo] ?? '';
    $dados[$campo] = is_string($recebido) ? trim($recebido) : '';
}

$erros = [];
$tokenCsrf = $_POST['token_csrf'] ?? '';

if (!is_string($tokenCsrf) || !tokenCsrfEhValido($tokenCsrf)) {
    $erros['geral'] = 'Não foi possível validar o formulário. Tente novamente.';
} else {
    $erros = validarIndicacaoCientista($dados);
}

if ($idCientista === null && !in_array($dados['status'], ['publicada', 'oculta'], true)) {
    $erros['status'] = 'Escolha se o perfil será publicado ou ficará oculto.';
}

$imagemPath = null;
$cientistaAtual = null;

if ($erros === []) {
    try {
        $conexao = obterConexao();

        if ($idCientista !== null) {
            $cientistaAtual = buscarCientistaAdministrativamente($conexao, $idCientista);

            if ($cientistaAtual === null) {
                http_response_code(404);
                exit;
            }
        }

        $arquivoImagem = $_FILES['fotografia'] ?? [];
        $imagemPath = processarUploadImagemCientista($arquivoImagem);
        $usuario = usuarioAtual();

        if ($idCientista === null) {
            $idCientista = criarCientistaAdministrativamente(
                $conexao,
                $dados,
                (int) $usuario['id_usuario'],
                $dados['status'],
                $imagemPath
            );
        } else {
            atualizarCientistaAdministrativamente(
                $conexao,
                $idCientista,
                $dados,
                (int) $usuario['id_usuario'],
                $imagemPath
            );

            if ($imagemPath !== null && !removerImagemCientista($cientistaAtual['imagem_path'])) {
                adicionarMensagemFlash(
                    'aviso',
                    'As informações foram salvas, mas não foi possível remover a fotografia anterior.'
                );
                redirecionar('admin/cientista-formulario.php?id=' . $idCientista);
            }
        }

        adicionarMensagemFlash('sucesso', 'As informações da cientista foram salvas.');
        redirecionar('admin/cientista-formulario.php?id=' . $idCientista);
    } catch (InvalidArgumentException $erro) {
        $erros['fotografia'] = $erro->getMessage();
    } catch (Throwable $erro) {
        if ($imagemPath !== null) {
            removerImagemCientista($imagemPath);
        }

        error_log('Erro ao salvar perfil na área administrativa: ' . $erro->getMessage());
        $erros['geral'] = 'Não foi possível salvar as informações agora. Tente novamente mais tarde.';
    }
}

$_SESSION['formulario_cientista'] = [
    'id_cientista' => $idCientista,
    'dados' => $dados,
    'erros' => $erros,
];
adicionarMensagemFlash('erro', 'Revise os campos destacados e tente salvar novamente.');

$destino = 'admin/cientista-formulario.php';

if ($idCientista !== null) {
    $destino .= '?id=' . $idCientista;
}

redirecionar($destino);
