<!-- admin_glossario.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        <li><a href="admin_recursos.php"><i class="fa-solid fa-plus"></i> Recursos Educativos</a></li>
        <li><a href="admin_recursos_painel.php"><i class="fas fa-pencil"></i> Painel da Educação</a></li>
      </ul>
      <ul>
      <br><br><br><br>
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
      <div class="tituloADDRecursos">
        <img src="img/tituloADDRecursos.png" alt="Adicionar Recursos Educativos">
      </div>

    <form action="admin_inserir_recursos.php" method="POST">
        <label for="palavra">Palavra-chave:</label>
        <input type="text" id="palavra" name="palavra" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" rows="4" cols="50" required></textarea>

        <label for="sugestao">Sugestão (link de vídeo, imagem, artigo):</label>
        <input type="text" id="sugestao" name="sugestao">

        <button class="btn-salvar" type="submit">Salvar</button>

    </form>

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

   <style>
    /* ===  Fonte === */
body {
  margin: 0;
  font-family: cursive;
}

/* === Container Principal === */
.container {
  display: flex;
  height: calc(200vh - 60px);
}

textarea,
input[type="text"] {
  width: 80%;
  padding: 10px;
  border: 2px solid #80142d;
  border-radius: 8px;
  margin-top: 5px;
  margin-left: 90px;
  font-size: 14px;
}
/* === Cabeçalho === */
header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 110px;
  background: #f59c00;
  color: white;
  font-weight: bold;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 20px;
  box-sizing: border-box;
  border-bottom: 3px solid #7b0f20;
  z-index: 11;
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
/* === Sidebar === */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 220px;
  height: 100vh;
  background: #7b0f16;
  color: white;
  padding: 130px 20px 20px;
  box-sizing: border-box;
  z-index: 10;
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

/* === Conteúdo === */
.content {
  margin-left: 240px;
  padding-top: 130px;
  box-sizing: border-box;
}

/* === Título === */
.tituloADDRecursos {
  margin-left: 80px;
  margin-top: 10px;
}

label {
  font-weight: bold;
  margin-top: 20px;
  margin-left: 90px;
  display: block;
  color: #80142d;
}

/* === Botão Salvar === */
.btn-salvar {
  display: block;
  margin-top: 20px;
  margin-left: 90px;
  background: #80142d;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  font-size: 16px;
  font-weight: bold;
  transition: transform 0.3s ease;
}
.btn-salvar:hover {
  background: #a91f40;
  transform: scale(1.1);
}


  </style>

</body>
</html>