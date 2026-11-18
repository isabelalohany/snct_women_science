<?php

require_once dirname(__DIR__) . '/app/config/conexao.php';
require_once dirname(__DIR__) . '/app/funcoes/autenticacao.php';
require_once dirname(__DIR__) . '/app/funcoes/cientistas.php';
require_once dirname(__DIR__) . '/app/funcoes/upload.php';
require_once dirname(__DIR__) . '/app/funcoes/validacao.php';

exigirLogin();

$tituloPagina = 'Indicar uma cientista | Mulheres na Ciência';
$paginaAtiva = '';
$usuario = usuarioAtual();
$mensagemFlash = obterMensagemFlash();
$erros = [];
$dados = [
    'nome_completo' => '',
    'nacionalidade' => '',
    'data_nascimento' => '',
    'area_atuacao' => '',
    'biografia' => '',
    'contribuicoes' => '',
    'informacoes_adicionais' => '',
    'fontes_referencias' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($dados as $campo => $valor) {
        $recebido = $_POST[$campo] ?? '';
        $dados[$campo] = is_string($recebido) ? trim($recebido) : '';
    }

    $tokenCsrf = $_POST['token_csrf'] ?? '';

    if (!is_string($tokenCsrf) || !tokenCsrfEhValido($tokenCsrf)) {
        $erros['geral'] = 'Não foi possível validar o formulário. Tente novamente.';
    } else {
        $erros = validarIndicacaoCientista($dados);
    }

    if (empty($erros)) {
        $imagemPath = null;

        try {
            $arquivoImagem = $_FILES['fotografia'] ?? [];
            $imagemPath = processarUploadImagemCientista($arquivoImagem);

            try {
                criarIndicacaoCientista(obterConexao(), $dados, (int) $usuario['id_usuario'], $imagemPath);
            } catch (Throwable $erro) {
                if ($imagemPath !== null) {
                    $caminhoImagem = dirname(__DIR__) . '/storage/uploads/' . $imagemPath;

                    if (is_file($caminhoImagem) && !unlink($caminhoImagem)) {
                        error_log('Não foi possível remover a fotografia após falha ao salvar a indicação.');
                    }
                }

                throw $erro;
            }

            adicionarMensagemFlash('sucesso', 'Indicação enviada para análise. Obrigada por contribuir com o portal.');
            redirecionar('indicar-cientista.php');
        } catch (InvalidArgumentException $erro) {
            $erros['fotografia'] = $erro->getMessage();
        } catch (Throwable $erro) {
            error_log('Erro ao salvar indicação de cientista: ' . $erro->getMessage());
            $erros['geral'] = 'Não foi possível enviar a indicação agora. Tente novamente mais tarde.';
        }
    }
}

require_once dirname(__DIR__) . '/app/componentes/cabecalho.php';
?>

<main id="conteudo-principal" class="pagina-formulario">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <section class="cartao-formulario" aria-labelledby="titulo-indicacao">
                    <p class="sobretitulo text-primary">Colabore com o portal</p>
                    <h1 class="titulo-secao h2" id="titulo-indicacao">Indique uma mulher cientista</h1>
                    <p class="texto-formulario">
                        As informações serão analisadas pela equipe antes de aparecerem no catálogo.
                        Preencha os campos obrigatórios e inclua fontes para apoiar a indicação.
                    </p>

                    <?php if ($mensagemFlash !== null): ?>
                        <div class="alert alert-<?= escapar(classeMensagemFlash($mensagemFlash['tipo'])) ?>" role="alert">
                            <?= escapar($mensagemFlash['texto']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($erros['geral'])): ?>
                        <div class="alert alert-danger" role="alert"><?= escapar($erros['geral']) ?></div>
                    <?php endif; ?>

                    <form method="post" action="<?= escapar(urlAplicacao('indicar-cientista.php')) ?>"
                        enctype="multipart/form-data" class="mt-4">
                        <input type="hidden" name="token_csrf" value="<?= escapar(gerarTokenCsrf()) ?>">

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
                            <div class="form-text">Informe links ou referências que ajudem a verificar os dados.</div>
                            <?php if (isset($erros['fontes_referencias'])): ?>
                                <div class="invalid-feedback"><?= escapar($erros['fontes_referencias']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="fotografia">Fotografia</label>
                            <input class="form-control <?= isset($erros['fotografia']) ? 'is-invalid' : '' ?>"
                                id="fotografia" name="fotografia" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                            <div class="form-text">Opcional. Formatos JPEG, PNG ou WebP, até 5 MB.</div>
                            <?php if (isset($erros['fotografia'])): ?>
                                <div class="invalid-feedback"><?= escapar($erros['fotografia']) ?></div>
                            <?php endif; ?>
                        </div>

                        <button class="btn btn-primary w-100 py-2" type="submit">Enviar indicação para análise</button>
                    </form>

                    <p class="texto-formulario small mt-3 mb-0">
                        Enviado por <?= escapar($usuario['nome_completo']) ?>. Depois do envio, os dados não poderão ser editados por esta conta.
                    </p>
                </section>
            </div>
        </div>
    </div>
</main>

<?php require_once dirname(__DIR__) . '/app/componentes/rodape.php'; ?>
