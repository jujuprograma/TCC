<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

/* === Título === */
.tituloADDSituacao {
  margin-left: 80px;
  margin-top: 10px;
}

/* === Subtitulos e Inputs === */
label {
  font-weight: bold;
  margin-top: 30px;
  margin-left: 90px;
  display: block;
  color: #80142d;
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

/* === Upload === */
.upload {
  border: 2px solid #80142d;
  border-radius: 8px;
  display: inline-block;
  padding: 10px 15px;
  margin-top: 10px;
  cursor: pointer;
  color: #80142d;
  font-weight: bold;
  transition: transform 0.3s ease;

}
.upload:hover{
  transform: scale(1.1);

}

/* === Alternativas === */
#alternativas div {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-left: 90px;
  margin-top: 10px;
}

#alternativas input[type="text"] {
  flex: 1;
  margin-left: 0;
}

/* === Botão Correção === */
.botao-correcao {
  appearance: none;
  width: 22px;
  height: 22px;
  border: 2px solid #3aa176;
  border-radius: 50%;
  background-color: white;
  cursor: pointer;
  transition: background 0.3s ease;
}

.botao-correcao:checked {
  background-color: #3aa176;
  box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.3);
  transition: transform 0.3s ease;
}
.botao-correcao:hover{
  background-color: #3aa176;
  box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.3);  
  transform: scale(1.1);
}

#ordem{
  border: 2px solid #80142d;
  border-radius: 8px;
  display: inline-block;
  padding: 10px;
  margin-top: 10px;
  margin-left: 90px;
  cursor: pointer;
  color: #80142d;
  font-weight: bold;
}
#nivel{
  border: 2px solid #80142d;
  border-radius: 8px;
  display: inline-block;
  padding: 10px 15px;
  margin-top: 10px;
  margin-left: 90px;
  cursor: pointer;
  color: #80142d;
  font-weight: bold;
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


/* === Tabela === */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

table th,
table td {
  border: 2px solid #80142d;
  padding: 10px;
  text-align: left;
}

table th {
  background-color: #f69e00;
  color: white;
}

table td img {
  border-radius: 6px;
}






/*-----------------------------------------------------*/
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

  </style>

</body>
</html>