<?php
include 'conexao.php';

// --- Sanitização de dados ---
$id = intval($_POST['id']);
$descricao = mysqli_real_escape_string($con, $_POST['descricao']);
$nivel = mysqli_real_escape_string($con, $_POST['nivel']);

// --- Verifica se há uma imagem nova ---
$imagem = $_FILES['imagem']['name'] ?? '';

if (!empty($imagem)) {
    $imagem_nome = uniqid() . "_" . basename($imagem);
    $caminho = 'uploads/' . $imagem_nome;

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
        $sql = "UPDATE situacoes 
                SET descricao = '$descricao', nivel = '$nivel', imagem = '$imagem_nome' 
                WHERE id = $id";
    } else {
        $sql = "UPDATE situacoes 
                SET descricao = '$descricao', nivel = '$nivel' 
                WHERE id = $id";
    }
} else {
    $sql = "UPDATE situacoes 
            SET descricao = '$descricao', nivel = '$nivel' 
            WHERE id = $id";
}

// --- Executa a query para atualizar situação ---
mysqli_query($con, $sql);

// --- Atualiza alternativas ---
if (isset($_POST['alternativas']) && is_array($_POST['alternativas'])) {
    $correta_id = intval($_POST['adequada']); // id da alternativa correta

    foreach ($_POST['alternativas'] as $alt_id => $alt_texto) {
        $alt_texto = mysqli_real_escape_string($con, $alt_texto);
        // Define 1 para alternativa correta, 0 para as demais
        $adequada = ($alt_id == $correta_id) ? 1 : 0;
        // Atualiza cada alternativa
        mysqli_query($con, "UPDATE alternativas SET descricao = '$alt_texto', adequada = $adequada WHERE id = $alt_id AND SITUACOES_id = $id");
    }
}

// --- Redireciona de volta ao painel ---
header("Location: admin_painel.php");
exit;
?>