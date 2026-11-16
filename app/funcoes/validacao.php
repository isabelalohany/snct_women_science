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
