<?php
header("Content-Type: text/html;  charset=ISO-8859-1",true);

ini_set("memory_limit", "128M");
ini_set("max_execution_time", 600);

// Inclusão de classes
require_once("admin/inc/classe.ssh.php");
require_once("admin/inc/classe.ftp.php");

$acao = query_string('1');

// Função para pulgar um video
if($acao == "pular_video") {

	$login = code_decode(query_string('2'),"D");
	
	$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$login."'"));
	$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://".$dados_servidor["ip"].":555/streamcontrol?appName=".$dados_stm["login"]."&action=playNextPlaylistItem&streamName=".$dados_stm["login"]."");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_USERPWD, "admin:".code_decode($dados_servidor["senha"],"D").""); 
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST); 
	curl_setopt($ch, CURLOPT_USERAGENT, 'Painel de Streaming 3.0.0');
	$resultado = curl_exec($ch);

	$retry = 0;
	while((curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200 || curl_errno($ch) != 0) && $retry < 10){
	    $resultado = curl_exec($ch);
	    $retry++;
		sleep(2);
	}

	if(preg_match('/Play/i',$resultado)) {

	echo "<span class='texto_status_sucesso'>O pr&oacute;ximo video ser&aacute; iniciado em instantes.</span><br /><br/><a href='javascript:void(0);' onClick='document.getElementById(\"log-sistema-fundo\").style.display = \"none\";document.getElementById(\"log-sistema\").style.display = \"none\";' class='texto_status_atualizar'>[".$lang['lang_botao_titulo_fechar']."]</a>";

	} else {

	echo "<span class='texto_status_erro'>N&atilde;o foi poss&iacute;vel pular o video atual.</span><br /><br/><a href='javascript:void(0);' onClick='document.getElementById(\"log-sistema-fundo\").style.display = \"none\";document.getElementById(\"log-sistema\").style.display = \"none\";' class='texto_status_atualizar'>[".$lang['lang_botao_titulo_fechar']."]</a>";

	}

	curl_close($ch);

}
