<?php
session_start();
include '../config/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header('Location: ../login.php');
  exit;
}

// Obtém os itens do carrinho para o usuário logado
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT c.quantidade, p.id as produto_id, p.nome, p.preco, p.imagem
        FROM carrinho c
        JOIN produtos p ON c.produto_id = p.id
        WHERE c.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
$items = []; // Array para armazenar os itens do carrinho

// Armazena os itens do carrinho no array
while ($item = $result->fetch_assoc()) {
  $items[] = $item;
  $total += $item['preco'] * $item['quantidade'];
}

// Quando o formulário for enviado, processa o pedido e finaliza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $metodo_pagamento = $_POST['metodo_pagamento'];

  // Cria um novo pedido
  $sql_pedido = "INSERT INTO pedidos (usuario_id, total, status) VALUES (?, ?, 'pendente')";
  $stmt_pedido = $conn->prepare($sql_pedido);
  $stmt_pedido->bind_param("id", $usuario_id, $total);

  if ($stmt_pedido->execute()) {
    $pedido_id = $stmt_pedido->insert_id;

    // Insere os itens do pedido usando o array de itens do carrinho
    foreach ($items as $item) {
      $sql_itens = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco)
                    VALUES (?, ?, ?, ?)";
      $stmt_itens = $conn->prepare($sql_itens);
      $stmt_itens->bind_param("iiid", $pedido_id, $item['produto_id'], $item['quantidade'], $item['preco']);
      $stmt_itens->execute();
    }

    // Limpa o carrinho do usuário
    $sql_limpar = "DELETE FROM carrinho WHERE usuario_id = ?";
    $stmt_limpar = $conn->prepare($sql_limpar);
    $stmt_limpar->bind_param("i", $usuario_id);
    $stmt_limpar->execute();

    // Redireciona para a página de compras após o pedido ser finalizado
    header('Location: compras.php');
    exit;
  } else {
    $erro = "Erro ao finalizar o pedido. Tente novamente.";
  }
}
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
  <link rel="stylesheet" href="../css/carrinho.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" data-purpose-"Layout StyleSheet" title="Web incrível" href="/css/app-wa-3b124ff...css?vsn-d">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-duotone-solid.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-thin.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-solid.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-regular.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-light.css">
  <link rel="shortcut icon" type="image/x-icon" href="../imgs/favicon.ico">
</head>

<body>
  <?php 
  $paginaAtual = 'carrinho';
  include '../includes/header.php';
   ?>
  <div class="container">
    <h1 class="title-carrinho">Carrinho</h1>

    <section class="itens">
      <div class="carrinho">
        <?php if (count($items) > 0): ?>
          <?php foreach ($items as $item): ?>
            <div class="item-carrinho">
              <div class="imagem-produto">
                <img src="../imgs/produtos/<?= $item['imagem']; ?>" alt="<?= $item['nome']; ?>" style="height: auto;">
              </div>
              <div>
                <div class="detalhes-produto">
                  <h3><?= $item['nome']; ?></h3>
                  <p>Preço Unitário: R$ <?= number_format($item['preco'], 2, ',', '.'); ?></p>
                  <p>Quantidade: <?= $item['quantidade']; ?></p>
                  <p>Subtotal: R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?></p>
                </div>
                <div class="remover-item">
                  <form action="remover_do_carrinho.php" method="POST">
                    <input type="hidden" name="produto_id" value="<?= $item['produto_id']; ?>">
                    <input class="button" type="submit" value="Remover">
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
      
      <div class="pagamento">
        <!-- Formulário para escolher o método de pagamento e finalizar o pedido -->
        <form action="carrinho.php" method="POST">
          <div class="input">
            <h3>Escolha o método de pagamento:</h3>
            <label>
              <input class="select" type="radio" name="metodo_pagamento" value="cartao_credito" required>
              Cartão de Crédito
            </label><br>

            <label>
              <input class="select" type="radio" name="metodo_pagamento" value="cartao_debito">
              Cartão de Débito
            </label><br>

            <label>
              <input class="select" type="radio" name="metodo_pagamento" value="pix">
              PIX
            </label><br>
          </div>

          <div class="total-carrinho">
            <h2>Total: R$ <?= number_format($total, 2, ',', '.'); ?></h2>
            <input class="button2" type="submit" value="Finalizar Pedido">
          </div>
        </form>
      </div>
      <?php else: ?>
        <p class="aviso">Seu carrinho está vazio.</p>
      <?php endif; ?>
    </div>
  </div>

  <?php include '../includes/footer.php'; ?>
</body>
</html>
