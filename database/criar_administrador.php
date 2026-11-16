<?php

/**
 * Cria manualmente a primeira conta administrativa pelo terminal.
 * Este script não faz parte da interface pública do portal.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Este script só pode ser executado no terminal.');
}

require_once dirname(__DIR__) . '/app/config/conexao.php';

/**
 * Solicita um valor obrigatório no terminal.
 */
function solicitarCampo(string $rotulo): string
{
    do {
        fwrite(STDOUT, $rotulo);
        $valor = trim((string) fgets(STDIN));

        if ($valor === '') {
            fwrite(STDOUT, "Este campo é obrigatório.\n");
        }
    } while ($valor === '');

    return $valor;
}

$nomeCompleto = solicitarCampo('Nome completo do administrador: ');
$email = strtolower(solicitarCampo('E-mail do administrador: '));
$senha = solicitarCampo('Senha inicial: ');

if (mb_strlen($nomeCompleto) > 150) {
    fwrite(STDERR, "O nome não pode ter mais de 150 caracteres.\n");
    exit(1);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) {
    fwrite(STDERR, "Informe um e-mail válido de até 190 caracteres.\n");
    exit(1);
}

if (strlen($senha) < 6 || strlen($senha) > 72) {
    fwrite(STDERR, "A senha deve ter entre 6 e 72 caracteres.\n");
    exit(1);
}

try {
    $conexao = obterConexao();

    $consulta = $conexao->prepare(
        'SELECT id_usuario FROM usuarios WHERE email = ? LIMIT 1'
    );
    $consulta->bind_param('s', $email);
    $consulta->execute();
    $consulta->store_result();

    if ($consulta->num_rows > 0) {
        fwrite(STDERR, "Já existe uma conta com esse e-mail.\n");
        exit(1);
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    if ($senhaHash === false) {
        throw new RuntimeException('Não foi possível gerar o hash da senha.');
    }

    $tipoUsuario = 'administrador';
    $cadastro = $conexao->prepare(
        'INSERT INTO usuarios (nome_completo, email, senha_hash, tipo_usuario)
         VALUES (?, ?, ?, ?)'
    );
    $cadastro->bind_param('ssss', $nomeCompleto, $email, $senhaHash, $tipoUsuario);
    $cadastro->execute();

    fwrite(STDOUT, "Administrador criado com sucesso.\n");
} catch (Throwable $erro) {
    error_log('Erro ao criar administrador: ' . $erro->getMessage());
    fwrite(STDERR, "Não foi possível criar o administrador. Verifique o arquivo .env e o banco.\n");
    exit(1);
}
