<?php
require_once("inc/conecta.php");

$sql = mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '6' ORDER by login ASC");
while ($dados_stm = mysqli_fetch_array($sql)) {

mysqli_query($conexao,"Update streamings set ftp_dir = '/home2/streaming/".$dados_stm["login"]."' where codigo = '".$dados_stm["codigo"]."'");

echo "Login ".$dados_stm["login"]."<br>";

}
echo "<br>Concluido<br>";

