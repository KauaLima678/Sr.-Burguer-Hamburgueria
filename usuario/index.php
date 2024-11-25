<?php
session_start();
include '../config/conexao.php';

$sql = "SELECT * FROM produtos WHERE status = 'ativo'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sr. Burguer</title>
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
  <link rel="stylesheet" href="../css/header.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lalezar&display=swap');
  </style>
</head>

<body>
  <?php 
  $paginaAtual = 'inicio';
  include "../includes/header.php"; 
  ?>
  <section class="inicio">
    <img src="../imgs/delicious-burger-studio.jpg" alt="">
    <div class="info-inicio">
      <h1 class="title">Sr. Burguer</h1>
      <p>Um reino de sabores prontos <br> para conquistar seu paladar <br> em cada mordida.</p>
      <p style="font-size: 15px">R. Sebastião Morgato, 90 - Cachoeira dos Mendes, Jaboticabal <br> SP, 14887-388</p>
    </div>
  </section>
  <div class="container">
    <div class="info-inic">
      <h1>Bem-vindo à lanchonete Sr. Burguer!</h1>
      <p>Aqui estão os produtos disponíveis:</p>
    </div>

    <div class="produtos">
      <?php while ($produto = $result->fetch_assoc()) : ?>
        <div class="produto">
          <img src="../imgs/produtos/<?= $produto['imagem']; ?>" alt="<?= $produto['nome']; ?>" style="width: 280px; height: auto;">
          <h3 class="nome"><?= $produto['nome']; ?></h3>
          <p class="descricao"><?= $produto['descricao']; ?></p>
          <p class="preco"><span>R$</span><?= $produto['preco']; ?></p>

          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'cliente') : ?>
            <form action="adicionar_ao_carrinho.php" method="POST">
              <input type="hidden" name="produto_id" value="<?= $produto['id']; ?>">
              <label for="quantidade">Quantidade:</label>
              <input class="qntd" type="number" name="quantidade" value="1" min="1">
              <input class="adc-carrinho" type="submit" value="Adicionar ao Carrinho">
            </form>
          <?php else : ?>
            <a style="background-color: #F9B125; width: 100%; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; padding: 8px; border: 0; bottom: 0; text-decoration:none; font-family: 'Montserrat'; font-weight: 450; color:black" href="../login.php">Adicionar ao carrinho</a>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <?php include "../includes/footer.php"; ?>
</body>

</html>
