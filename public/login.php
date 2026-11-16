<?php

require_once dirname(__DIR__) . '/app/config/conexao.php';
require_once dirname(__DIR__) . '/app/funcoes/autenticacao.php';
require_once dirname(__DIR__) . '/app/funcoes/usuarios.php';

if (usuarioAtual() !== null) {
    redirecionar('index.php');
}

$tituloPagina = 'Entrar | Mulheres na Ciência';
$paginaAtiva = '';
$erros = [];
$email = '';
$mensagemFlash = obterMensagemFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tokenCsrf = $_POST['token_csrf'] ?? '';
    $emailRecebido = $_POST['email'] ?? '';
    $senhaRecebida = $_POST['senha'] ?? '';

    $email = is_string($emailRecebido) ? strtolower(trim($emailRecebido)) : '';
    $senha = is_string($senhaRecebida) ? $senhaRecebida : '';

    if (!is_string($tokenCsrf) || !tokenCsrfEhValido($tokenCsrf)) {
        $erros['geral'] = 'Não foi possível validar o formulário. Tente novamente.';
    } elseif ($email === '' || $senha === '') {
        $erros['geral'] = 'Informe seu e-mail e sua senha.';
    } else {
        try {
            $usuario = buscarUsuarioPorEmail(obterConexao(), $email);

            if ($usuario === null || !password_verify($senha, $usuario['senha_hash'])) {
                $erros['geral'] = 'E-mail ou senha inválidos.';
            } else {
                iniciarSessaoUsuario($usuario);
                adicionarMensagemFlash('sucesso', 'Login realizado com sucesso.');
                redirecionar('index.php');
            }
        } catch (Throwable $erro) {
            error_log('Erro durante o login: ' . $erro->getMessage());
            $erros['geral'] = 'Não foi possível entrar agora. Tente novamente mais tarde.';
        }
    }
}

require_once dirname(__DIR__) . '/app/componentes/cabecalho.php';
?>

<main id="conteudo-principal" class="pagina-formulario">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-xl-5">
                <section class="cartao-formulario" aria-labelledby="titulo-login">
                    <p class="sobretitulo text-primary">Acesso à plataforma</p>
                    <h1 class="titulo-secao h2" id="titulo-login">Entre na sua conta</h1>
                    <p class="texto-formulario">Use seu e-mail e sua senha para acessar as funcionalidades disponíveis.</p>

                    <?php if ($mensagemFlash !== null): ?>
                        <div class="alert alert-<?= escapar(classeMensagemFlash($mensagemFlash['tipo'])) ?>" role="alert">
                            <?= escapar($mensagemFlash['texto']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($erros['geral'])): ?>
                        <div class="alert alert-danger" role="alert"><?= escapar($erros['geral']) ?></div>
                    <?php endif; ?>

                    <form method="post" action="<?= escapar(urlAplicacao('login.php')) ?>" class="mt-4">
                        <input type="hidden" name="token_csrf" value="<?= escapar(gerarTokenCsrf()) ?>">

                        <div class="mb-3">
                            <label class="form-label" for="email">E-mail</label>
                            <input class="form-control" id="email" name="email" type="email" maxlength="190" autocomplete="email" required value="<?= escapar($email) ?>">
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="senha">Senha</label>
                            <input class="form-control" id="senha" name="senha" type="password" maxlength="72" autocomplete="current-password" required>
                        </div>

                        <button class="btn btn-primary w-100 py-2" type="submit">Entrar</button>
                    </form>

                    <p class="text-center texto-formulario mt-4 mb-0">Ainda não possui uma conta? <a href="<?= escapar(urlAplicacao('cadastro.php')) ?>">Crie sua conta</a>.</p>
                </section>
            </div>
        </div>
    </div>
</main>

<?php require_once dirname(__DIR__) . '/app/componentes/rodape.php'; ?>
