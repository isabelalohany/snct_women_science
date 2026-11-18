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
