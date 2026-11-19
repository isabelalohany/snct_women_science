<?php

/**
 * Salva uma indicação de usuário sempre como pendente.
 *
 * @param array<string, string> $dados
 */
function criarIndicacaoCientista(mysqli $conexao, array $dados, int $usuarioId, ?string $imagemPath): int
{
    $status = 'pendente';
    $nacionalidade = $dados['nacionalidade'] !== '' ? $dados['nacionalidade'] : null;
    $dataNascimento = $dados['data_nascimento'] !== '' ? $dados['data_nascimento'] : null;
    $informacoesAdicionais = $dados['informacoes_adicionais'] !== ''
        ? $dados['informacoes_adicionais']
        : null;
    $cadastro = $conexao->prepare(
        'INSERT INTO cientistas (
            nome_completo,
            nacionalidade,
            data_nascimento,
            area_atuacao,
            biografia,
            contribuicoes,
            informacoes_adicionais,
            imagem_path,
            fontes_referencias,
            status,
            usuario_indicador_id,
            alterado_por_usuario_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $cadastro->bind_param(
        'ssssssssssii',
        $dados['nome_completo'],
        $nacionalidade,
        $dataNascimento,
        $dados['area_atuacao'],
        $dados['biografia'],
        $dados['contribuicoes'],
        $informacoesAdicionais,
        $imagemPath,
        $dados['fontes_referencias'],
        $status,
        $usuarioId,
        $usuarioId
    );
    $cadastro->execute();

    return (int) $conexao->insert_id;
}

/**
 * Lista os perfis para consulta administrativa, do mais recentemente alterado.
 *
 * @return list<array<string, mixed>>
 */
function listarCientistasAdministrativas(mysqli $conexao): array
{
    $consulta = $conexao->query(
        'SELECT
            c.id_cientista,
            c.nome_completo,
            c.area_atuacao,
            c.status,
            c.criado_em,
            c.atualizado_em,
            u.nome_completo AS indicador_nome
         FROM cientistas AS c
         LEFT JOIN usuarios AS u ON u.id_usuario = c.usuario_indicador_id
         ORDER BY c.atualizado_em DESC, c.id_cientista DESC'
    );

    return $consulta->fetch_all(MYSQLI_ASSOC);
}

/**
 * Retorna a quantidade de perfis em cada situação do fluxo.
 *
 * @return array<string, int>
 */
function contarCientistasPorStatus(mysqli $conexao): array
{
    $quantidades = [
        'pendente' => 0,
        'publicada' => 0,
        'rejeitada' => 0,
        'oculta' => 0,
    ];
    $consulta = $conexao->query(
        'SELECT status, COUNT(*) AS total
         FROM cientistas
         GROUP BY status'
    );

    while ($linha = $consulta->fetch_assoc()) {
        $status = $linha['status'];

        if (array_key_exists($status, $quantidades)) {
            $quantidades[$status] = (int) $linha['total'];
        }
    }

    return $quantidades;
}

/**
 * Lista somente os perfis publicados, em ordem alfabética.
 *
 * @return list<array<string, mixed>>
 */
function listarCientistasPublicadas(mysqli $conexao): array
{
    $status = 'publicada';
    $consulta = $conexao->prepare(
        'SELECT id_cientista, nome_completo, area_atuacao, imagem_path
         FROM cientistas
         WHERE status = ?
         ORDER BY nome_completo ASC, id_cientista ASC'
    );
    $consulta->bind_param('s', $status);
    $consulta->execute();

    return $consulta->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Busca informações públicas somente de um perfil publicado.
 *
 * @return array<string, mixed>|null
 */
function buscarCientistaPublicada(mysqli $conexao, int $idCientista): ?array
{
    $status = 'publicada';
    $consulta = $conexao->prepare(
        'SELECT
            id_cientista,
            nome_completo,
            nacionalidade,
            data_nascimento,
            area_atuacao,
            biografia,
            contribuicoes,
            informacoes_adicionais,
            imagem_path,
            fontes_referencias
         FROM cientistas
         WHERE id_cientista = ? AND status = ?
         LIMIT 1'
    );
    $consulta->bind_param('is', $idCientista, $status);
    $consulta->execute();
    $cientista = $consulta->get_result()->fetch_assoc();

    return $cientista ?: null;
}

/**
 * Busca um perfil completo para o formulário administrativo.
 *
 * @return array<string, mixed>|null
 */
function buscarCientistaAdministrativamente(mysqli $conexao, int $idCientista): ?array
{
    $consulta = $conexao->prepare(
        'SELECT
            id_cientista,
            nome_completo,
            nacionalidade,
            data_nascimento,
            area_atuacao,
            biografia,
            contribuicoes,
            informacoes_adicionais,
            imagem_path,
            fontes_referencias,
            status
         FROM cientistas
         WHERE id_cientista = ?
         LIMIT 1'
    );
    $consulta->bind_param('i', $idCientista);
    $consulta->execute();
    $cientista = $consulta->get_result()->fetch_assoc();

    return $cientista ?: null;
}

/**
 * Cadastra um perfil diretamente como administrador.
 *
 * @param array<string, string> $dados
 */
function criarCientistaAdministrativamente(
    mysqli $conexao,
    array $dados,
    int $administradorId,
    string $status,
    ?string $imagemPath
): int {
    $nacionalidade = $dados['nacionalidade'] !== '' ? $dados['nacionalidade'] : null;
    $dataNascimento = $dados['data_nascimento'] !== '' ? $dados['data_nascimento'] : null;
    $informacoesAdicionais = $dados['informacoes_adicionais'] !== ''
        ? $dados['informacoes_adicionais']
        : null;
    $publicadoEm = $status === 'publicada' ? date('Y-m-d H:i:s') : null;
    $cadastro = $conexao->prepare(
        'INSERT INTO cientistas (
            nome_completo,
            nacionalidade,
            data_nascimento,
            area_atuacao,
            biografia,
            contribuicoes,
            informacoes_adicionais,
            imagem_path,
            fontes_referencias,
            status,
            alterado_por_usuario_id,
            publicado_em
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $cadastro->bind_param(
        'ssssssssssis',
        $dados['nome_completo'],
        $nacionalidade,
        $dataNascimento,
        $dados['area_atuacao'],
        $dados['biografia'],
        $dados['contribuicoes'],
        $informacoesAdicionais,
        $imagemPath,
        $dados['fontes_referencias'],
        $status,
        $administradorId,
        $publicadoEm
    );
    $cadastro->execute();

    return (int) $conexao->insert_id;
}

/**
 * Atualiza os campos do perfil sem alterar seu status ou os dados de análise.
 *
 * @param array<string, string> $dados
 */
function atualizarCientistaAdministrativamente(
    mysqli $conexao,
    int $idCientista,
    array $dados,
    int $administradorId,
    ?string $imagemPath
): void {
    $nacionalidade = $dados['nacionalidade'] !== '' ? $dados['nacionalidade'] : null;
    $dataNascimento = $dados['data_nascimento'] !== '' ? $dados['data_nascimento'] : null;
    $informacoesAdicionais = $dados['informacoes_adicionais'] !== ''
        ? $dados['informacoes_adicionais']
        : null;
    $atualizacao = $conexao->prepare(
        'UPDATE cientistas
         SET nome_completo = ?,
             nacionalidade = ?,
             data_nascimento = ?,
             area_atuacao = ?,
             biografia = ?,
             contribuicoes = ?,
             informacoes_adicionais = ?,
             imagem_path = COALESCE(?, imagem_path),
             fontes_referencias = ?,
             alterado_por_usuario_id = ?
         WHERE id_cientista = ?'
    );
    $atualizacao->bind_param(
        'sssssssssii',
        $dados['nome_completo'],
        $nacionalidade,
        $dataNascimento,
        $dados['area_atuacao'],
        $dados['biografia'],
        $dados['contribuicoes'],
        $informacoesAdicionais,
        $imagemPath,
        $dados['fontes_referencias'],
        $administradorId,
        $idCientista
    );
    $atualizacao->execute();
}

/**
 * Exclui permanentemente um perfil.
 */
function excluirCientistaAdministrativamente(mysqli $conexao, int $idCientista): bool
{
    $exclusao = $conexao->prepare('DELETE FROM cientistas WHERE id_cientista = ?');
    $exclusao->bind_param('i', $idCientista);
    $exclusao->execute();

    return $exclusao->affected_rows === 1;
}

/**
 * Aplica somente as transições administrativas permitidas para o status atual.
 */
function alterarStatusCientista(
    mysqli $conexao,
    int $idCientista,
    string $novoStatus,
    int $administradorId
): bool {
    $cientista = buscarCientistaAdministrativamente($conexao, $idCientista);

    if ($cientista === null) {
        return false;
    }

    $transicoesPermitidas = [
        'pendente' => ['publicada', 'rejeitada'],
        'publicada' => ['oculta'],
        'oculta' => ['publicada'],
        'rejeitada' => [],
    ];
    $statusAtual = $cientista['status'];

    if (!in_array($novoStatus, $transicoesPermitidas[$statusAtual] ?? [], true)) {
        return false;
    }

    $dataDecisao = date('Y-m-d H:i:s');
    $dataPublicacao = $novoStatus === 'publicada' ? $dataDecisao : null;
    $atualizacao = $conexao->prepare(
        'UPDATE cientistas
         SET status = ?,
             analisado_por_usuario_id = ?,
             analisado_em = ?,
             alterado_por_usuario_id = ?,
             publicado_em = CASE WHEN ? = \'publicada\' THEN ? ELSE publicado_em END
         WHERE id_cientista = ? AND status = ?'
    );
    $atualizacao->bind_param(
        'sisissis',
        $novoStatus,
        $administradorId,
        $dataDecisao,
        $administradorId,
        $novoStatus,
        $dataPublicacao,
        $idCientista,
        $statusAtual
    );
    $atualizacao->execute();

    return $atualizacao->affected_rows === 1;
}
