<?php

require_once dirname(__DIR__, 2) . '/app/config/conexao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/autenticacao.php';
require_once dirname(__DIR__, 2) . '/app/funcoes/usuarios.php';

exigirAdministrador();

$tituloPagina = 'Usuários cadastrados | Mulheres na Ciência';
$paginaAtiva = 'admin-usuarios';
$usuarios = null;

try {
    $usuarios = listarUsuariosAdministrativos(obterConexao());
} catch (Throwable $erro) {
    error_log('Erro ao consultar usuários na área administrativa: ' . $erro->getMessage());
}

require_once dirname(__DIR__, 2) . '/app/componentes/cabecalho.php';
?>

<main id="conteudo-principal" class="pagina-formulario">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <p class="sobretitulo text-primary">Área restrita</p>
                <h1 class="titulo-secao mb-0">Usuários cadastrados</h1>
            </div>
            <a class="btn btn-outline-primary" href="<?= escapar(urlAplicacao('admin/index.php')) ?>">Voltar ao painel</a>
        </div>

        <?php if ($usuarios === null): ?>
            <div class="alert alert-danger" role="alert">
                Não foi possível consultar os usuários agora. Tente novamente mais tarde.
            </div>
        <?php elseif ($usuarios === []): ?>
            <div class="alert alert-info" role="status">Ainda não há usuários cadastrados.</div>
        <?php else: ?>
            <div class="table-responsive cartao-proposito p-0">
                <table class="table table-striped table-hover align-middle mb-0">
                    <caption class="visually-hidden">Lista de contas cadastradas no portal</caption>
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Cadastrado em</th>
                            <th scope="col">Atualizado em</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= escapar($usuario['nome_completo']) ?></td>
                                <td><?= escapar($usuario['email']) ?></td>
                                <td><?= $usuario['tipo_usuario'] === 'administrador' ? 'Administrador' : 'Usuário' ?></td>
                                <td><?= escapar(date('d/m/Y H:i', strtotime($usuario['criado_em']))) ?></td>
                                <td><?= escapar(date('d/m/Y H:i', strtotime($usuario['atualizado_em']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once dirname(__DIR__, 2) . '/app/componentes/rodape.php'; ?>
