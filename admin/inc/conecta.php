<?php
$host = "localhost";//nome do host
$user = "painel";//nome de usuario do mysql
$pass = "Mzc1MzIwNjU4N2R"; //senha do mysql
$bd_streaming = "video"; //nome do banco de dados

$conexao = mysqli_connect($host,$user,$pass);

mysqli_select_db($conexao,$bd_streaming);
?>
