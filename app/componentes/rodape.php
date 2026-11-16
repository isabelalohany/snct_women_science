<?php

/**
 * Rodapé compartilhado e carregamento do JavaScript local do Bootstrap.
 */

$urlBase = rtrim(configuracao('APP_URL', ''), '/');
$urlBaseSeguro = htmlspecialchars($urlBase, ENT_QUOTES, 'UTF-8');
?>
    <footer class="rodape-site">
        <div class="container py-4 d-md-flex justify-content-between align-items-center text-center text-md-start">
            <p class="mb-2 mb-md-0">&copy; <?= date('Y') ?> Mulheres na Ciência — projeto educativo.</p>
            <p class="mb-0 texto-rodape-secundario">Conhecer, reconhecer e inspirar pela ciência.</p>
        </div>
    </footer>

    <script src="<?= $urlBaseSeguro ?>/assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
