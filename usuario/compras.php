<?php
session_start();
include '../config/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header('Location: ../login.php');
  exit;
}



// Obtém os pedidos do usuário logado
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT p.*, u.nome 
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.usuario_id = ?
        ORDER BY p.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Minhas Compras - Fast Food</title>
  <link rel="stylesheet" href="../css/index.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/vendas-usuario.css">
  <link rel="shortcut icon" type="image/x-icon" href="../imgs/favicon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" data-purpose-"Layout StyleSheet" title="Web incrível" href="/css/app-wa-3b124ff...css?vsn-d">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-duotone-solid.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-thin.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-solid.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-regular.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-light.css">
</head>

<body>
  <?php 
  $paginaAtual = 'compras';
  include '../includes/header.php';
   ?>
  <div class="container">
    <h1 style="color:white; text-align:center; margin-top: 20px; margin-bottom:30px">Gerenciar Pedidos</h1>

    <!-- Exibe mensagem de sucesso ou erro -->
    <?php if (isset($msg)): ?>
      <p style="color: green; text-align: center; margin-bottom: 10px;"><?= $msg; ?></p>
    <?php elseif (isset($erro)): ?>
      <p style="color: red;"><?= $erro; ?></p>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
      <div class="lista-pedidos">
        <?php while ($pedido = $result->fetch_assoc()): ?>
          <div class="pedido-item">
            <div class="pedido-info">
              <h3 class="details">Pedido #<?= $pedido['id']; ?>
                <nav class="informs"><?= $pedido['nome'] .' '. date('d/m/Y H:i', strtotime($pedido['created_at'])); ?></nav>
              </h3>
              <p class="products"><strong>Produtos:</strong><?php
                                                            // Consulta para obter os nomes dos produtos do pedido
                                                            $sql_produtos = "SELECT pr.nome 
                                   FROM itens_pedido ip 
                                   JOIN produtos pr ON ip.produto_id = pr.id 
                                   WHERE ip.pedido_id = ?";
                                                            $stmt_produtos = $conn->prepare($sql_produtos);
                                                            $stmt_produtos->bind_param("i", $pedido['id']);
                                                            $stmt_produtos->execute();
                                                            $result_produtos = $stmt_produtos->get_result();

                                                            // Exibe os nomes dos produtos em uma linha separados por vírgula
                                                            $produtos = [];
                                                            while ($produto = $result_produtos->fetch_assoc()) {
                                                              $produtos[] = $produto['nome'];
                                                            }

                                                            // Exibe a lista de produtos separados por vírgula
                                                            echo implode(', ', $produtos);
                                                            ?></p>
              <p><strong>Total:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.'); ?></p>
              <p><strong>Status:</strong> <?= ucfirst($pedido['status']); ?></p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p>Você ainda não fez nenhuma compra.</p>
    <?php endif; ?>
  </div>

  <?php include '../includes/footer.php'; ?>
</body>

</html>