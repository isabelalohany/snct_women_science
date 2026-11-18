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

/**
 * Lista dados administrativos sem consultar nem retornar hashes de senha.
 *
 * @return list<array<string, mixed>>
 */
function listarUsuariosAdministrativos(mysqli $conexao): array
{
    $consulta = $conexao->query(
        'SELECT id_usuario, nome_completo, email, tipo_usuario, criado_em, atualizado_em
         FROM usuarios
         ORDER BY atualizado_em DESC, id_usuario DESC'
    );

    return $consulta->fetch_all(MYSQLI_ASSOC);
}

/**
 * Retorna a quantidade de contas por tipo de usuário.
 *
 * @return array<string, int>
 */
function contarUsuariosPorTipo(mysqli $conexao): array
{
    $quantidades = [
        'usuario' => 0,
        'administrador' => 0,
    ];
    $consulta = $conexao->query(
        'SELECT tipo_usuario, COUNT(*) AS total
         FROM usuarios
         GROUP BY tipo_usuario'
    );

    while ($linha = $consulta->fetch_assoc()) {
        $tipo = $linha['tipo_usuario'];

        if (array_key_exists($tipo, $quantidades)) {
            $quantidades[$tipo] = (int) $linha['total'];
        }
    }

    return $quantidades;
}
