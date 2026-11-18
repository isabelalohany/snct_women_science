<?php

require_once dirname(__DIR__, 2) . '/app/config/conexao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/autenticacao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/cientistas.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/usuarios.php';

exigirAdministrador();

$tituloPagina = 'Painel administrativo | Mulheres na Ciência';
$paginaAtiva = 'admin-index';
$quantidadesUsuarios = null;
$quantidadesCientistas = null;
$erroConsulta = false;

try {
    $conexao = obterConexao();
    $quantidadesUsuarios = contarUsuariosPorTipo($conexao);
    $quantidadesCientistas = contarCientistasPorStatus($conexao);
} catch (Throwable $erro) {
    error_log('Erro ao carregar o painel administrativo: ' . $erro->getMessage());
    $erroConsulta = true;
}

require_once dirname(__DIR__, 2) . '/app/componentes/cabecalho.php';
?>

<main id="conteudo-principal" class="pagina-formulario">
    <div class="container">
        <div class="mb-4">
            <p class="sobretitulo text-primary">Área restrita</p>
            <h1 class="titulo-secao">Painel administrativo</h1>
            <p class="texto-secao">Consulte os quantitativos e acesse as listagens do portal.</p>
        </div>

        <?php if ($erroConsulta): ?>
            <div class="alert alert-danger" role="alert">
                Não foi possível carregar os quantitativos agora. Tente novamente mais tarde.
            </div>
        <?php else: ?>
            <section class="mb-5" aria-labelledby="titulo-usuarios">
                <h2 class="h4 titulo-secao mb-3" id="titulo-usuarios">Usuários</h2>
                <div class="row g-3">
                    <div class="col-sm-6 col-lg-4">
                        <article class="cartao-proposito">
                            <p class="texto-formulario mb-1">Usuários comuns</p>
                            <p class="display-6 fw-bold mb-0"><?= $quantidadesUsuarios['usuario'] ?></p>
                        </article>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <article class="cartao-proposito">
                            <p class="texto-formulario mb-1">Administradores</p>
                            <p class="display-6 fw-bold mb-0"><?= $quantidadesUsuarios['administrador'] ?></p>
                        </article>
                    </div>
                </div>
            </section>

            <section class="mb-5" aria-labelledby="titulo-cientistas">
                <h2 class="h4 titulo-secao mb-3" id="titulo-cientistas">Perfis de cientistas</h2>
                <div class="row g-3">
                    <?php foreach ([
                        'pendente' => 'Pendentes',
                        'publicada' => 'Publicadas',
                        'rejeitada' => 'Rejeitadas',
                        'oculta' => 'Ocultas',
                    ] as $status => $rotulo): ?>
                        <div class="col-sm-6 col-lg-3">
                            <article class="cartao-proposito">
                                <p class="texto-formulario mb-1"><?= escapar($rotulo) ?></p>
                                <p class="display-6 fw-bold mb-0"><?= $quantidadesCientistas[$status] ?></p>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <section aria-labelledby="titulo-consultas">
            <h2 class="h4 titulo-secao mb-3" id="titulo-consultas">Consultas</h2>
            <div class="d-flex flex-column flex-sm-row gap-3">
                <a class="btn btn-primary px-4" href="<?= escapar(urlAplicacao('admin/usuarios.php')) ?>">
                    Consultar usuários
                </a>
                <a class="btn btn-outline-primary px-4" href="<?= escapar(urlAplicacao('admin/cientistas.php')) ?>">
                    Consultar cientistas
                </a>
            </div>
        </section>
    </div>
</main>

<?php require_once dirname(__DIR__, 2) . '/app/componentes/rodape.php'; ?>
