<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Verifica se o ID existe antes de tentar apagar
    $check = mysqli_query($con, "SELECT id FROM situacoes WHERE id = $id");
    if (mysqli_num_rows($check) > 0) {
        // Exclui primeiro as alternativas relacionadas
        mysqli_query($con, "DELETE FROM alternativas WHERE SITUACOES_id = $id");

        // Exclui a situação
        mysqli_query($con, "DELETE FROM situacoes WHERE id = $id");
    }
}

// Redireciona de volta ao painel
header("Location: admin_painel.php");
exit;
?>
