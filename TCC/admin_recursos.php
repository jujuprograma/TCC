<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $link_video = $_POST['link_video'];
    $imagem = $_FILES['imagem']['name'];

    // pasta onde as imagens serão guardadas
    $pasta = "uploads/";
    if (!is_dir($pasta)) mkdir($pasta);

    $destino = $pasta . basename($imagem);
    move_uploaded_file($_FILES['imagem']['tmp_name'], $destino);

    $sql = "INSERT INTO recursos (titulo, imagem, link_video) VALUES ('$titulo', '$destino', '$link_video')";
    mysqli_query($conexao, $sql);
    echo "<script>alert('Recurso adicionado com sucesso!');window.location='adicionar_recurso.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adicionar Recurso Educativo</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #2b241f;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 40px;
    }

    form {
      background: #3a322d;
      padding: 30px;
      border-radius: 12px;
      width: 400px;
      box-shadow: 0 0 10px rgba(0,0,0,0.6);
    }

    label {
      display: block;
      margin-top: 15px;
    }

    input[type="text"], input[type="url"], input[type="file"] {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 6px;
      margin-top: 5px;
    }

    button {
      margin-top: 20px;
      padding: 10px 20px;
      background: #8aa648;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
    }

    button:hover {
      background: #a3be61;
    }
  </style>
</head>
<body>
  <h2>Adicionar Recurso Educativo</h2>
  <form method="POST" enctype="multipart/form-data">
    <label for="titulo">Título:</label>
    <input type="text" name="titulo" id="titulo" required>

    <label for="imagem">Imagem (arquivo .jpg ou .png):</label>
    <input type="file" name="imagem" id="imagem" accept="image/*" required>

    <label for="link_video">Link do Vídeo:</label>
    <input type="url" name="link_video" id="link_video" placeholder="https://..." required>

    <button type="submit">Salvar</button>
  </form>
</body>
</html>
