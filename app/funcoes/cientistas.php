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
