<?php
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where ip = '".query_string('2')."'"));

$sql = mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".$dados_servidor["codigo"]."' ORDER by login ASC");
while ($dados_stm = mysqli_fetch_array($sql)) {

$dados_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM revendas where codigo = '".$dados_stm["codigo_cliente"]."'"));

echo $dados_stm["login"]."\n";

}

echo "playlists";

?>