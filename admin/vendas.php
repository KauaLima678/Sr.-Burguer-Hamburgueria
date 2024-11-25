<?php
session_start();
include '../config/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header('Location: ../login.php');
  exit;
}

// Atualiza o status do pedido se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id']) && isset($_POST['status'])) {
  $pedido_id = $_POST['pedido_id'];
  $status = $_POST['status'];

  // Atualiza o status do pedido
  $sql_update = "UPDATE pedidos SET status = ? WHERE id = ?";
  $stmt_update = $conn->prepare($sql_update);
  $stmt_update->bind_param("si", $status, $pedido_id);

  if ($stmt_update->execute()) {
    $msg = "Status do pedido #{$pedido_id} atualizado com sucesso!";
  } else {
    $erro = "Erro ao atualizar o status do pedido.";
  }
}

// Obtém todos os pedidos
$sql = "SELECT p.id, p.usuario_id, p.total, p.status, p.created_at, u.nome
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="../css/header.css">
  <title>Pedidos - Fast Food</title>
  <link rel="stylesheet" href="../css/vendas.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/footer.css">
  <link rel="stylesheet" href="../css/index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="shortcut icon" type="image/x-icon" href="../imgs/favicon.ico">
  <style>
    /* Estilo para exibir os produtos em uma linha */
    .produtos-lista {
      display: inline-block;
      margin-top: 5px;
    }
    .produtos-lista span {
      margin-right: 10px;
    }
  </style>
</head>
<body>
  <?php 
  $paginaAtual = 'vendas';
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
              <h3 class="details">Pedido #<?= $pedido['id']; ?> <nav><?= $pedido['nome']; ?> <?= date('d/m/Y H:i', strtotime($pedido['created_at'])); ?> </nav></h3>
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
            <div class="pedido-acoes">
              <!-- Formulário para atualizar o status do pedido -->
              <form action="vendas.php" method="POST">
                <input type="hidden" name="pedido_id" value="<?= $pedido['id']; ?>">
                <label for="status">Alterar Status:</label>
                <select name="status" required>
                  <option value="pendente" <?= $pedido['status'] == 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                  <option value="em andamento" <?= $pedido['status'] == 'em andamento' ? 'selected' : ''; ?>>Em andamento</option>
                  <option value="saiu para entrega" <?= $pedido['status'] == 'saiu para entrega' ? 'selected' : ''; ?>>Saiu para entrega</option>
                  <option value="entregue" <?= $pedido['status'] == 'entregue' ? 'selected' : ''; ?>>Entregue</option>
                  <option value="cancelado" <?= $pedido['status'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                </select>
                <br>
                <div class="alt">
                  <input type="submit" value="Atualizar Status">
                </div>
              </form>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p>Nenhum pedido foi encontrado.</p>
    <?php endif; ?>
  </div>

  <?php include '../includes/footer.php'; ?>
</body>
</html>
