<?php
ini_set("max_execution_time", 3600);

require_once("/home/painelvideo/public_html/admin/inc/conecta.php");

$query_stats = mysqli_query($conexao,"SELECT * FROM estatisticas");
while ($dados_stats = mysqli_fetch_array($query_stats)) {

$verifica_stm = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where codigo = '".$dados_stats["codigo_stm"]."'"));

// Remove os registros de streamings removidos
if($verifica_stm == 0) {
mysqli_query($conexao,"Delete From estatisticas where codigo = '".$dados_stats["codigo"]."'");
}

}

// Remove os registros anteriores a 1 ano
$data = date("Y-m-d",mktime (0, 0, 0, date("m")  , date("d")-300, date("Y")));

mysqli_query($conexao,"Delete From estatisticas WHERE data < '".$data."'");
?>