<?php

require_once __DIR__ . '/bootstrap.php';

/**
 * Cria uma única conexão mysqli por requisição e configura o uso de utf8mb4.
 * Mensagens detalhadas ficam no log do servidor, não na interface pública.
 */
function obterConexao(): mysqli
{
    static $conexao = null;

    if ($conexao instanceof mysqli) {
        return $conexao;
    }

    $porta = filter_var(configuracao('DB_PORT', '3306'), FILTER_VALIDATE_INT);

    if ($porta === false || $porta < 1 || $porta > 65535) {
        throw new RuntimeException('A porta do banco de dados é inválida.');
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $conexao = new mysqli(
            configuracaoObrigatoria('DB_HOST'),
            configuracaoObrigatoria('DB_USER'),
            configuracaoObrigatoria('DB_PASSWORD'),
            configuracaoObrigatoria('DB_NAME'),
            $porta
        );
        $conexao->set_charset('utf8mb4');
    } catch (mysqli_sql_exception $erro) {
        error_log('Falha na conexão com o banco de dados: ' . $erro->getMessage());
        throw new RuntimeException('Não foi possível conectar ao banco de dados.');
    }

    return $conexao;
}
