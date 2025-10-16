<?php
include 'conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel Administrador</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="admin_painel999.css">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</head>
<body>

  <header>
    <div class="logo-header">
      <img src="img/logo.png" alt="Logo" style="height:80px; border-radius:50%;">
    </div>
  </header>

<div class="container">
  <aside class="sidebar">
    <h3>Menu</h3>
    <ul>
      <li><a href="admin.html"><i class="fa-solid fa-house"></i> Página inicial</a></li>
    </ul>
    <h3>Edite aqui!</h3>
    <ul>
      <li><a href="admin_situacoes.php"><i class="fa-solid fa-plus"></i> Nova Fase</a></li>
      <li><a href="admin_painel.php"><i class="fas fa-pencil"></i> Painel de Jogo</a></li>
    </ul>
    <ul>
    <br><br><br><br><br><br><br><br><br><br>
    <li class="sair"><a href="index.html"><i class="fas fa-right-from-bracket"></i> Sair</a></li>
    </ul>
  </aside>

  <main class="tabela">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Descrição</th>
          <th>Imagem</th>
          <th>Alternativas</th>
          <th>Adequada</th>
          <th>Ordem</th>
          <th>Nível</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody id="sortable-situacoes">
        <?php
        $sql = "SELECT s.*, a.descricao AS alternativa_adequada 
                FROM situacoes s
                LEFT JOIN alternativas a ON s.id = a.SITUACOES_id AND a.adequada = 1";
        $resultado = mysqli_query($con, $sql);

        while ($linha = mysqli_fetch_assoc($resultado)):

          $img = trim($linha['imagem']);
          $img = str_replace('uploads/', '', $img);
          $caminho = "uploads/$img";
        ?>
        <tr>
          <td><?= $linha['id'] ?></td>
          <td><?= htmlspecialchars($linha['descricao']) ?></td>
          <td>
            <?php if (!empty($img) && file_exists($caminho)): ?>
              <img src="<?= $caminho ?>" class="miniatura" data-full="<?= $caminho ?>" style="height:50px; border-radius:6px; object-fit:cover; cursor:pointer;">
            <?php else: ?>
              <span style="color:#999;">Imagem não encontrada</span>
            <?php endif; ?>
          </td>
          <td>
            <?php
            $id_situacao = $linha['id'];
            $sql_alt = "SELECT descricao, adequada FROM alternativas WHERE SITUACOES_id = $id_situacao";
            $res_alt = mysqli_query($con, $sql_alt);
            while ($alt = mysqli_fetch_assoc($res_alt)):
              $texto = htmlspecialchars($alt['descricao']);
              $marcada = $alt['adequada'] == 1 ? "<strong>(✔)</strong>" : "";
              echo "<div>$texto $marcada</div>";
            endwhile;
            ?>
          </td>
          <td><?= htmlspecialchars($linha['alternativa_adequada'] ?? '—') ?></td>
          <td><?= $linha['ordem'] ?></td>
          <td><?= $linha['nivel'] ?></td>
          <td>
            <a href='admin_editar_situacao.php?id=<?= $linha['id'] ?>'>Editar</a> |
            <a href='admin_excluir_situacao.php?id=<?= $linha['id'] ?>' onclick='return confirm("Tem certeza que deseja excluir?")'>Excluir</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>

<script>
new Sortable(document.getElementById('sortable-situacoes'), {
  animation: 150,
  onEnd: function () {
    const ordem = [...document.querySelectorAll('#sortable-situacoes tr')].map((row, index) => ({
      id: parseInt(row.querySelector('td').innerText),
      ordem: index + 1
    }));

    fetch('admin_painel_ordem.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(ordem)
    });
  }
});
</script>

<!-- Modal de imagem -->
<div id="imagemModal" class="modal">
  <span class="fechar">&times;</span>
  <img class="modal-conteudo" id="imagemAmpliada">
  <a id="baixarImagem" download style="color: #fff; display: block; text-align: center; margin-top: 10px; font-size: 16px;">⬇️ Baixar imagem</a>
</div>

<script>
// Abrir modal
document.querySelectorAll('.miniatura').forEach(img => {
  img.addEventListener('click', function() {
    const modal = document.getElementById('imagemModal');
    const modalImg = document.getElementById('imagemAmpliada');
    const baixar = document.getElementById('baixarImagem');
    modal.style.display = 'block';
    modalImg.src = this.dataset.full;
    baixar.href = this.dataset.full;
  });
});

// Fechar modal
document.addEventListener('DOMContentLoaded', function() {
  document.querySelector('.fechar').addEventListener('click', function() {
    document.getElementById('imagemModal').style.display = 'none';
  });
});
</script>

</body>
</html>
