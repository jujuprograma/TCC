<?php
include 'conexao.php';

// Lê o JSON enviado no corpo da requisição
$data = json_decode(file_get_contents("php://input"), true);

if (is_array($data)) {
    $stmt = $con->prepare("UPDATE situacoes SET ordem = ? WHERE id = ?");

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao preparar statement: " . $con->error]);
        exit;
    }

    foreach ($data as $item) {
        $ordem = intval($item['ordem']);
        $id = intval($item['id']);
        $stmt->bind_param("ii", $ordem, $id);
        $stmt->execute();
    }

    $stmt->close();
    echo json_encode(["sucesso" => true]);
} else {
    http_response_code(400);
    echo json_encode(["erro" => "Formato de dados inválido."]);
}
?>