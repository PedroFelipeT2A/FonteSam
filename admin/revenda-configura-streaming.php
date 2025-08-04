<?php
ini_set("memory_limit", "128M");
ini_set("max_execution_time", 600);

// Inclusão de classes
require_once("inc/protecao-revenda.php");
require_once("inc/classe.ssh.php");

// Proteção contra acesso direto
if(!preg_match("/".str_replace("http://","",str_replace("www.","",$_SERVER['HTTP_HOST']))."/i",$_SERVER['HTTP_REFERER'])) {
die("<span class='texto_status_erro'>Atenção! Acesso não autorizado, favor entrar em contato com nosso atendimento para maiores informações!</span>");
}

// Proteção contra usuario não logados
if(empty($_SESSION["code_user_logged"])) {
die("<span class='texto_status_erro'>0x005 - Atenção! Acesso não autorizado, favor entrar em contato com nosso atendimento para maiores informações!</span>");
}

if(empty($_POST["login"]) or empty($_POST["bitrate"]) or empty($_POST["senha"])) {
die ("<script> alert(\"".lang_info_campos_vazios."\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
}

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm_atual = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_POST["login"]."'"));

// Verifica os limites da revenda
$dados_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM revendas WHERE codigo = '".$_SESSION["code_user_logged"]."'"));
$dados_subrevenda_atual = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM revendas WHERE (codigo_revenda = '".$dados_revenda["codigo"]."' AND codigo = '".code_decode($_POST["codigo_subrevenda"],"D")."') AND tipo = '2'"));

$total_subrevendas = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM revendas WHERE codigo_revenda = '".$dados_revenda["codigo"]."' AND tipo = '2'"));
$total_streamings_subrevenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT SUM(streamings) as total FROM revendas WHERE codigo_revenda = '".$dados_revenda["codigo"]."' AND tipo = '2'"));
$total_streamings_revenda = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings WHERE codigo_cliente = '".$dados_revenda["codigo"]."'"));
$espectadores_subrevenda_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT SUM(espectadores) as total FROM revendas WHERE codigo_revenda = '".$dados_revenda["codigo"]."' AND tipo = '2'"));
$espectadores_stm_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT SUM(espectadores) as total FROM streamings WHERE codigo_cliente = '".$dados_revenda["codigo"]."'"));
$espaco_subrevenda_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT SUM(espaco) as total FROM revendas WHERE codigo_revenda = '".$dados_revenda["codigo"]."' AND tipo = '2'"));
$espaco_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT SUM(espaco) as total FROM streamings WHERE codigo_cliente = '".$dados_revenda["codigo"]."'"));

// Verifica se excedeu o limite de espectadores do cliente
$total_espectadores_revenda = $espectadores_revenda["total"]+$espectadores_subrevenda_revenda["total"];
$total_espectadores_revenda = $total_espectadores_revenda+$_POST["espectadores"];
$total_espectadores_revenda = $total_espectadores_revenda-$dados_stm_atual["espectadores"];

