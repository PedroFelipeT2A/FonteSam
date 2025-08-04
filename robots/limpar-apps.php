<?php
ini_set("memory_limit", "128M");
ini_set("max_execution_time", 3600);

require_once("../admin/inc/conecta.php");

$query = mysqli_query($conexao,"SELECT * FROM apps");
while ($dados_app = mysqli_fetch_array($query)) {

$verifica_stm = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where codigo = '".$dados_app["codigo_stm"]."'"));

if($verifica_stm == 0) {
mysqli_query($conexao,"Delete From apps where codigo = '".$dados_dj["codigo"]."'");
@unlink("../app_android/apps/".$dados_app["zip"]."");
}

}

echo "[".date("d/m/Y H:i:s")."] Processo Concluído."
?>