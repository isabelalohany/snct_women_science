<?php

/**
 * Valida e armazena a fotografia fora do diretório público.
 *
 * O caminho retornado é relativo a storage/uploads e pode ser gravado no banco.
 *
 * @param array<string, mixed> $arquivo
 */
function processarUploadImagemCientista(array $arquivo): ?string
{
    if (!isset($arquivo['error']) || !is_int($arquivo['error'])) {
        throw new InvalidArgumentException('O envio da fotografia não foi reconhecido.');
    }

    if ($arquivo['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($arquivo['error'] === UPLOAD_ERR_INI_SIZE || $arquivo['error'] === UPLOAD_ERR_FORM_SIZE) {
        throw new InvalidArgumentException('A fotografia deve ter no máximo 5 MB.');
    }

    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        throw new InvalidArgumentException('Não foi possível receber a fotografia. Tente enviá-la novamente.');
    }

    if (
        !isset($arquivo['tmp_name'], $arquivo['name'])
        || !is_string($arquivo['tmp_name'])
        || !is_string($arquivo['name'])
        || !is_uploaded_file($arquivo['tmp_name'])
    ) {
        throw new InvalidArgumentException('O arquivo enviado não é uma fotografia válida.');
    }

    $tamanho = filesize($arquivo['tmp_name']);

    if ($tamanho === false || $tamanho > 5 * 1024 * 1024) {
        throw new InvalidArgumentException('A fotografia deve ter no máximo 5 MB.');
    }

    $informacaoImagem = getimagesize($arquivo['tmp_name']);

    if ($informacaoImagem === false) {
        throw new InvalidArgumentException('Envie uma imagem JPEG, PNG ou WebP válida.');
    }

    $tiposPermitidos = [
        'image/jpeg' => [
            'extensoes' => ['jpg', 'jpeg'],
            'tipo' => IMAGETYPE_JPEG,
            'extensao_armazenada' => 'jpg',
        ],
        'image/png' => [
            'extensoes' => ['png'],
            'tipo' => IMAGETYPE_PNG,
            'extensao_armazenada' => 'png',
        ],
        'image/webp' => [
            'extensoes' => ['webp'],
            'tipo' => IMAGETYPE_WEBP,
            'extensao_armazenada' => 'webp',
        ],
    ];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    if ($finfo === false) {
        throw new RuntimeException('Não foi possível verificar o tipo da fotografia.');
    }

    $tipoMime = finfo_file($finfo, $arquivo['tmp_name']);
    finfo_close($finfo);

    if (!is_string($tipoMime) || !isset($tiposPermitidos[$tipoMime])) {
        throw new InvalidArgumentException('Envie uma imagem JPEG, PNG ou WebP.');
    }

    $extensaoOriginal = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    $tipoPermitido = $tiposPermitidos[$tipoMime];

    if (
        !in_array($extensaoOriginal, $tipoPermitido['extensoes'], true)
        || $informacaoImagem[2] !== $tipoPermitido['tipo']
        || $informacaoImagem['mime'] !== $tipoMime
    ) {
        throw new InvalidArgumentException('A extensão do arquivo não corresponde a uma imagem JPEG, PNG ou WebP.');
    }

    $diretorioUploads = dirname(__DIR__, 2) . '/storage/uploads/cientistas';

    if (!is_dir($diretorioUploads) && !mkdir($diretorioUploads, 0750, true) && !is_dir($diretorioUploads)) {
        throw new RuntimeException('Não foi possível preparar o armazenamento de fotografias.');
    }

    $nomeSeguro = bin2hex(random_bytes(16)) . '.' . $tipoPermitido['extensao_armazenada'];
    $caminhoAbsoluto = $diretorioUploads . '/' . $nomeSeguro;

    if (!move_uploaded_file($arquivo['tmp_name'], $caminhoAbsoluto)) {
        throw new RuntimeException('Não foi possível armazenar a fotografia enviada.');
    }

    return 'cientistas/' . $nomeSeguro;
}

/**
 * Resolve somente os caminhos internos gerados para fotografias de cientistas.
 */
function localizarImagemCientista(?string $caminhoRelativo): ?string
{
    if (
        $caminhoRelativo === null
        || !preg_match('#^cientistas/[a-f0-9]{32}\.(jpg|png|webp)$#D', $caminhoRelativo)
    ) {
        return null;
    }

    $diretorio = realpath(dirname(__DIR__, 2) . '/storage/uploads/cientistas');

    if ($diretorio === false) {
        return null;
    }

    $caminhoAbsoluto = realpath($diretorio . '/' . basename($caminhoRelativo));

    if (
        $caminhoAbsoluto === false
        || !is_file($caminhoAbsoluto)
        || !str_starts_with($caminhoAbsoluto, $diretorio . DIRECTORY_SEPARATOR)
    ) {
        return null;
    }

    return $caminhoAbsoluto;
}

/**
 * Remove apenas arquivos com nome interno válido; falhas são registradas no log.
 */
function removerImagemCientista(?string $caminhoRelativo): bool
{
    if ($caminhoRelativo === null || $caminhoRelativo === '') {
        return true;
    }

    $caminhoAbsoluto = localizarImagemCientista($caminhoRelativo);

    if ($caminhoAbsoluto === null) {
        $diretorio = realpath(dirname(__DIR__, 2) . '/storage/uploads/cientistas');
        $nomeValido = preg_match('#^cientistas/[a-f0-9]{32}\.(jpg|png|webp)$#D', $caminhoRelativo) === 1;

        if ($nomeValido && $diretorio !== false && !file_exists($diretorio . '/' . basename($caminhoRelativo))) {
            return true;
        }

        error_log('Não foi possível localizar uma fotografia de cientista para remoção.');
        return false;
    }

    if (!unlink($caminhoAbsoluto)) {
        error_log('Não foi possível remover uma fotografia de cientista.');
        return false;
    }

    return true;
}