if($total_espectadores_revenda > $dados_revenda["espectadores"] && $dados_revenda["espectadores"] != 999999) {
die ("<script> alert(\"".lang_info_pagina_cadastrar_streaming_resultado_alerta_limite_espectadores."\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
}

// Verifica se excedeu o limite de espectadores do cliente
$total_espaco_revenda = $espaco_revenda["total"]+$espaco_subrevenda_revenda["total"];
$total_espaco_revenda = $total_espaco_revenda+$_POST["espaco"];
$total_espaco_revenda = $total_espaco_revenda-$dados_stm_atual["espaco"];

if($total_espaco_revenda > $dados_revenda["espaco"]) {
die ("<script> alert(\"".lang_info_pagina_cadastrar_streaming_resultado_alerta_limite_espaco_ftp."\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
}

// Verifica se excedeu o limite de bitrate do cliente
if($_POST["bitrate"] > $dados_revenda["bitrate"]) {
die ("<script> alert(\"".lang_info_pagina_cadastrar_streaming_resultado_alerta_limite_bitrate."\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
}

$srt_status = ($_POST["srt_status"]) ? $_POST["srt_status"] : "nao";

if($_POST["srt_status"] == "sim" && $dados_stm_atual["srt_status"] == "nao") {

$porta_livre_srt = false;
$nova_porta_srt = rand(20000,50000);

while(!$porta_livre_srt) {

$total_porta_livre_srt = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings WHERE srt_porta = '".$nova_porta_srt."' ORDER BY srt_porta"));

if($total_porta_livre_srt == 0) {
$porta_livre_srt = true;
} else {
$nova_porta_srt = rand(20000,50000);
$porta_livre_srt = false;
}

}

$srt_porta = $nova_porta_srt;

mysqli_query($conexao,"Update streamings set srt_porta = '".$srt_porta."' where codigo = '".$dados_stm_atual["codigo"]."'") or die(mysqli_error($conexao));
}
mysqli_query($conexao,"Update streamings set senha = '".$_POST["senha"]."', senha_transmissao = '".$_POST["senha_transmissao"]."', autenticar_live = '".$_POST["autenticar_live"]."', espectadores = '".$_POST["espectadores"]."', bitrate = '".$_POST["bitrate"]."', espaco = '".$_POST["espaco"]."', ipcameras = '".$_POST["ipcameras"]."', identificacao = '".$_POST["identificacao"]."', idioma_painel = '".$_POST["idioma_painel"]."', email = '".$_POST["email"]."', permitir_alterar_senha = '".$_POST["permitir_alterar_senha"]."', exibir_app_android = '".$_POST["exibir_app_android"]."', srt_status = '".$srt_status."' where codigo = '".$dados_stm_atual["codigo"]."'") or die(mysqli_error($conexao));

if($dados_stm_atual["senha_transmissao"] != $_POST["senha_transmissao"] || $dados_stm_atual["espectadores"] != $_POST["espectadores"] || $dados_stm_atual["bitrate"] != $_POST["bitrate"] || $dados_stm_atual["autenticar_live"] != $_POST["autenticar_live"]) {

$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm_atual["codigo_servidor"]."'"));

// Conexão SSH
$ssh = new SSH();
$ssh->conectar($dados_servidor["ip"],$dados_servidor["porta_ssh"]);
$ssh->autenticar("root",code_decode($dados_servidor["senha"],"D"));

$aplicacao_xml = ($dados_stm_atual["aplicacao"]) ? $dados_stm_atual["aplicacao"] : "tvstation";

if($_POST["autenticar_live"] == "nao" && ($dados_stm_atual["aplicacao"] == "tvstation" || $dados_stm_atual["aplicacao"] == "live")) {
$autenticar = "-sem-login";
}

if($dados_stm_atual["protecao"] == 'sim') {
$protecao = "-protecao";
}

$aplicacao_xml = ($_POST["srt_status"] == 'sim') ? "tvstation" : $aplicacao_xml;
$autenticar = ($_POST["srt_status"] == 'sim') ? "" : $autenticar;
$protecao = ($_POST["srt_status"] == 'sim') ? "" : $protecao;

$ssh->executar("/usr/local/WowzaMediaServer/sincronizar ".$dados_stm_atual["login"]." '".$_POST["senha_transmissao"]."' ".$_POST["bitrate"]." ".$_POST["espectadores"]." ".$aplicacao_xml.$autenticar.$protecao."");

if($dados_stm_atual["aplicacao"] == 'webrtc') {

if($dados_servidor["nome_principal"]) {
$servidor = $dados_servidor["nome_principal"].".".$dados_config["dominio_padrao"];
} else {
$servidor = $dados_servidor["nome"].".".$dados_config["dominio_padrao"];
}

$ssh->executar("sed -i 's/HOSTNAME/".$servidor."/g' /usr/local/WowzaStreamingEngine/conf/".strtolower($dados_stm_atual["login"])."/Application.xml;echo OK");	
}

}

if($_POST["srt_status"] == "sim") {

$ssh->executar("perl -i -p -e \"s/<Value>live<\/Value>/<Value>srt.stream<\/Value>/\" /usr/local/WowzaStreamingEngine/conf/".$dados_stm_atual["login"]."/Application.xml;echo OK");

}

$ssh->executar("/usr/bin/java -cp /usr/local/WowzaMediaServer JMXCommandLine -jmx service:jmx:rmi://localhost:8084/jndi/rmi://localhost:8085/jmxrmi -user admin -pass admin shutdownAppInstance ".$dados_stm_atual["login"]."");

$ssh->executar("/usr/bin/java -cp /usr/local/WowzaMediaServer JMXCommandLine -jmx service:jmx:rmi://localhost:8084/jndi/rmi://localhost:8085/jmxrmi -user admin -pass admin startAppInstance ".$dados_stm_atual["login"]."");

if($_POST["srt_status"] == "sim") {

$ssh->executar("mkdir /home/streaming/".$dados_stm_atual["login"].";echo 'srt://0.0.0.0:".$srt_porta."' > /home/streaming/".$dados_stm_atual["login"]."/srt.stream;echo OK");
	

	// Inicia o streaming da camera
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://".$dados_servidor["ip"].":555/streammanager/streamAction?action=startStream&vhostName=_defaultVHost_&appName=".$dados_stm_atual["login"]."%2F_definst_&streamName=srt.stream&groupId=&mediaCasterType=srt");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_USERPWD, "admin:".code_decode($dados_servidor["senha"],"D").""); 
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST); 
	curl_setopt($ch, CURLOPT_USERAGENT, 'Painel de Streaming 3.0.0');
	ob_start();
	$resultado = curl_exec($ch);
	curl_close($ch);
	
}

// Loga a ação executada
mysqli_query($conexao,"INSERT INTO logs (acao,data,ip,log) VALUES ('alterar_configuracoes_streaming',NOW(),'".$_SERVER['REMOTE_ADDR']."','Alteração nas configurações do streaming ".$dados_stm["login"]." pela revenda.')");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] .= status_acao(sprintf(lang_info_pagina_configurar_streaming_resultado_ok,$_POST["login"]),"ok");
$_SESSION["status_acao"] .= status_acao(lang_info_pagina_configurar_streaming_resultado_alerta,"alerta");

echo '<script type="text/javascript">top.location = "/admin/revenda/'.code_decode($_POST["login"],"E").'"</script>';
?>