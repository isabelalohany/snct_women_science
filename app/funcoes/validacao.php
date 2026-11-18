<?php

/**
 * Valida os dados recebidos no cadastro público de usuários.
 */
function validarCadastroUsuario(string $nomeCompleto, string $email, string $senha): array
{
    $erros = [];

    if ($nomeCompleto === '') {
        $erros['nome_completo'] = 'Informe seu nome completo.';
    } elseif (mb_strlen($nomeCompleto) > 150) {
        $erros['nome_completo'] = 'O nome pode ter no máximo 150 caracteres.';
    }

    if ($email === '') {
        $erros['email'] = 'Informe seu e-mail.';
    } elseif (mb_strlen($email) > 190 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = 'Informe um e-mail válido.';
    }

    if (strlen($senha) < 6 || strlen($senha) > 72) {
        $erros['senha'] = 'A senha deve ter entre 6 e 72 caracteres.';
    }

    return $erros;
}

/**
 * Valida e normaliza os campos do formulário de indicação de cientista.
 *
 * @param array<string, string> $dados
 * @return array<string, string>
 */
function validarIndicacaoCientista(array $dados): array
{
    $erros = [];
    $limites = [
        'nome_completo' => 180,
        'nacionalidade' => 100,
        'area_atuacao' => 255,
        'biografia' => 10000,
        'contribuicoes' => 10000,
        'informacoes_adicionais' => 5000,
        'fontes_referencias' => 5000,
    ];
    $camposObrigatorios = [
        'nome_completo' => 'Informe o nome completo da cientista.',
        'area_atuacao' => 'Informe a área de atuação científica.',
        'biografia' => 'Informe a biografia e a trajetória profissional.',
        'contribuicoes' => 'Informe as principais descobertas e contribuições.',
        'fontes_referencias' => 'Informe as fontes e referências utilizadas.',
    ];

    foreach ($camposObrigatorios as $campo => $mensagem) {
        if ($dados[$campo] === '') {
            $erros[$campo] = $mensagem;
        }
    }

    foreach ($limites as $campo => $limite) {
        if (mb_strlen($dados[$campo]) > $limite) {
            $erros[$campo] = 'Este campo pode ter no máximo ' . $limite . ' caracteres.';
        }
    }

    if ($dados['data_nascimento'] !== '') {
        $dataNascimento = DateTimeImmutable::createFromFormat('!Y-m-d', $dados['data_nascimento']);
        $errosData = DateTimeImmutable::getLastErrors();

        if (
            $dataNascimento === false
            || ($errosData !== false && ($errosData['warning_count'] > 0 || $errosData['error_count'] > 0))
            || $dataNascimento->format('Y-m-d') !== $dados['data_nascimento']
        ) {
            $erros['data_nascimento'] = 'Informe uma data de nascimento válida.';
        } elseif ($dataNascimento > new DateTimeImmutable('today')) {
            $erros['data_nascimento'] = 'A data de nascimento não pode estar no futuro.';
        }
    }

    return $erros;
}
