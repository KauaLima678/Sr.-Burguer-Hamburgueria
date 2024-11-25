<?php
session_start();
include 'config/conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  // Consulta para verificar o usuário
  $sql = "SELECT * FROM usuarios WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  // Verifica se o usuário foi encontrado
  if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    // Verifica a senha
    if (password_verify($senha, $usuario['senha'])) {
      // Define as variáveis de sessão
      $_SESSION['usuario_id'] = $usuario['id'];
      $_SESSION['role'] = $usuario['role'];

      header('Location: /index.php');
      exit;
    } else {
      $erro = "Senha incorreta!";
    }
  } else {
    $erro = "Usuário não encontrado!";
  }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login - Fast Food</title>
  <link rel="stylesheet" href="./css/login.css">
  <link rel="shortcut icon" type="image/x-icon" href="../imgs/favicon.ico">

</head>

<body>
  <div class="container">
    <h1 class="title-login">Login</h1>

    <?php if (isset($erro)): ?>
      <p style="color: red;"><?= $erro; ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
      <label for="email">Email:</label>
      <input type="email" name="email" required>

      <label for="senha">Senha:</label>
      <input type="password" name="senha" required>

      <input class="button" type="submit" value="Entrar">
    </form>

    <p class="cadastro">Não tem uma conta? <br><a href="cadastro.php">Cadastre-se aqui!</a></p>
  </div>


</body>

</html>