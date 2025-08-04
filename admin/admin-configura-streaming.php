<?php
ini_set("memory_limit", "128M");
ini_set("max_execution_time", 600);

require_once("inc/protecao-admin.php");
require_once("inc/classe.ssh.php");

// Proteção contra acesso direto
if(!preg_match("/".str_replace("http://","",str_replace("www.","",$_SERVER['HTTP_HOST']))."/i",$_SERVER['HTTP_REFERER'])) {
die("<span class='texto_status_erro'>Atenção! Acesso não autorizado, favor entrar em contato com nosso atendimento para maiores informações!</span>");
}

// Proteção contra usuario não logados
if(empty($_SESSION["code_user_logged"])) {
die("<span class='texto_status_erro'>0x005 - Atenção! Acesso não autorizado, favor entrar em contato com nosso atendimento para maiores informações!</span>");
}

if(empty($_POST["login"]) or empty($_POST["espectadores"]) or empty($_POST["bitrate"]) or empty($_POST["senha"])) {
die ("<script> alert(\"Você deixou campos em branco!\\n \\nPor favor volte e tente novamente.\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
}

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm_atual = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_POST["login"]."'"));
$dados_servidor_atual = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm_atual["codigo_servidor"]."'"));

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
mysqli_query($conexao,"Update streamings set codigo_cliente = '".$_POST["codigo_cliente"]."', codigo_servidor = '".$_POST["servidor"]."', senha = '".$_POST["senha"]."', senha_transmissao = '".$_POST["senha_transmissao"]."', espectadores = '".$_POST["espectadores"]."', bitrate = '".$_POST["bitrate"]."', espaco = '".$_POST["espaco"]."', identificacao = '".$_POST["identificacao"]."', email = '".$_POST["email"]."', aplicacao = '".$_POST["aplicacao"]."', autenticar_live = '".$_POST["autenticar_live"]."', srt_status = '".$srt_status."' where codigo = '".$dados_stm_atual["codigo"]."'") or die(mysqli_error($conexao));

$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$_POST["servidor"]."'"));

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


if($dados_stm_atual["codigo_servidor"] != $_POST["servidor"]) {

// Conexão SSH
$ssh2 = new SSH();
$ssh2->conectar($dados_servidor_atual["ip"],$dados_servidor_atual["porta_ssh"]);
$ssh2->autenticar("root",code_decode($dados_servidor_atual["senha"],"D"));

$ssh2->executar("/usr/local/WowzaMediaServer/desativar ".$dados_stm_atual["login"]."");

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

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] .= status_acao("Configurações do streaming ".$_POST["login"]." alteradas com sucesso.","ok");

header("Location: /admin/admin-streamings/resultado/".$_POST["login"]."");
?>