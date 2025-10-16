<?php
include 'conexao.php';

// --- Verifica e busca o registro pelo ID ---
$id = intval($_GET['id']);
$query = "SELECT * FROM situacoes WHERE id = $id";
$result = mysqli_query($con, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Situação não encontrada.";
    exit;
}

$linha = mysqli_fetch_assoc($result);
?>

<form action="admin_atualizar_situacao.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?= $linha['id'] ?>">

  <label>Descrição:</label>
  <input type="text" name="descricao" value="<?= htmlspecialchars($linha['descricao']) ?>" required>

  <label>Nível:</label>
  <input type="text" name="nivel" value="<?= htmlspecialchars($linha['nivel']) ?>" required>

  <label>Imagem:</label>
  <input type="file" name="imagem">

  <?php if (!empty($linha['imagem'])): ?>
    <p>Imagem atual:</p>
    <img src="uploads/<?= htmlspecialchars($linha['imagem']) ?>" alt="Imagem atual" width="150">
  <?php endif; ?>

  <button type="submit">Salvar</button>
</form>
