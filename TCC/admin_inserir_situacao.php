<?php
include("conexao.php");

// --- Sanitização básica ---
$descricao = trim($_POST['descricao']);
$nivel = trim($_POST['nivel']);
$ordem = intval($_POST['ordem']);

// --- Upload da imagem ---
if (!empty($_FILES['imagem']['name'])) {
    $imagem = uniqid() . "_" . basename($_FILES['imagem']['name']);
    $caminho = "uploads/" . $imagem;

    if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
        die("Erro ao enviar a imagem.");
    }
} else {
    $caminho = null; // caso a imagem não seja obrigatória
}

// --- Insere situação ---
$sql = "INSERT INTO situacoes (descricao, imagem, nivel, ordem) VALUES (?, ?, ?, ?)";
$stmt = $con->prepare($sql);

if (!$stmt) {
    die("Erro na preparação da query: " . $con->error);
}

$stmt->bind_param("sssi", $descricao, $caminho, $nivel, $ordem);
$stmt->execute();
$id_situacao = $stmt->insert_id;
$stmt->close();

// --- Insere alternativas ---
if (isset($_POST['alternativas']) && is_array($_POST['alternativas'])) {
    $correta = intval($_POST['adequada']); // <-- Corrigido aqui!

    $sqlAlt = "INSERT INTO alternativas (descricao, adequada, SITUACOES_id) VALUES (?, ?, ?)";
    $stmtAlt = $con->prepare($sqlAlt);

    if (!$stmtAlt) {
        die("Erro na preparação da query de alternativas: " . $con->error);
    }

    foreach ($_POST['alternativas'] as $i => $alt) {
        $alt = trim($alt);
        if ($alt !== "") {
            $adequada = ($i == $correta) ? 1 : 0;
            $stmtAlt->bind_param("sii", $alt, $adequada, $id_situacao);
            $stmtAlt->execute();
        }
    }
    $stmtAlt->close();
}

// --- Redireciona após sucesso ---
header("Location: admin_situacoes.php?sucesso=1");
exit;
?>