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
        // Garante a ordem correta
        $sql = "SELECT s.*, a.descricao AS alternativa_adequada 
        FROM situacoes s
        LEFT JOIN alternativas a ON s.id = a.SITUACOES_id AND a.adequada = 1
        ORDER BY s.ordem ASC";
        $resultado = mysqli_query($con, $sql);

        while ($linha = mysqli_fetch_assoc($resultado)):

          $img = trim($linha['imagem']);
          $img = str_replace('uploads/', '', $img);
          $caminho = "uploads/$img";
        ?>
        <tr data-id="<?= $linha['id'] ?>">
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
            // Mostra todas as alternativas e destaca a correta
            $id_situacao = $linha['id'];
            $sql_alt = "SELECT descricao, adequada FROM alternativas WHERE SITUACOES_id = $id_situacao";
            $res_alt = mysqli_query($con, $sql_alt);
            while ($alt = mysqli_fetch_assoc($res_alt)):
              $texto = htmlspecialchars($alt['descricao']);
              if ($alt['adequada'] == 1) {
                echo "<div style='color:#27ae60;font-weight:bold;'>$texto (✔)</div>";
              } else {
                echo "<div>$texto</div>";
              }
            endwhile;
            ?>
          </td>
          <td style="color:#27ae60;font-weight:bold;">
            <?= htmlspecialchars($linha['alternativa_adequada'] ?? '—') ?>
          </td>
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
      id: parseInt(row.dataset.id),
      ordem: index + 1
    }));

    fetch('admin_painel_ordem.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(ordem)
    })
    .then(r => r.json())
    .then(res => {
      if (res.sucesso) {
        const aviso = document.createElement('div');
        aviso.textContent = 'Ordem atualizada!';
        aviso.style.cssText = 'position:fixed;top:10px;right:10px;background:#d4edda;color:#155724;padding:10px;border-radius:8px;z-index:9999;';
        document.body.appendChild(aviso);
        setTimeout(() => aviso.remove(), 1800);
      }
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

<style>
  /* Fonte e cores */
body {
  font-family: cursive, sans-serif;  /* Mantive cursive como original */
  color: #6b4847;
  margin: 0;
  padding: 0;
}

/* === Cabeçalho (consolidado, fixo) === */
/* Cabeçalho */
header {
  position: fixed;    /* Fixa no topo */
  top: 0;
  left: 0;
  width: 100%;
  background: #f59c00;
  height: 110px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 20px;
  color: white;
  font-weight: bold;
  box-sizing: border-box; /* Inclui padding/borda na largura */
  border-bottom: 3px solid #7b0f20; /* Exemplo de borda */
  z-index: 11;        /* Garante que fique acima da sidebar e do conteúdo */
}

header .perfil {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 25px;
}

header .perfil i {
  font-size: 50px;
}


/* Sidebar */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 220px;
  max-height: calc(100vh - 0px); /* ou ajusta se tiver cabeçalho fixo */
  background: #7b0f16;
  color: white;
  padding: 130px 20px 20px 20px; /* 130px no topo para dar espaço para o header e a logo */
  box-sizing: border-box;
  z-index: 10; /* Fica acima do conteúdo, mas abaixo do header */
  overflow-y: hidden;
}

.sidebar h3 {
  margin-top: 20px;
  font-size: 16px;
}

.sidebar ul {
  list-style: none;
  padding: 0;
}

.sidebar ul li {
  margin: 15px 0;
}

.sidebar ul li a {
  text-decoration: none;
  color: white;
  display: flex;
  align-items: center;
  gap: 10px;
  background: #a81a23;
  padding: 8px;
  border-radius: 8px;
  transition: 0.3s;
}

.sidebar ul li a:hover {
  background: #b82d36;
}

/* === Container === */
.container {
  display: flex;
  margin-top: 110px;  /* Espaço para header */
  margin-left: 220px;  /* Espaço para sidebar */
  min-height: calc(100vh - 110px);
}

/* === Tabela principal === */
.tabela {
  flex: 1;
  padding: 30px;
  overflow-x: auto;
}

.tabela table {
  width: 100%;
  border-collapse: collapse;
}

.tabela th, .tabela td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #eee;
}

.tabela th {
  background-color: #6b4847;
  font-weight: 600;
  color: #fff;
  
}

.tabela tr:hover {
  background-color: #fff2e2;
}

/* Botões de ação */
.tabela a {
  color: #f76105;
  text-decoration: none;
  margin-right: 10px;
}

.tabela a:hover {
  text-decoration: underline;
}
.tabela img {
  max-height: 50px;
  max-width: 50px;
  object-fit: cover;
  border-radius: 6px;
  box-shadow: 0 0 4px rgba(0,0,0,0.1);
}

.modal {
  display: none;
  position: fixed;
  z-index: 999;
  padding-top: 60px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.8);
}

.modal-conteudo {
  margin: auto;
  display: block;
  max-width: 80%;
  max-height: 80%;
  border-radius: 8px;
  box-shadow: 0 0 10px #000;
}

.fechar {
  position: absolute;
  top: 30px;
  right: 40px;
  color: #fff;
  font-size: 40px;
  font-weight: bold;
  cursor: pointer;
}

/* Ícone de ✔ */
strong {
  color: #27ae60;
}

/* Responsivo (melhorado, sem esconder sidebar) */
@media (max-width: 768px) {
  .sidebar {
    position: relative;
    top: 0;
    width: 100%;
    height: auto;
    margin-top: 0;
  }

  .container {
    flex-direction: column;
    margin-left: 0;
    margin-top: 0;  /* Sidebar agora é relativa */
  }

  .tabela {
    padding: 20px;
  }

  .tabela table,
  .tabela thead,
  .tabela tbody,
  .tabela th,
  .tabela td,
  .tabela tr {
    display: block;
  }

  .tabela thead tr {
    position: absolute;
    top: -9999px;
    left: -9999px;
  }

  .tabela tr {
    margin-bottom: 15px;
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    border: 1px solid #ddd;
  }

  .tabela td {
    padding: 8px 10px 8px 50%;
    border: none;
    position: relative;
    border-bottom: 1px dotted #ccc;
  }

  .tabela td::before {
    content: attr(data-label) ": ";
    font-weight: bold;
    position: absolute;
    left: 6px;
    width: 45%;
    padding-right: 10px;
    white-space: nowrap;
    color: #555;
  }

  .tabela td:last-child {
    border-bottom: none;
  }
}
</style>
</body>
</html>
