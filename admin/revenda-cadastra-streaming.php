<?php
require_once("inc/protecao-revenda.php");
require_once('inc/classe.ssh.php');

// Proteção contra acesso direto
if(!preg_match("/".str_replace("http://","",str_replace("www.","",$_SERVER['HTTP_HOST']))."/i",$_SERVER['HTTP_REFERER'])) {
die("<span class='texto_status_erro'>Atenção! Acesso não autorizado, favor entrar em contato com nosso atendimento para maiores informações!</span>");
}

if(empty($_POST["bitrate"]) or empty($_POST["senha"])) {
die ("<script> alert(\"".lang_info_campos_vazios."\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
}

// Valida o login contra caracteres especiais
if(!preg_match("/^[a-z0-9]+$/", $_POST["login"])) {
die ("<script> alert(\"".lang_info_pagina_cadastrar_streaming_resultado_erro_login_invalido."\");
		 window.location = '/admin/revenda-cadastrar-streaming'; </script>");
}

// Verifica se login já esta em uso
$verificacao_login = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where login = '".strtolower($_POST["login"])."'"));

if($verificacao_login > 0) {
die ("<script> alert(\"".lang_info_pagina_cadastrar_streaming_resultado_erro_login_existente."\"); 
		 window.location = '/admin/revenda-cadastrar-streaming'; </script>");
}

// Verifica logins restritos
$array_logins_restritos = array("web", "streaming", "home");

if(in_array($_POST["login"], $array_logins_restritos)) { 
die ("<script> alert(\"Login reservado!\\n\\nLogin reserved!\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
}

// Verifica os limites do cliente
$dados_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM revendas WHERE codigo = '".$_SESSION["code_user_logged"]."'"));

$total_subrevendas = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM revendas WHERE codigo_revenda = '".$dados_revenda["codigo"]."' AND tipo = '2'"));
$total_streamings_subrevenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT SUM(streamings) as total FROM revendas WHERE codigo_revenda = '".$dados_revenda["codigo"]."' AND tipo = '2'"));
$total_streamings_revenda = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings WHERE codigo_cliente = '".$dados_revenda["codigo"]."'"));
$espectadores_subrevenda_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT SUM(espectadores) as total FROM revendas WHERE codigo_revenda = '".$dados_revenda["codigo"]."' AND tipo = '2'"));
$espectadores_stm_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT SUM(espectadores) as total FROM streamings WHERE codigo_cliente = '".$dados_revenda["codigo"]."'"));
$espaco_subrevenda_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT SUM(espaco) as total FROM revendas WHERE codigo_revenda = '".$dados_revenda["codigo"]."' AND tipo = '2'"));
$espaco_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT SUM(espaco) as total FROM streamings WHERE codigo_cliente = '".$dados_revenda["codigo"]."'"));

// Verifica se excedeu o limite de streamings do cliente
$total_streamings_revenda = $total_streamings_revenda+$total_streamings_subrevenda["total"]+1;

if($total_streamings_revenda > $dados_revenda["streamings"] && $dados_revenda["streamings"] != 999999) {
die ("<script> alert(\"".lang_info_pagina_cadastrar_streaming_resultado_alerta_limite_streamings."\"); 
		 window.location = '/admin/revenda-cadastrar-streaming'; </script>");
}

// Verifica se excedeu o limite de espectadores do cliente
$total_espectadores_revenda = $espectadores_revenda["total"]+$espectadores_subrevenda_revenda["total"]+$_POST["espectadores"];

if($total_espectadores_revenda > $dados_revenda["espectadores"] && $dados_revenda["espectadores"] != 999999) {
die ("<script> alert(\"".lang_info_pagina_cadastrar_streaming_resultado_alerta_limite_espectadores."\"); 
		 window.location = '/admin/revenda-cadastrar-streaming'; </script>");
}

// Verifica se excedeu o limite de espectadores do cliente
$total_espaco_revenda = $espaco_revenda["total"]+$espaco_subrevenda_revenda["total"]+$_POST["espaco"];

if($total_espaco_revenda > $dados_revenda["espaco"]) {
die ("<script> alert(\"".lang_info_pagina_cadastrar_streaming_resultado_alerta_limite_espaco_ftp."\"); 
		 window.location = '/admin/revenda-cadastrar-streaming'; </script>");
}

// Verifica se excedeu o limite de bitrate do cliente
if($_POST["bitrate"] > $dados_revenda["bitrate"]) {
die ("<script> alert(\"".lang_info_pagina_cadastrar_streaming_resultado_alerta_limite_bitrate."\"); 
		 window.location = '/admin/revenda-cadastrar-streaming'; </script>");
}

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));

$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_config["codigo_servidor_atual"]."'"));

$srt_status = ($_POST["srt_status"]) ? $_POST["srt_status"] : "nao";

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

mysqli_query($conexao,"INSERT INTO streamings (codigo_cliente,codigo_servidor,login,senha,senha_transmissao,autenticar_live,espectadores,bitrate,espaco,ipcameras,ftp_dir,identificacao,data_cadastro,idioma_painel,email,permitir_alterar_senha,aplicacao,exibir_app_android,srt_status,srt_porta) VALUES ('".$dados_revenda["codigo"]."','".$dados_config["codigo_servidor_atual"]."','".strtolower($_POST["login"])."','".$_POST["senha"]."','".$_POST["senha"]."','".$_POST["autenticar_live"]."','".$_POST["espectadores"]."','".$_POST["bitrate"]."','".$_POST["espaco"]."','".$_POST["ipcameras"]."','".$dados_servidor["path_home"]."/streaming/".strtolower($_POST["login"])."','".$_POST["identificacao"]."',NOW(),'".$_POST["idioma_painel"]."','".$_POST["email"]."','".$_POST["permitir_alterar_senha"]."','".$_POST["aplicacao"]."','".$_POST["exibir_app_android"]."','".$srt_status."','".$srt_porta."')") or die("Erro ao processar query.<br>Mensagem do servidor: ".mysqli_error($conexao));
$codigo_streaming = mysqli_insert_id($conexao);

// Cria o streaming no servidor Wowza

// Conexão SSH
$ssh = new SSH();
$ssh->conectar($dados_servidor["ip"],$dados_servidor["porta_ssh"]);
$ssh->autenticar("root",code_decode($dados_servidor["senha"],"D"));

if($_POST["aplicacao"] == 'tvstation') {

// Cria a home do streaming
$ssh->executar("/bin/mkdir -v ".$dados_servidor["path_home"]."/streaming/".strtolower($_POST["login"]).";/bin/chown streaming.streaming ".$dados_servidor["path_home"]."/streaming/".strtolower($_POST["login"])."");
// Copia a playlist demo para home do streaming
$ssh->executar("/bin/cp -vp ".$dados_servidor["path_home"]."/streaming/demo.mp4 ".$dados_servidor["path_home"]."/streaming/".strtolower($_POST["login"])."/;/bin/cp -vp ".$dados_servidor["path_home"]."/streaming/demo.smil ".$dados_servidor["path_home"]."/streaming/".strtolower($_POST["login"])."/playlists_agendamentos.smil");
// Configura a playlist demo
$ssh->executar("sed -i -e 's/LOGIN/".strtolower($_POST["login"])."/g' ".$dados_servidor["path_home"]."/streaming/".strtolower($_POST["login"])."/playlists_agendamentos.smil;echo OK");
}

if($_POST["aplicacao"] == 'vod') {
// Cria a home do streaming
$ssh->executar("/bin/mkdir -v ".$dados_servidor["path_home"]."/streaming/".strtolower($_POST["login"]).";/bin/chown streaming.streaming ".$dados_servidor["path_home"]."/streaming/".strtolower($_POST["login"])."");
}

$aplicacao_xml = $_POST["aplicacao"];

if($_POST["autenticar_live"] == "nao") {

if($_POST["aplicacao"] == "tvstation" || $_POST["aplicacao"] == "live") {
$aplicacao_xml = $_POST["aplicacao"].'-sem-login';
}

}

// Ativa o streaming no Wowza
$ssh->executar("/usr/local/WowzaMediaServer/ativar ".strtolower($_POST["login"])." '".$_POST["senha"]."' ".$_POST["bitrate"]." ".$_POST["espectadores"]." ".$aplicacao_xml."");
$ssh->executar("sed -i 's/\<home\>/".ltrim($dados_servidor["path_home"], '/')."/g' /usr/local/WowzaMediaServer/conf/".strtolower($_POST["login"])."/Application.xml;echo OK");



if($_POST["aplicacao"] == 'webrtc') {

if($dados_servidor["nome_principal"]) {
$servidor = $dados_servidor["nome_principal"].".".$dados_config["dominio_padrao"];
} else {
$servidor = $dados_servidor["nome"].".".$dados_config["dominio_padrao"];
}

$ssh->executar("sed -i 's/HOSTNAME/".$servidor."/g' /usr/local/WowzaStreamingEngine/conf/".strtolower($_POST["login"])."/Application.xml;echo OK");
	
$ssh->executar("/usr/bin/java -cp /usr/local/WowzaMediaServer JMXCommandLine -jmx service:jmx:rmi://localhost:8084/jndi/rmi://localhost:8085/jmxrmi -user admin -pass admin shutdownAppInstance ".$_POST["login"]."");
	
$ssh->executar("/usr/bin/java -cp /usr/local/WowzaMediaServer JMXCommandLine -jmx service:jmx:rmi://localhost:8084/jndi/rmi://localhost:8085/jmxrmi -user admin -pass admin startAppInstance ".$_POST["login"]."");
}

// Loga a ação executada
mysqli_query($conexao,"INSERT INTO logs (acao,data,ip,log) VALUES ('cadastro_streaming',NOW(),'".$_SERVER['REMOTE_ADDR']."','Cadastrado streaming ".strtolower($_POST["login"])." no servidor ".$dados_servidor["nome"]." pela revenda ".$dados_revenda["nome"]."')");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao(sprintf(lang_info_pagina_cadastrar_streaming_resultado_ok,strtolower($_POST["login"])),"ok");

echo '<script type="text/javascript">top.location = "/admin/revenda/'.code_decode(strtolower($_POST["login"]),"E").'"</script>';
?>