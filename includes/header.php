<?php
// Verifica se a sessão já foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="start">
    <div class="img">
        <img src="../imgs/Logo.png" alt="">
    </div>
    <nav>
        <a class="link-menu <?= ($paginaAtual == 'inicio') ? 'ativo' : ''; ?>" href="/index.php"><i class="fa-solid fa-house"></i> <span>Home</span></a>
        <a class="link-menu <?= ($paginaAtual == 'sobre') ? 'ativo' : ''; ?>" href="../Sobre.php"><i class="fa-sharp fa-solid fa-circle-exclamation"></i><span>Sobre</span></a>

        <?php if (isset($_SESSION['role'])) : ?>
            <?php if ($_SESSION['role'] === 'admin') : ?>
                <a class="link-menu <?= ($paginaAtual == 'vendas') ? 'ativo' : ''; ?>" href="/admin/vendas.php">Gerenciar Pedidos</a>
                <a class="link-menu <?= ($paginaAtual == 'adc-prod') ? 'ativo' : ''; ?>" href="/admin/adicionar_produto.php">Adicionar Produto</a>
            <?php elseif ($_SESSION['role'] === 'cliente') : ?>
                <a class="link-menu <?= ($paginaAtual == 'compras') ? 'ativo' : ''; ?>" href="/usuario/compras.php"><i class="fas fa-cart-circle-check"><Span class="nav-inx">Compras</Span></i></a>
                <a class="link-menu <?= ($paginaAtual == 'carrinho') ? 'ativo' : ''; ?>" href="/usuario/carrinho.php"><i class="fa-solid fa-cart-shopping"></i><span>Carrinho</span></a>
            <?php endif; ?>
            <a href="/logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
        <?php else: ?>
        <?php endif; ?>
        
        <a class="link-menu <?= ($paginaAtual == 'perfil') ? 'ativo' : ''; ?>" href="/perfil.php"><i class="fa-solid fa-user"></i><span>Perfil</span></a>
    </nav>
</header>
