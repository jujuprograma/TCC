<?php
include ("conexao.php");

$host = "localhost";
$user = "root";
$senha = "";
$banco = "projeto_situacoes";

$con = new mysqli($host, $user, $senha, $banco);

if ($con->connect_error) {
    die("Erro na conexão: " . $con->connect_error);
}

$palavra = $_POST['palavra'];
$descricao = $_POST['descricao'];
$sugestao = $_POST['sugestao'];

$sql = "INSERT INTO GLOSSARIO (palavra, descricao, sugestao) VALUES (?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("sss", $palavra, $descricao, $sugestao);

if ($stmt->execute()) {
    echo "Recurso inserido com sucesso!";
} else {
    echo "Erro ao inserir: " . $stmt->error;
}

$stmt->close();
$con->close();
?>