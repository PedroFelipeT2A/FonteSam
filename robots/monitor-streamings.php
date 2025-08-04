<?php
ini_set("max_execution_time", 1800);

require_once("".str_replace("/robots","",dirname(__FILE__))."/admin/inc/conecta.php");
require_once("".str_replace("/robots","",dirname(__FILE__))."/admin/inc/funcoes.php");
require_once("".str_replace("/robots","",dirname(__FILE__))."/admin/inc/classe.ssh.php");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));

$inicio_execucao = tempo_execucao();

parse_str($argv[1],$opcoes);

list($inicial,$final) = explode("-",$opcoes["registros"]);

echo "\n\n--------------------------------------------------------------------\n\n";

$sql = mysqli_query($conexao,"SELECT * FROM streamings where status = '1' ORDER by login ASC LIMIT ".$inicial.", ".$final."");
while ($dados_stm = mysqli_fetch_array($sql)) {

$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

if($dados_servidor["status"] == "on") {

$url_source_http = "https://".$dados_servidor["nome"].".".$dados_config["dominio_padrao"]."/".$dados_stm["login"]."/".$dados_stm["login"]."/playlist.m3u8";

$file_headers = @get_headers($url_source_http);

if($file_headers[0] == 'HTTP/1.0 404 Not Found') {
echo "[".$dados_stm["login"]."] Touch OK\n";
}

// Verifica se tem ip cameras cadastradas e conecta
$total_cameras = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM ip_cameras where codigo_stm = '".$dados_stm["codigo"]."' ORDER by codigo"));

if($total_cameras > 0) {

$sql_ip_camera = mysqli_query($conexao,"SELECT * FROM ip_cameras where codigo_stm = '".$dados_stm["codigo"]."' ORDER by codigo");
while ($dados_ip_camera = mysqli_fetch_array($sql_ip_camera)) {

// Inicia o streaming da camera
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://".$dados_servidor["ip"].":555/streammanager/streamAction?action=startStream&vhostName=_defaultVHost_&appName=".$dados_stm["login"]."%2F_definst_&streamName=".$dados_ip_camera["stream"]."&groupId=&mediaCasterType=rtp");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_USERPWD, "admin:".code_decode($dados_servidor["senha"],"D").""); 
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST); 
curl_setopt($ch, CURLOPT_USERAGENT, 'Painel de Streaming 3.0.0');
$resultado_ligar_stream = curl_exec($ch);
curl_close($ch);

echo "[".$dados_stm["login"]."] IP camera ".$dados_ip_camera["stream"]." conectada.\n";

}

} // ip cameras 

} // status servidor

} // while

$fim_execucao = tempo_execucao();

$tempo_execucao = number_format(($fim_execucao-$inicio_execucao),2);

echo "\n\n--------------------------------------------------------------------\n\n";
echo "Tempo: ".$tempo_execucao." segundo(s);\n\n";
?>
