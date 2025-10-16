<?php
// 1. Conexão
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projeto_situacoes";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Recebendo os dados do formulário
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Verificando se os dados foram preenchidos
if (empty($email) || empty($senha)) {
    header('Location: logar.html?erro=1');
    exit;
}

// Buscando o usuário ADM pelo email
$sql = "SELECT senha, adm FROM USUARIO WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($hash, $adm);
    $stmt->fetch();

    if ($adm === 'sim' && password_verify($senha, $hash)) {
        header("Location: admin.html");
    exit;
        // Aqui você pode iniciar a sessão, se quiser
        // session_start();
        // $_SESSION['usuario'] = $email;
        // header('Location: painel.php'); exit;
    } else {
        header('Location: logar.html?erro=1');
        exit;
    }
} else {
    header('Location: logar.html?erro=1');
    exit;
}

$stmt->close();
$conn->close();
?>