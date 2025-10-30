<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recursos Educativos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('img/BackgroundJogo.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            color: #fff;
        }

        .voltar-icone {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 40px;
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
        }

        .voltar-icone:hover {
            transform: scale(1.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        form {
            text-align: center;
            margin-bottom: 30px;
        }

        input[type="text"] {
            padding: 10px;
            width: 300px;
            border-radius: 8px;
            border: none;
        }

        input[type="submit"] {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            background-color: #80142d;
            color: white;
            cursor: pointer;
        }

        .grid-recursos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .painel {
            background-color: rgba(0, 0, 0, 0.6);
            border: 2px solid #fff;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .painel h3 {
            margin-bottom: 10px;
        }

        .descricao {
            font-size: 14px;
            line-height: 1.4;
            position: relative;
            word-wrap: break-word;
            overflow-wrap: break-word;

        }

        .texto-curto,
        .texto-completo {
            display: block;
        }

        .texto-completo {
            display: none;
        }

        .ver-toggle {
            color: #ffd700;
            font-weight: bold;
            cursor: pointer;
            display: inline-block;
            margin-top: 5px;
            text-decoration: underline;
        }

        .painel iframe {
            width: 100%;
            height: 180px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .painel a {
            color: #ffa60d;
            text-decoration: underline;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <a href="jogo.html" class="voltar-icone">
        <i class="fas fa-circle-arrow-left"></i>
    </a>

    <h2>Recursos Educativos sobre Racismo</h2>

    <form method="GET">
        <label for="busca">Buscar por palavra:</label>
        <input type="text" id="busca" name="busca">
        <input type="submit" value="Buscar">
    </form>

    <div class="grid-recursos">
    <?php
    $con = new mysqli("localhost", "root", "", "projeto_situacoes");

    $busca = isset($_GET['busca']) ? $_GET['busca'] : '';
    $sql = "SELECT * FROM GLOSSARIO WHERE palavra LIKE ? ORDER BY id DESC";
    $stmt = $con->prepare($sql);
    $like = "%$busca%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $descricao = htmlspecialchars($row['descricao']);
            $descricao_curta = mb_substr($descricao, 0, 150);
            $tem_ver_mais = mb_strlen($descricao) > 150;

            echo "<div class='painel'>";
            echo "<h3>" . htmlspecialchars($row['palavra']) . "</h3>";
            echo "<p class='descricao'>";
            echo "<span class='texto-curto'>" . $descricao_curta . ($tem_ver_mais ? "..." : "") . "</span>";
            echo "<span class='texto-completo'>" . $descricao . "</span>";
            if ($tem_ver_mais) {
                echo "<a href='#' class='ver-toggle'>Ver mais</a>";
            }
            echo "</p>";

            if (!empty($row['sugestao'])) {
                if (strpos($row['sugestao'], 'youtube.com') !== false || strpos($row['sugestao'], 'youtu.be') !== false) {
                    preg_match('/(?:v=|\/)([a-zA-Z0-9_-]{11})/', $row['sugestao'], $matches);
                    $videoId = $matches[1] ?? '';
                    if ($videoId) {
                        echo "<iframe src='https://www.youtube.com/embed/$videoId' frameborder='0' allowfullscreen></iframe>";
                    } else {
                        echo "<a href='" . $row['sugestao'] . "' target='_blank'>Ver recurso</a>";
                    }
                } else {
                    echo "<a href='" . $row['sugestao'] . "' target='_blank'>Ver recurso</a>";
                }
            }

            echo "</div>";
        }
    } else {
        echo "<p style='text-align:center;'>Nenhum recurso encontrado.</p>";
    }

    $stmt->close();
    $con->close();
    ?>
    </div>

    <script>
    document.querySelectorAll('.ver-toggle').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const descricao = this.closest('.descricao');
            const curto = descricao.querySelector('.texto-curto');
            const completo = descricao.querySelector('.texto-completo');

            if (completo.style.display === 'none') {
                curto.style.display = 'none';
                completo.style.display = 'block';
                this.textContent = 'Ver menos';
            } else {
                curto.style.display = 'block';
                completo.style.display = 'none';
                this.textContent = 'Ver mais';
            }
        });
    });
    </script>

</body>
</html>