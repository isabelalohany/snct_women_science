<?php

require_once dirname(__DIR__, 2) . '/app/config/conexao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/autenticacao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/cientistas.php';

exigirAdministrador();

$idCientista = null;

if (isset($_GET['id'])) {
    $idValidado = is_string($_GET['id'])
        ? filter_var($_GET['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
        : false;

    if ($idValidado === false) {
        http_response_code(404);
        exit;
    }

    $idCientista = (int) $idValidado;
}

$cientista = null;
$erroCarregamento = false;

if ($idCientista !== null) {
    try {
        $cientista = buscarCientistaAdministrativamente(obterConexao(), $idCientista);
    } catch (Throwable $erro) {
        error_log('Erro ao carregar perfil para edição: ' . $erro->getMessage());
        $erroCarregamento = true;
    }

    if ($cientista === null && !$erroCarregamento) {
        http_response_code(404);
        exit;
    }
}

$dados = [
    'nome_completo' => $cientista['nome_completo'] ?? '',
    'nacionalidade' => $cientista['nacionalidade'] ?? '',
    'data_nascimento' => $cientista['data_nascimento'] ?? '',
    'area_atuacao' => $cientista['area_atuacao'] ?? '',
    'biografia' => $cientista['biografia'] ?? '',
    'contribuicoes' => $cientista['contribuicoes'] ?? '',
    'informacoes_adicionais' => $cientista['informacoes_adicionais'] ?? '',
    'fontes_referencias' => $cientista['fontes_referencias'] ?? '',
    'status' => '',
];
$erros = [];
$estadoFormulario = $_SESSION['formulario_cientista'] ?? null;
unset($_SESSION['formulario_cientista']);

if (
    is_array($estadoFormulario)
    && ($estadoFormulario['id_cientista'] ?? null) === $idCientista
    && is_array($estadoFormulario['dados'] ?? null)
) {
    foreach ($dados as $campo => $valor) {
        if (isset($estadoFormulario['dados'][$campo]) && is_string($estadoFormulario['dados'][$campo])) {
            $dados[$campo] = $estadoFormulario['dados'][$campo];
        }
    }

    if (is_array($estadoFormulario['erros'] ?? null)) {
        $erros = $estadoFormulario['erros'];
    }
}

$mensagemFlash = obterMensagemFlash();
$tituloPagina = $idCientista === null
    ? 'Cadastrar cientista | Mulheres na Ciência'
    : 'Editar cientista | Mulheres na Ciência';
$paginaAtiva = 'admin-cientistas';
$statusRotulos = [
    'pendente' => 'Pendente',
    'publicada' => 'Publicada',
    'rejeitada' => 'Rejeitada',
    'oculta' => 'Oculta',
];

require_once dirname(__DIR__, 2) . '/app/componentes/cabecalho.php';
?>

<main id="conteudo-principal" class="pagina-formulario">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <p class="sobretitulo text-primary">Área restrita</p>
                <h1 class="titulo-secao mb-0">
                    <?= $idCientista === null ? 'Cadastrar cientista' : 'Editar perfil da cientista' ?>
                </h1>
            </div>
            <a class="btn btn-outline-primary" href="<?= escapar(urlAplicacao('admin/cientistas.php')) ?>">Voltar aos perfis</a>
        </div>

        <?php if ($mensagemFlash !== null): ?>
            <div class="alert alert-<?= escapar(classeMensagemFlash($mensagemFlash['tipo'])) ?>" role="alert">
                <?= escapar($mensagemFlash['texto']) ?>
            </div>
        <?php endif; ?>

        <?php if ($erroCarregamento): ?>
            <div class="alert alert-danger" role="alert">
                Não foi possível carregar o perfil para edição. Tente novamente mais tarde.
            </div>
        <?php else: ?>
            <?php if ($erros !== []): ?>
                <div class="alert alert-danger" role="alert">
                    Confira os dados informados e corrija os campos destacados.
                    <?php if (isset($erros['geral'])): ?>
                        <br><?= escapar($erros['geral']) ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <section class="cartao-formulario" aria-label="Formulário de dados da cientista">
                <?php if ($cientista !== null): ?>
                    <p class="texto-formulario">
                        Situação atual:
                        <strong><?= escapar($statusRotulos[$cientista['status']] ?? $cientista['status']) ?></strong>.
                        A edição não altera a situação do perfil.
                    </p>
                <?php endif; ?>

                <form method="post" action="<?= escapar(urlAplicacao('admin/cientista-salvar.php')) ?>"
                    enctype="multipart/form-data">
                    <input type="hidden" name="token_csrf" value="<?= escapar(gerarTokenCsrf()) ?>">
                    <?php if ($idCientista !== null): ?>
                        <input type="hidden" name="id_cientista" value="<?= $idCientista ?>">
                    <?php endif; ?>

                    <?php if ($idCientista === null): ?>
                        <div class="mb-3">
                            <label class="form-label" for="status">Situação inicial <span class="campo-obrigatorio">*</span></label>
                            <select class="form-select <?= isset($erros['status']) ? 'is-invalid' : '' ?>"
                                id="status" name="status" required>
                                <option value="">Selecione</option>
                                <option value="publicada" <?= $dados['status'] === 'publicada' ? 'selected' : '' ?>>Publicada</option>
                                <option value="oculta" <?= $dados['status'] === 'oculta' ? 'selected' : '' ?>>Oculta</option>
                            </select>
                            <?php if (isset($erros['status'])): ?>
                                <div class="invalid-feedback"><?= escapar($erros['status']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label" for="nome_completo">Nome completo <span class="campo-obrigatorio">*</span></label>
                        <input class="form-control <?= isset($erros['nome_completo']) ? 'is-invalid' : '' ?>"
                            id="nome_completo" name="nome_completo" type="text" maxlength="180" required
                            value="<?= escapar($dados['nome_completo']) ?>">
                        <?php if (isset($erros['nome_completo'])): ?>
                            <div class="invalid-feedback"><?= escapar($erros['nome_completo']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="nacionalidade">Nacionalidade</label>
                            <input class="form-control <?= isset($erros['nacionalidade']) ? 'is-invalid' : '' ?>"
                                id="nacionalidade" name="nacionalidade" type="text" maxlength="100"
                                value="<?= escapar($dados['nacionalidade']) ?>">
                            <?php if (isset($erros['nacionalidade'])): ?>
                                <div class="invalid-feedback"><?= escapar($erros['nacionalidade']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="data_nascimento">Data de nascimento</label>
                            <input class="form-control <?= isset($erros['data_nascimento']) ? 'is-invalid' : '' ?>"
                                id="data_nascimento" name="data_nascimento" type="date"
                                value="<?= escapar($dados['data_nascimento']) ?>">
                            <?php if (isset($erros['data_nascimento'])): ?>
                                <div class="invalid-feedback"><?= escapar($erros['data_nascimento']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="area_atuacao">Área de atuação científica <span class="campo-obrigatorio">*</span></label>
                        <input class="form-control <?= isset($erros['area_atuacao']) ? 'is-invalid' : '' ?>"
                            id="area_atuacao" name="area_atuacao" type="text" maxlength="255" required
                            value="<?= escapar($dados['area_atuacao']) ?>">
                        <?php if (isset($erros['area_atuacao'])): ?>
                            <div class="invalid-feedback"><?= escapar($erros['area_atuacao']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="biografia">Biografia e trajetória profissional <span class="campo-obrigatorio">*</span></label>
                        <textarea class="form-control <?= isset($erros['biografia']) ? 'is-invalid' : '' ?>"
                            id="biografia" name="biografia" rows="5" maxlength="10000" required><?= escapar($dados['biografia']) ?></textarea>
                        <?php if (isset($erros['biografia'])): ?>
                            <div class="invalid-feedback"><?= escapar($erros['biografia']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="contribuicoes">Descobertas e principais contribuições <span class="campo-obrigatorio">*</span></label>
                        <textarea class="form-control <?= isset($erros['contribuicoes']) ? 'is-invalid' : '' ?>"
                            id="contribuicoes" name="contribuicoes" rows="5" maxlength="10000" required><?= escapar($dados['contribuicoes']) ?></textarea>
                        <?php if (isset($erros['contribuicoes'])): ?>
                            <div class="invalid-feedback"><?= escapar($erros['contribuicoes']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="informacoes_adicionais">Informações adicionais</label>
                        <textarea class="form-control <?= isset($erros['informacoes_adicionais']) ? 'is-invalid' : '' ?>"
                            id="informacoes_adicionais" name="informacoes_adicionais" rows="3" maxlength="5000"><?= escapar($dados['informacoes_adicionais']) ?></textarea>
                        <?php if (isset($erros['informacoes_adicionais'])): ?>
                            <div class="invalid-feedback"><?= escapar($erros['informacoes_adicionais']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="fontes_referencias">Fontes e referências <span class="campo-obrigatorio">*</span></label>
                        <textarea class="form-control <?= isset($erros['fontes_referencias']) ? 'is-invalid' : '' ?>"
                            id="fontes_referencias" name="fontes_referencias" rows="4" maxlength="5000" required><?= escapar($dados['fontes_referencias']) ?></textarea>
                        <?php if (isset($erros['fontes_referencias'])): ?>
                            <div class="invalid-feedback"><?= escapar($erros['fontes_referencias']) ?></div>
                        <?php endif; ?>
                    </div>

                    <?php if ($cientista !== null && $cientista['imagem_path'] !== null): ?>
                        <div class="mb-3">
                            <p class="form-label">Fotografia atual</p>
                            <img class="imagem-admin-cientista img-thumbnail mb-2"
                                src="<?= escapar(urlAplicacao('imagem.php?id=' . $idCientista)) ?>"
                                alt="Fotografia de <?= escapar($cientista['nome_completo']) ?>">
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label class="form-label" for="fotografia">
                            <?= $cientista !== null && $cientista['imagem_path'] !== null ? 'Substituir fotografia' : 'Fotografia' ?>
                        </label>
                        <input class="form-control <?= isset($erros['fotografia']) ? 'is-invalid' : '' ?>"
                            id="fotografia" name="fotografia" type="file"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        <div class="form-text">Opcional. Formatos JPEG, PNG ou WebP, até 5 MB.</div>
                        <?php if (isset($erros['fotografia'])): ?>
                            <div class="invalid-feedback"><?= escapar($erros['fotografia']) ?></div>
                        <?php endif; ?>
                    </div>

                    <button class="btn btn-primary px-4" type="submit">Salvar perfil</button>
                </form>

                <?php if ($idCientista !== null): ?>
                    <hr class="my-4">
                    <form method="post" action="<?= escapar(urlAplicacao('admin/cientista-excluir.php')) ?>"
                        onsubmit="return confirm('Excluir permanentemente este perfil e sua fotografia?');">
                        <input type="hidden" name="token_csrf" value="<?= escapar(gerarTokenCsrf()) ?>">
                        <input type="hidden" name="id_cientista" value="<?= $idCientista ?>">
                        <button class="btn btn-outline-danger" type="submit">Excluir permanentemente</button>
                    </form>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php require_once dirname(__DIR__, 2) . '/app/componentes/rodape.php'; ?>
