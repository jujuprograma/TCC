<?php
include 'conexao.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID inválido.";
    exit;
}

$sql = "SELECT * FROM GLOSSARIO WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$recurso = $result->fetch_assoc();

if (!$recurso) {
    echo "Recurso não encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Recurso</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 40px;
        }
        form {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background: #80142d;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background: #a91f40;
        }
    </style>
</head>
<body>

<h2>Editar Recurso Educativo</h2>

<form action="admin_recursos_atualizar.php" method="POST">
    <input type="hidden" name="id" value="<?= $recurso['id'] ?>">
    
    <label for="palavra">Palavra-chave:</label>
    <input type="text" name="palavra" id="palavra" value="<?= htmlspecialchars($recurso['palavra']) ?>" required>

    <label for="descricao">Descrição:</label>
    <textarea name="descricao" id="descricao" rows="4" required><?= htmlspecialchars($recurso['descricao']) ?></textarea>

    <label for="sugestao">Sugestão (link de vídeo, imagem, artigo):</label>
    <input type="text" name="sugestao" id="sugestao" value="<?= htmlspecialchars($recurso['sugestao']) ?>">

    <button type="submit">Salvar alterações</button>
</form>

</body>
</html>