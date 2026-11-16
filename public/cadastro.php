<?php

require_once dirname(__DIR__) . '/app/config/conexao.php';
require_once dirname(__DIR__) . '/app/funcoes/autenticacao.php';
require_once dirname(__DIR__) . '/app/funcoes/usuarios.php';
require_once dirname(__DIR__) . '/app/funcoes/validacao.php';

$tituloPagina = 'Criar conta | Mulheres na Ciência';
$paginaAtiva = '';
$erros = [];
$nomeCompleto = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tokenCsrf = $_POST['token_csrf'] ?? '';
    $nomeRecebido = $_POST['nome_completo'] ?? '';
    $emailRecebido = $_POST['email'] ?? '';
    $senhaRecebida = $_POST['senha'] ?? '';

    $nomeCompleto = is_string($nomeRecebido) ? trim($nomeRecebido) : '';
    $email = is_string($emailRecebido) ? strtolower(trim($emailRecebido)) : '';
    $senha = is_string($senhaRecebida) ? $senhaRecebida : '';

    if (!is_string($tokenCsrf) || !tokenCsrfEhValido($tokenCsrf)) {
        $erros['geral'] = 'Não foi possível validar o formulário. Tente novamente.';
    } else {
        $erros = validarCadastroUsuario($nomeCompleto, $email, $senha);
    }

    if (empty($erros)) {
        try {
            $conexao = obterConexao();

            if (buscarUsuarioPorEmail($conexao, $email) !== null) {
                $erros['email'] = 'Já existe uma conta cadastrada com este e-mail.';
            } else {
                criarUsuario($conexao, $nomeCompleto, $email, $senha);
                adicionarMensagemFlash('sucesso', 'Conta criada com sucesso. Agora você já pode entrar.');
                redirecionar('login.php');
            }
        } catch (Throwable $erro) {
            error_log('Erro no cadastro público de usuário: ' . $erro->getMessage());
            $erros['geral'] = 'Não foi possível concluir o cadastro agora. Tente novamente mais tarde.';
        }
    }
}

require_once dirname(__DIR__) . '/app/componentes/cabecalho.php';
?>

<main id="conteudo-principal" class="pagina-formulario">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-6">
                <section class="cartao-formulario" aria-labelledby="titulo-cadastro">
                    <p class="sobretitulo text-primary">Participe do portal</p>
                    <h1 class="titulo-secao h2" id="titulo-cadastro">Crie sua conta</h1>
                    <p class="texto-formulario">Com sua conta, você poderá indicar mulheres cientistas para análise administrativa.</p>

                    <?php if (isset($erros['geral'])): ?>
                        <div class="alert alert-danger" role="alert"><?= escapar($erros['geral']) ?></div>
                    <?php endif; ?>

                    <form method="post" action="<?= escapar(urlAplicacao('cadastro.php')) ?>" class="mt-4">
                        <input type="hidden" name="token_csrf" value="<?= escapar(gerarTokenCsrf()) ?>">

                        <div class="mb-3">
                            <label class="form-label" for="nome_completo">Nome completo <span class="campo-obrigatorio">*</span></label>
                            <input class="form-control <?= isset($erros['nome_completo']) ? 'is-invalid' : '' ?>" id="nome_completo" name="nome_completo" type="text" maxlength="150" autocomplete="name" required value="<?= escapar($nomeCompleto) ?>">
                            <?php if (isset($erros['nome_completo'])): ?>
                                <div class="invalid-feedback"><?= escapar($erros['nome_completo']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">E-mail <span class="campo-obrigatorio">*</span></label>
                            <input class="form-control <?= isset($erros['email']) ? 'is-invalid' : '' ?>" id="email" name="email" type="email" maxlength="190" autocomplete="email" required value="<?= escapar($email) ?>">
                            <?php if (isset($erros['email'])): ?>
                                <div class="invalid-feedback"><?= escapar($erros['email']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="senha">Senha <span class="campo-obrigatorio">*</span></label>
                            <input class="form-control <?= isset($erros['senha']) ? 'is-invalid' : '' ?>" id="senha" name="senha" type="password" minlength="6" maxlength="72" autocomplete="new-password" required>
                            <div class="form-text">Use entre 6 e 72 caracteres.</div>
                            <?php if (isset($erros['senha'])): ?>
                                <div class="invalid-feedback"><?= escapar($erros['senha']) ?></div>
                            <?php endif; ?>
                        </div>

                        <button class="btn btn-primary w-100 py-2" type="submit">Criar conta</button>
                    </form>

                    <p class="text-center texto-formulario mt-4 mb-0">Já possui uma conta? <a href="<?= escapar(urlAplicacao('login.php')) ?>">Entre aqui</a>.</p>
                </section>
            </div>
        </div>
    </div>
</main>

<?php require_once dirname(__DIR__) . '/app/componentes/rodape.php'; ?>
