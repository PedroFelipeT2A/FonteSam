<?php
require_once("/home/painelvideo/public_html/admin/inc/conecta.php");

// Verifica se ha espaço nos servidores, se não houver notifica o admin
$total_srv_disponivel = 0;

$query1 = mysqli_query($conexao,"SELECT * FROM servidores WHERE tipo = 'streaming'");
while ($dados_srv = mysqli_fetch_array($query1)) {

$total_stm_srv = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".$dados_srv["codigo"]."'"));

if($total_stm_srv < $dados_srv["limite_streamings"]) {
$total_srv_disponivel++;
}

}


// Atualiza servidor atual
$query = mysqli_query($conexao,"SELECT * FROM servidores WHERE status = 'on' ORDER by RAND() LIMIT 1");
while ($dados_servidor = mysqli_fetch_array($query)) {

$total_stm = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".$dados_servidor["codigo"]."'"));

if($total_stm < $dados_servidor["limite_streamings"]) {

mysqli_query($conexao,"Update configuracoes set codigo_servidor_atual = '".$dados_servidor["codigo"]."'");

}

}

?>
