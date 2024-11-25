<?php 
session_start();
include './config/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

$mensagem = "";  // Variável para armazenar a mensagem

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bairro = $_POST['bairro'];
    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $usuario_id = $_SESSION['usuario_id'];

    $sql = "UPDATE usuarios SET bairro = ?, logradouro = ?, numero = ?, complemento = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $bairro, $logradouro, $numero, $complemento, $usuario_id);

    if ($stmt->execute()) {
        $mensagem = "Perfil atualizado com sucesso!";
    } else {
        $mensagem = "Erro ao atualizar perfil";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Perfil - Fast Food</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" data-purpose-"Layout StyleSheet" title="Web incrível" href="/css/app-wa-3b124ff...css?vsn-d"> 
<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-duotone-solid.css">
<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-thin.css">
<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-solid.css">
<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-regular.css"> <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-light.css">
<link rel="shortcut icon" type="image/x-icon" href="../imgs/favicon.ico">
  <link rel="stylesheet" href="./css/perfil.css">
  <link rel="stylesheet" href="./css/header.css">
</head>
<body>
  
  <?php
$paginaAtual = 'perfil';
 include "./includes/header.php";
  ?>
  
<div class="msg">
  <?php if ($mensagem): ?>
      <p class="mensagem"><?= $mensagem ?></p>
  <?php endif; ?>

</div>

<div class="container">
  <div class="inic">
    <h1 class="title-perfil"><input type="text" name="nome" value="<?= $usuario['nome'] ?>" required></h1>
    
    <div class="picture">
      <img style="width: 300px;" src="./imgs/user.avif" alt="Foto de perfil">
    </div>
  </div>
  
  
  <form action="perfil.php" method="POST">
    <h1 class="informs">Informações</h1>
      
      <div class="info">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= $usuario['email'] ?>" required>
      </div>

      <div class="info">
        <label for="senha">Senha:</label>
        <input type="password" name="senha">
      </div>

      <div class="info">
        <label for="bairro">Bairro:</label>
        <input type="text" name="bairro" id="bairro" value="<?= $usuario['bairro'] ?>" required>
      </div>

      <div class="info">
        <label for="logradouro">Logradouro:</label>
        <input type="text" name="logradouro" id="logradouro" value="<?= $usuario['logradouro'] ?>" required>
      </div>

      <div class="info">
        <label for="numero">Número:</label>
        <input type="text" name="numero" id="numero" value="<?= $usuario['numero'] ?>" required>
      </div>

      <div class="info">
        <label for="complemento">Complemento:</label>
        <input type="text" name="complemento" id="complemento" value="<?= $usuario['complemento'] ?>">
      </div>
      
      <div class="button-at">
        <input class="button" type="submit" value="Atualizar Perfil">
      </div>
    </form>

  </div>

<footer>
  <div class="content">
  <div class="contato">
    <h1 class="title-contato">Contato</h1>
    <p><i class="fa-solid fa-phone" style="color: #000000;"></i>(16) 3203-8922</p>
    <p><i class="fa-solid fa-envelope" style="color: #000000;"></i>senhor.burguer@gmail.com</p>
  </div>

  <div class="redes">
    <h1>Redes sociais</h1>
    <a href=""><i class="fa-brands fa-whatsapp" style="color: #000000;"></i></a>
    <a href=""><i class="fa-brands fa-facebook" style="color: #000000;"></i></a>
    <a href=""><i class="fa-brands fa-instagram" style="color: #000000;"></i></a>
  </div>

  <div class="links">
    <h1>Links úteis</h1>
    <a href="./usuario/index.php">Home</a>
    <a href="./usuario/Sobre.php">Sobre</a>
    <a href="./usuario/carrinho.php">Carrinho</a>
    <a href="perfil.php">Perfil</a>
    <a href="./usuario/compras.php"></a>
  </div>
  </div>
  
  <div class="copyright">
    <hr>
    <br>
    <p>©Todos os direitos reservados | Desenvolvido por Kauã Lima, Eduardo Junqueira, julia delamonica e Beatriz marino</p>
  </div>
</footer>
</body>
</html>
