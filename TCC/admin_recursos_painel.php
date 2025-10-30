<?php
include 'conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Painel de Recursos Educativos</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

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

  <main class="tabela">
    <h2 style="margin-bottom:20px;">Painel de Recursos Educativos</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Palavra-chave</th>
          <th>Descrição</th>
          <th>Sugestão</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM GLOSSARIO ORDER BY id DESC";
        $resultado = mysqli_query($con, $sql);

        while ($linha = mysqli_fetch_assoc($resultado)):
        ?>
        <tr>
          <td><?= $linha['id'] ?></td>
          <td><?= htmlspecialchars($linha['palavra']) ?></td>
          <td><?= htmlspecialchars($linha['descricao']) ?></td>
          <td>
            <?php if (!empty($linha['sugestao'])): ?>
              <a href="<?= $linha['sugestao'] ?>" target="_blank">Ver recurso</a>
            <?php else: ?>
              <span style="color:#999;">—</span>
            <?php endif; ?>
          </td>
          <td>
            <a href='admin_recursos_editar.php?id=<?= $linha['id'] ?>'>Editar</a> |
            <a href='admin_recursos_excluir.php?id=<?= $linha['id'] ?>' onclick='return confirm("Tem certeza que deseja excluir?")'>Excluir</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>

<style>
/* Reaproveita o estilo do painel original */
body {
  font-family: cursive, sans-serif;
  color: #6b4847;
  margin: 0;
  padding: 0;
}

header {
  position: fixed;
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
  box-sizing: border-box;
  border-bottom: 3px solid #7b0f20;
  z-index: 11;
}

.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 220px;
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

.container {
  display: flex;
  margin-top: 110px;
  margin-left: 220px;
  min-height: calc(100vh - 110px);
}

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

.tabela a {
  color: #f76105;
  text-decoration: none;
  margin-right: 10px;
}

.tabela a:hover {
  text-decoration: underline;
}
</style>

</body>
</html>