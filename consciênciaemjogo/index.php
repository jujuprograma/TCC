
<?php
$servername = "localhost";
$username = "root"; 
$password = "";    
$dbname = "jogo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TCC</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="float">
        <h1>Bem-vindo(a)!</h1>
        EMAIL:<br>
        <input type="text" id="nome" name="nome">
        <br><br>
        SENHA:<br>
        <input type=password id="senha" name="senha">
        <br><br>
        <button id="entrar">Entrar</button>
              NÃO TEM UMA CONTA?
        <button id="criar" class="underline-btn">criar</button>
    </div>
</body>
</html>
