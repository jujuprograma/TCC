<?php
include 'conexao.php';

$id = $_POST['id'] ?? null;
$palavra = $_POST['palavra'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$sugestao = $_POST['sugestao'] ?? '';

if (!$id || !$palavra || !$descricao) {
    echo "Dados incompletos.";
    exit;
}

$sql = "UPDATE GLOSSARIO SET palavra = ?, descricao = ?, sugestao = ? WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("sssi", $palavra, $descricao, $sugestao, $id);

if ($stmt->execute()) {
    header("Location: admin_recursos_painel.php?sucesso=1");
} else {
    echo "Erro ao salvar: " . $stmt->error;
}

$stmt->close();
$con->close();