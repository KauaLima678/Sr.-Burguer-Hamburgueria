<?php 
session_start();
include '../config/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Verifica se produto_id e quantidade foram enviados via POST
if (isset($_POST['produto_id']) && isset($_POST['quantidade'])) {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];

    $sql = "SELECT * FROM carrinho WHERE usuario_id = ? AND produto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $usuario_id, $produto_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sql_update = "UPDATE carrinho SET quantidade = quantidade + ? WHERE usuario_id = ? AND produto_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("iii", $quantidade, $usuario_id, $produto_id);
        $stmt_update->execute();
    } else {
        $sql_insert = "INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iii", $usuario_id, $produto_id, $quantidade);
        $stmt_insert->execute();
    }

    header('Location: carrinho.php');
    exit;
} else {
    echo "Erro: Produto ou quantidade não foram especificados.";
}
?>
