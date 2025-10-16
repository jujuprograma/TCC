<?php
include 'conexao.php';

// --- Sanitização de dados ---
$id = intval($_POST['id']);
$descricao = mysqli_real_escape_string($con, $_POST['descricao']);
$nivel = mysqli_real_escape_string($con, $_POST['nivel']);

// --- Verifica se há uma imagem nova ---
$imagem = $_FILES['imagem']['name'] ?? '';

if (!empty($imagem)) {
    // Define o caminho do upload
    $caminho = 'uploads/' . basename($imagem);

    // Faz o upload da imagem
    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
        // Atualiza com imagem nova
        $sql = "UPDATE situacoes 
                SET descricao = '$descricao', nivel = '$nivel', imagem = '$imagem' 
                WHERE id = $id";
    } else {
        // Caso o upload falhe, mantém os dados antigos
        $sql = "UPDATE situacoes 
                SET descricao = '$descricao', nivel = '$nivel' 
                WHERE id = $id";
    }
} else {
    // Atualiza apenas texto (sem imagem)
    $sql = "UPDATE situacoes 
            SET descricao = '$descricao', nivel = '$nivel' 
            WHERE id = $id";
}

// --- Executa a query ---
mysqli_query($con, $sql);

// --- Redireciona de volta ao painel ---
header("Location: admin_painel.php");
exit;
?>
