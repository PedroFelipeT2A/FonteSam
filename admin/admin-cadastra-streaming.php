<?php
ini_set("memory_limit", "128M");
ini_set("max_execution_time", 600);

// Inclusão de classes
require_once("inc/classe.ssh.php");

if(empty($_POST["login"]) or empty($_POST["espectadores"]) or empty($_POST["bitrate"]) or empty($_POST["senha"]) or empty($_POST["identificacao"])) {
die ("<script> alert(\"Você deixou campos em branco!\\n \\nPor favor volte e tente novamente.\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
}

// Verifica se a porta já esta em uso
$total_streamings = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where login = '".strtolower($_POST["login"])."'"));

if($total_streamings > 0) {
die ("<script> alert(\"O login ".strtolower($_POST["login"])." já esta em uso\\n \\nPor favor volte e tente novamente.\"); 
		 window.location = '/admin/admin-cadastrar-streaming'; </script>");
}

// Verifica logins restritos
$array_logins_restritos = array("web", "streaming", "home");

if(in_array($_POST["login"], $array_logins_restritos)) { 
die ("<script> alert(\"Login reservado!\\n\\nLogin reserved!\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
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

mysqli_query($conexao,"INSERT INTO streamings (codigo_cliente,codigo_servidor,login,senha,senha_transmissao,espectadores,bitrate,espaco,ftp_dir,identificacao,data_cadastro,idioma_painel,email,aplicacao,srt_status,srt_porta) VALUES ('1','".$_POST["servidor"]."','".strtolower($_POST["login"])."','".$_POST["senha"]."','".$_POST["senha"]."','".$_POST["espectadores"]."','".$_POST["bitrate"]."','".$_POST["espaco"]."','".$dados_servidor["path_home"]."/streaming/".strtolower($_POST["login"])."','".$_POST["identificacao"]."',NOW(),'".$_POST["idioma_painel"]."','".$_POST["email"]."','".$_POST["aplicacao"]."','".$srt_status."','".$srt_porta."')") or die("Erro ao processar query.<br>Mensagem do servidor: ".mysqli_error($conexao));
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

// Ativa o streaming no Wowza
$ssh->executar("/usr/local/WowzaMediaServer/ativar ".strtolower($_POST["login"])." '".$_POST["senha"]."' ".$_POST["bitrate"]." ".$_POST["espectadores"]." ".$_POST["aplicacao"]."");
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

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Streaming ".strtolower($_POST["login"])." cadastrado com sucesso.","ok");

header("Location: /admin/admin-streamings/resultado/".strtolower($_POST["login"])."");
?>