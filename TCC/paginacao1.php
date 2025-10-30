<?php
// situacoes_lista.php
// Exemplo: imprime tabela HTML com os registros da tabela situacoes
// Ajuste as credenciais abaixo ou inclua seu arquivo conexao.php

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'projeto_situacoes';

// Conexão
$con = new mysqli($host, $user, $pass, $db);
if ($con->connect_error) {
    die('Erro de conexão: ' . $con->connect_error);
}
$con->set_charset('utf8mb4');

// Parâmetros de paginação (opcional)
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = isset($_GET['per_page']) ? max(1, intval($_GET['per_page'])) : 10;
$offset = ($page - 1) * $perPage;

// Contar total (opcional)
$total = 0;
$resTotal = $con->query("SELECT COUNT(*) AS total FROM situacoes");
if ($resTotal) {
    $rowTotal = $resTotal->fetch_assoc();
    $total = intval($rowTotal['total']);
    $resTotal->free();
}
$totalPages = max(1, ceil($total / $perPage));

// Busca situações paginadas (prepared statement para LIMIT)
$sql = "SELECT * FROM situacoes ORDER BY ordem ASC, id ASC LIMIT ?, ?";
$stmt = $con->prepare($sql);
if (!$stmt) {
    die("Erro ao preparar query: " . $con->error);
}
$stmt->bind_param("ii", $offset, $perPage);
$stmt->execute();
$result = $stmt->get_result();

$situacoes = [];
$ids = [];
while ($row = $result->fetch_assoc()) {
    $situacoes[] = $row;
    $ids[] = intval($row['id']);
}
$result->free();
$stmt->close();

// Busca alternativas para todas as situações retornadas (uma única query)
$alternativasPorSituacao = [];
if (count($ids) > 0) {
    $ids_list = implode(',', $ids); // ids já são inteiros
    $sql_alt = "SELECT id, descricao, adequada, SITUACOES_id FROM alternativas WHERE SITUACOES_id IN ($ids_list) ORDER BY id ASC";
    $res_alt = $con->query($sql_alt);
    if ($res_alt) {
        while ($a = $res_alt->fetch_assoc()) {
            $sid = intval($a['SITUACOES_id']);
            if (!isset($alternativasPorSituacao[$sid])) $alternativasPorSituacao[$sid] = [];
            $alternativasPorSituacao[$sid][] = $a;
        }
        $res_alt->free();
    }
}

// Função helper para escapar
function h($s) { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Lista de Situações</title>
<style>
  body { font-family: Arial, Helvetica, sans-serif; color:#333; padding:16px; }
  table { width:100%; border-collapse: collapse; margin-top:12px; }
  th, td { padding:10px; border:1px solid #e6e6e6; vertical-align:top; text-align:left; }
  th { background:#f3f3f3; }
  .mini { height:60px; width:60px; object-fit:cover; border-radius:6px; }
  .alt-correta { color:#1e8f3e; font-weight:700; }
  .pager { margin-top:16px; display:flex; align-items:center; gap:8px; }
  .pager a, .pager span { padding:6px 10px; border-radius:6px; text-decoration:none; border:1px solid #ddd; color:#333; }
  .pager .active { background:#6b4847; color:#fff; border-color:#6b4847; }
</style>
</head>
<body>

<h1>Lista de Situações</h1>
<p>Total de situações: <?= $total ?></p>

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
    </tr>
  </thead>
  <tbody>
    <?php if (count($situacoes) === 0): ?>
      <tr><td colspan="7">Nenhuma situação encontrada.</td></tr>
    <?php else: ?>
      <?php foreach ($situacoes as $sit): 
        $sid = intval($sit['id']);
        $img = trim($sit['imagem']);
        $imgName = str_replace('uploads/', '', $img);
        $imgPath = "uploads/$imgName";
      ?>
      <tr>
        <td><?= $sid ?></td>
        <td><?= h($sit['descricao']) ?></td>
        <td>
          <?php if ($imgName !== '' && file_exists($imgPath)): ?>
            <img src="<?= h($imgPath) ?>" alt="imagem" class="mini">
          <?php else: ?>
            <span style="color:#999">sem imagem</span>
          <?php endif; ?>
        </td>
        <td>
          <?php
            if (!empty($alternativasPorSituacao[$sid])) {
              foreach ($alternativasPorSituacao[$sid] as $alt) {
                $txt = h($alt['descricao']);
                if (intval($alt['adequada']) === 1) {
                  echo "<div class=\"alt-correta\">$txt (✔)</div>";
                } else {
                  echo "<div>$txt</div>";
                }
              }
            } else {
              echo "<div style='color:#999'>Sem alternativas</div>";
            }
          ?>
        </td>
        <td style="color:#1e8f3e;font-weight:700;">
          <?php
            $adequadaText = '—';
            if (!empty($alternativasPorSituacao[$sid])) {
              foreach ($alternativasPorSituacao[$sid] as $alt) {
                if (intval($alt['adequada']) === 1) { $adequadaText = h($alt['descricao']); break; }
              }
            }
            echo $adequadaText;
          ?>
        </td>
        <td><?= intval($sit['ordem']) ?></td>
        <td><?= h($sit['nivel']) ?></td>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<!-- paginação simples -->
<div class="pager" role="navigation" aria-label="Navegação de páginas">
  <?php if ($page > 1): ?>
    <a href="?page=<?= $page-1 ?>&per_page=<?= $perPage ?>">&laquo; Anterior</a>
  <?php else: ?>
    <span style="opacity:.5;">&laquo; Anterior</span>
  <?php endif; ?>

  <?php
    $window = 7;
    $start = max(1, $page - intval($window/2));
    $end = min($totalPages, $start + $window - 1);
    if ($end - $start + 1 < $window) $start = max(1, $end - $window + 1);
    for ($p = $start; $p <= $end; $p++):
      if ($p == $page):
  ?>
    <span class="active"><?= $p ?></span>
  <?php else: ?>
    <a href="?page=<?= $p ?>&per_page=<?= $perPage ?>"><?= $p ?></a>
  <?php endif; endfor; ?>

  <?php if ($page < $totalPages): ?>
    <a href="?page=<?= $page+1 ?>&per_page=<?= $perPage ?>">Próximo &raquo;</a>
  <?php else: ?>
    <span style="opacity:.5;">Próximo &raquo;</span>
  <?php endif; ?>
</div>

</body>
</html>

<?php
$con->close();
?>