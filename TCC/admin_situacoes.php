<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Administrador</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="admin_situacoesssss.css">
</head>
<body>

  <!-- Cabeçalho -->
  <header>
    <div class="logo-header">
      <img src="img/logo.png" alt="Logo" style="height:80px; border-radius:50%;">
    </div>
  </header>



  <div class="container">

    <!-- Menu lateral -->
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

    <!-- Conteúdo principal -->
    <main class="content">

      <!-- Aviso de sucesso -->
      <?php if (isset($_GET['sucesso'])): ?>
        <div id="aviso-sucesso" style="
          background:#d4edda;
          color:#155724;
          padding:15px;
          border:1px solid #c3e6cb;
          margin-bottom:20px;
          border-radius:8px;
          font-weight:bold;
          font-size:16px;
          display:flex;
          align-items:center;
          gap:10px;
          box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        ">
          <i class="fa-solid fa-circle-check" style="font-size:20px; color:#28a745;"></i>
          Situação cadastrada com sucesso!
        </div>
      <?php endif; ?>

      <!-- Título -->
      <div class="tituloADDSituacao">
        <img src="img/tituloADDSituacao.png" alt="Adicionar Situações">
      </div>

      <!-- Formulário de cadastro -->
      <form action="admin_inserir_situacao.php" method="POST" enctype="multipart/form-data" id="form-situacao">
        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao" placeholder="Escreva aqui..." required></textarea>

        <label>Imagem</label>
        <label class="upload">
          📥 Fazer download
          <input type="file" name="imagem" hidden required>
        </label>

        <label>Alternativas:</label>
        <div id="alternativas">
          <?php for ($i = 0; $i < 3; $i++): ?>
            <div>
              <input type="text" name="alternativas[]" placeholder="Alternativa <?= $i + 1 ?>">
              <input type="radio" name="adequada" value="<?= $i ?>" class="botao-correcao" <?= $i === 0 ? 'required' : '' ?>>
            </div>
          <?php endfor; ?>
        </div>
        
        <label for="ordem">Posição no jogo:</label>
        <input type="number" name="ordem" id="ordem" min="1" required>

        <label for="nivel">Nível:</label>
        <select name="nivel" id="nivel" required>
          <option value="">Selecione</option>
          <option value="Fácil">Fácil</option>
          <option value="Médio">Médio</option>
          <option value="Difícil">Difícil</option>
        </select>

        <button class="btn-salvar" type="submit">Salvar</button>
      </form>

    </main>
  </div>

  <!-- Script para desaparecer o aviso -->
  <script>
    setTimeout(() => {
      const aviso = document.getElementById('aviso-sucesso');
      if (aviso) {
        aviso.style.transition = "opacity 0.6s ease, transform 0.6s ease";
        aviso.style.opacity = 0;
        aviso.style.transform = "translateY(-10px)";
        setTimeout(() => aviso.remove(), 600);
      }
    }, 4000);
  </script>

  <!-- Validação de alternativas -->
  <script>
    document.getElementById('form-situacao').addEventListener('submit', function(event) {
      const alternativas = document.querySelectorAll('input[name="alternativas[]"]');
      const algumaPreenchida = Array.from(alternativas).some(input => input.value.trim());

      if (!algumaPreenchida) {
        event.preventDefault();
        alert('Preencha pelo menos uma alternativa.');
        alternativas[0].focus();
      }
    });
  </script>

</body>
</html>