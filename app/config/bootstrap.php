<?php

/**
 * Inicializa as configurações compartilhadas da aplicação.
 * Este arquivo deve ser carregado antes dos demais módulos PHP.
 */

/**
 * Lê um arquivo .env simples no formato CHAVE=VALOR.
 * O arquivo fica fora do diretório público para proteger credenciais.
 */
function carregarVariaveisAmbiente(string $caminhoArquivo): array
{
    if (!is_file($caminhoArquivo)) {
        throw new RuntimeException('O arquivo de configuração .env não foi encontrado.');
    }

    $variaveis = parse_ini_file($caminhoArquivo, false, INI_SCANNER_RAW);

    if ($variaveis === false) {
        throw new RuntimeException('Não foi possível ler o arquivo de configuração .env.');
    }

    return $variaveis;
}

/** @var array<string, string> $configuracoesAplicacao */
$configuracoesAplicacao = carregarVariaveisAmbiente(dirname(__DIR__, 2) . '/.env');

/**
 * Retorna uma configuração opcional ou o valor padrão informado.
 */
function configuracao(string $chave, string $valorPadrao = ''): string
{
    global $configuracoesAplicacao;

    return isset($configuracoesAplicacao[$chave])
        ? (string) $configuracoesAplicacao[$chave]
        : $valorPadrao;
}

/**
 * Retorna uma configuração obrigatória e interrompe a execução se ela estiver vazia.
 */
function configuracaoObrigatoria(string $chave): string
{
    $valor = trim(configuracao($chave));

    if ($valor === '') {
        throw new RuntimeException('A configuração obrigatória ' . $chave . ' não foi informada.');
    }

    return $valor;
}

// Evita que erros internos sejam mostrados para visitantes.
error_reporting(E_ALL);
ini_set('display_errors', '0');

$fusoHorario = configuracao('APP_TIMEZONE', 'America/Fortaleza');

if (!in_array($fusoHorario, timezone_identifiers_list(), true)) {
    error_log('Fuso horário inválido no arquivo .env. O padrão America/Fortaleza será usado.');
    $fusoHorario = 'America/Fortaleza';
}

date_default_timezone_set($fusoHorario);

// O script de criação do administrador é executado no terminal e não usa sessão.
if (PHP_SAPI !== 'cli' && session_status() === PHP_SESSION_NONE) {
    $cookieSeguro = filter_var(
        configuracao('SESSION_SECURE_COOKIE', 'false'),
        FILTER_VALIDATE_BOOLEAN
    );

    session_name('mulheres_na_ciencia');
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => $cookieSeguro,
    ]);
    session_start();
}
