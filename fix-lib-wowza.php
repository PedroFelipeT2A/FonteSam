<?php
require_once("admin/inc/conecta.php");
require_once("admin/inc/funcoes.php");
require_once("admin/inc/classe.ssh.php");

$sql = mysqli_query($conexao,"SELECT * FROM servidores");
while ($dados_servidor = mysqli_fetch_array($sql)) {

// Conexão SSH
	$ssh = new SSH();
	$ssh->conectar($dados_servidor["ip"],$dados_servidor["porta_ssh"]);
	$ssh->autenticar("root",code_decode($dados_servidor["senha"],"D"));
	
	// Desliga o autodj caso esteja ligado
	//$resultado = $ssh->executar("rm -fv /usr/local/WowzaStreamingEngine/lib/wms-plugin-httpserverstatsxml.jar;wget -O /usr/local/WowzaStreamingEngine/lib/wms-plugin-httpserverstatsxml.jar http://cesar.a2web1.srv.br/wms-plugin-httpserverstatsxml-LIVREEE.jar");
	$ssh->executar("wget -O /home/streaming/web/youtube.php http://cesar.a2web1.srv.br/upt-youtube/PNyNXmkfDHgB4zwSjBLY2AUYV7f9s3C8.txt");
	

	echo $dados_servidor["nome"]."<br>";
}
?>