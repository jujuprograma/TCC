<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "projeto_situacoes";

$con = new mysqli($host, $usuario, $senha, $banco);

if ($con->connect_error) {
    die("Falha na conexão: " . $con->connect_error);
}
?>
