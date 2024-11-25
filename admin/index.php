<?php
session_start();
include '../config/conexao.php';

// Verifica se o usuário é admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header('Location: ../login.php');
  exit;
}

// Deletar produto
if (isset($_GET['remover_id'])) {
  $remover_id = $_GET['remover_id'];

  // Primeiro, removemos o produto da tabela 'itens_pedido' que faz referência ao produto
  $delete_itens_pedido_sql = "DELETE FROM itens_pedido WHERE produto_id = ?";
  $stmt_itens_pedido = $conn->prepare($delete_itens_pedido_sql);
  $stmt_itens_pedido->bind_param("i", $remover_id);

  if ($stmt_itens_pedido->execute()) {
    // Agora, removemos o produto da tabela 'carrinho' que faz referência ao produto
    $delete_carrinho_sql = "DELETE FROM carrinho WHERE produto_id = ?";
    $stmt_carrinho = $conn->prepare($delete_carrinho_sql);
    $stmt_carrinho->bind_param("i", $remover_id);

    if ($stmt_carrinho->execute()) {
      // Agora, podemos excluir o produto da tabela 'produtos'
      $delete_sql = "DELETE FROM produtos WHERE id = ?";
      $stmt_produto = $conn->prepare($delete_sql);
      $stmt_produto->bind_param("i", $remover_id);

      if ($stmt_produto->execute()) {
        echo "<script>alert('Produto removido com sucesso!'); window.location.href = 'index.php';</script>";
      } else {
        echo "<script>alert('Erro ao remover o produto!');</script>";
      }
      $stmt_produto->close();
    } else {
      echo "<script>alert('Erro ao remover o produto do carrinho!');</script>";
    }
    $stmt_carrinho->close();
  } else {
    echo "<script>alert('Erro ao remover o produto de itens do pedido!');</script>";
  }
  $stmt_itens_pedido->close();
}

$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Painel Admin - Fast Food</title>
  <link rel="stylesheet" href="../css/index.css">
  <link rel="stylesheet" href="../css/index-adm.css">
  <link rel="stylesheet" href="../css/footer.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" data-purpose-"Layout StyleSheet" title="Web incrível" href="/css/app-wa-3b124ff...css?vsn-d">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-duotone-solid.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-thin.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-solid.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-regular.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-light.css">
  <link rel="stylesheet" href="../css/index.css">
  <link rel="shortcut icon" type="image/x-icon" href="../imgs/favicon.ico">
</head>

<body>
<?php 
$paginaAtual = 'inicio';
include  '../includes/header.php'; ?>
<section class="inicio">
  <img src="../imgs/delicious-burger-studio.jpg" alt="">
  <div class="info-inicio">
    <h1 class="title">Sr. Burguer</h1>
    <p>Um reino de sabores prontos <br> para conquistar seu paladar <br> em cada mordida.</p>
    <p style="font-size: 15px">R. Sebastião Morgato, 90 - Cachoeira dos Mendes, Jaboticabal <br> SP, 14887-388</p>
    <!-- <p style="font-size: 20px;">Horário de funcionamento: <br>18:00 as 22:00</p> -->
  </div>
</section>

<div class="container">
  <h1 style="color: #F9B125; text-align:center; margin-bottom: 30px">Produtos</h1>

  <div class="produtos">
    <?php while ($produto = $result->fetch_assoc()): ?>
      <div class="produto">
        <img src="../imgs/produtos/<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" style="width: 280px; height: auto;">
        <h3><?php echo $produto['nome']; ?></h3>
        <p>Descrição: <?php echo $produto['descricao']; ?></p>
        <p>Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
        <p>Status: <?php echo $produto['status'] == 1 ? 'Ativo' : 'Inativo'; ?></p>

        <div class="edits">
          <a href="editar_produto.php?id=<?php echo $produto['id']; ?>">Editar</a>
          <a href="index.php?remover_id=<?php echo $produto['id']; ?>" onclick="return confirm('Tem certeza que deseja remover este produto?')">Remover</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<?php include "../includes/footer.php" ?>
</body>
</html>
