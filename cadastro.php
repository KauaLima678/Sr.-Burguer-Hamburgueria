<?php
session_start();
include 'config/conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
  $bairro = $_POST['bairro'];
  $logradouro = $_POST['logradouro'];
  $numero = $_POST['numero'];
  $complemento = $_POST['complemento'];

  // Insere os dados no banco de dados
  $sql = "INSERT INTO usuarios (nome, email, senha, bairro, logradouro, numero, complemento) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssssss", $nome, $email, $senha, $bairro, $logradouro, $numero, $complemento);

  if ($stmt->execute()) {
    echo "Usuário cadastrado com sucesso!";
  } else {
    echo "Erro ao cadastrar o usuário.";
  }
}
?>




<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Cadastro de usuario - Fast Food</title>
  <link rel="stylesheet" href="./css/cadastro.css">

</head>

<body>
  <div class="container">
    <h1 class="title-login">Cadastro   de   Usuário</h1>

    <?php if (isset($erro)): ?>
      <p style="color: red;"><?= $erro; ?></p>
    <?php endif; ?>

    <form action="cadastro.php" method="POST">
      <div class="inputs">
      <label for="nome">Nome:</label>
      <input type="text" name="nome" required>
      </div>
      
      <div class="inputs">
      <label for="email">Email:</label>
      <input type="email" name="email" required>
      </div>

      <div class="inputs">
      <label for="senha">Senha:</label>
      <input type="password" name="senha" required>
      </div>

      <h1 class="title-endereço" style="color: #F9B125">Endereço</h1>
    

      <div class="inputs">
      <label for="bairro">Bairro:</label>
      <input type="text" name="bairro" id="bairro" required>
      </div>

      <div class="inputs">
      <label for="logradouro">Logradouro:</label>
      <input type="text" name="logradouro" id="logradouro" required>
      </div>

      <div class="inputs">
      <label for="numero">Número:</label>
      <input type="text" name="numero" id="numero" required>
      </div>

      <div class="inputs">
      <label for="complemento">Complemento:</label>
      <input type="text" name="complemento" id="complemento">
      </div> 
      <div class="btn-cont">
        <input class="button" type="submit" value="Cadastrar">
      </div>
    </form>


    <p class="login">Já tem uma conta?<br><a href="login.php">Faça login aqui</a>.</p>
  </div>


</body>

</html>