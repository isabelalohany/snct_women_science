<?php

require_once dirname(__DIR__, 2) . '/app/config/conexao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/autenticacao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/cientistas.php';

exigirAdministrador();

$tituloPagina = 'Perfis de cientistas | Mulheres na Ciência';
$paginaAtiva = 'admin-cientistas';
$cientistas = null;
$situacoes = [
    'pendente' => 'Pendentes',
    'publicada' => 'Publicadas',
    'rejeitada' => 'Rejeitadas',
    'oculta' => 'Ocultas',
];

try {
    $cientistas = listarCientistasAdministrativas(obterConexao());
} catch (Throwable $erro) {
    error_log('Erro ao consultar cientistas na área administrativa: ' . $erro->getMessage());
}

$cientistasPorStatus = [];

if ($cientistas !== null) {
    foreach ($situacoes as $status => $rotulo) {
        $cientistasPorStatus[$status] = array_values(array_filter(
            $cientistas,
            static fn (array $cientista): bool => $cientista['status'] === $status
        ));
    }
}

require_once dirname(__DIR__, 2) . '/app/componentes/cabecalho.php';
?>

<main id="conteudo-principal" class="pagina-formulario">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <p class="sobretitulo text-primary">Área restrita</p>
                <h1 class="titulo-secao mb-0">Perfis de cientistas</h1>
            </div>
            <a class="btn btn-outline-primary" href="<?= escapar(urlAplicacao('admin/index.php')) ?>">Voltar ao painel</a>
        </div>

        <?php if ($cientistas === null): ?>
            <div class="alert alert-danger" role="alert">
                Não foi possível consultar os perfis agora. Tente novamente mais tarde.
            </div>
        <?php elseif ($cientistas === []): ?>
            <div class="alert alert-info" role="status">Ainda não há perfis cadastrados.</div>
        <?php else: ?>
            <?php foreach ($situacoes as $status => $rotulo): ?>
                <section class="mb-5" aria-labelledby="titulo-<?= escapar($status) ?>">
                    <h2 class="h4 titulo-secao mb-3" id="titulo-<?= escapar($status) ?>">
                        <?= escapar($rotulo) ?>
                        <span class="badge text-bg-secondary"><?= count($cientistasPorStatus[$status]) ?></span>
                    </h2>

                    <?php if ($cientistasPorStatus[$status] === []): ?>
                        <p class="texto-formulario">Nenhum perfil nesta situação.</p>
                    <?php else: ?>
                        <div class="table-responsive cartao-proposito p-0">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <caption class="visually-hidden">Perfis <?= escapar($rotulo) ?>, ordenados pela última atualização</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Área de atuação</th>
                                        <th scope="col">Indicado por</th>
                                        <th scope="col">Cadastrado em</th>
                                        <th scope="col">Atualizado em</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cientistasPorStatus[$status] as $cientista): ?>
                                        <tr>
                                            <td><?= escapar($cientista['nome_completo']) ?></td>
                                            <td><?= escapar($cientista['area_atuacao']) ?></td>
                                            <td><?= $cientista['indicador_nome'] === null ? 'Cadastro administrativo' : escapar($cientista['indicador_nome']) ?></td>
                                            <td><?= escapar(date('d/m/Y H:i', strtotime($cientista['criado_em']))) ?></td>
                                            <td><?= escapar(date('d/m/Y H:i', strtotime($cientista['atualizado_em']))) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php require_once dirname(__DIR__, 2) . '/app/componentes/rodape.php'; ?>
