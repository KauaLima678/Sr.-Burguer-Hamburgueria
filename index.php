<?php
session_start();

//nao tem regra
if(!isset($_SESSION['role'])){
    header('Location: usuario/index.php');
    exit();
}

//regra admin
if($_SESSION['role'] === "admin"){
    header('Location: admin/index.php');
    exit();

} else if($_SESSION['role'] === "cliente"){
    header('Location: usuario/index.php');
    exit();
    
} else {
    echo "Erro: Tipo de usuario desconhecido.";
    exit();
}