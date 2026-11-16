<?php

/**
 * Busca uma conta pelo e-mail usado no login ou no cadastro.
 */
function buscarUsuarioPorEmail(mysqli $conexao, string $email): ?array
{
    $consulta = $conexao->prepare(
        'SELECT id_usuario, nome_completo, email, senha_hash, tipo_usuario
         FROM usuarios
         WHERE email = ?
         LIMIT 1'
    );
    $consulta->bind_param('s', $email);
    $consulta->execute();

    $resultado = $consulta->get_result();
    $usuario = $resultado->fetch_assoc();

    return $usuario ?: null;
}

/**
 * Cria exclusivamente uma conta de usuário comum pelo cadastro público.
 */
function criarUsuario(mysqli $conexao, string $nomeCompleto, string $email, string $senha): int
{
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    if ($senhaHash === false) {
        throw new RuntimeException('Não foi possível proteger a senha informada.');
    }

    $tipoUsuario = 'usuario';
    $cadastro = $conexao->prepare(
        'INSERT INTO usuarios (nome_completo, email, senha_hash, tipo_usuario)
         VALUES (?, ?, ?, ?)'
    );
    $cadastro->bind_param('ssss', $nomeCompleto, $email, $senhaHash, $tipoUsuario);
    $cadastro->execute();

    return (int) $conexao->insert_id;
}
