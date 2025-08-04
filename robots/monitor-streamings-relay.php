<?php
require_once("/home/painelvideo/public_html/admin/inc/conecta.php");
require_once("/home/painelvideo/public_html/admin/inc/classe.ssh.php");
require_once("/home/painelvideo/public_html/admin/inc/funcoes.php");

parse_str($argv[1],$opcoes);

list($inicial,$final) = explode("-",$opcoes["registros"]);

$sql = mysqli_query($conexao,"SELECT * FROM streamings where relay_status = 'sim' ORDER by login ASC LIMIT ".$inicial.", ".$final."");
while ($dados_stm = mysqli_fetch_array($sql)) {

	$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

	if($dados_servidor["status"] == "on") {

		// Verifica se foi configurado o IP e porta do streaming remoto
		if(!empty($dados_stm["relay_url"])) {

			// Verifica o status do relay, se esta ligado
			$status_streaming = status_streaming($dados_servidor["ip"],$dados_servidor["senha"],$dados_stm["login"]);

			// Se relay não estiver ligado, então reinicia o streaming
			if($status_streaming["status_transmissao"] != "relay" && $status_streaming["status_transmissao"] != "aovivo") {

				// Conexão SSH
				$ssh = new SSH();
				$ssh->conectar($dados_servidor["ip"],$dados_servidor["porta_ssh"]);
				$ssh->autenticar("root",code_decode($dados_servidor["senha"],"D"));

				// Finaliza relay atual se existir
				$ssh->executar("echo OK;screen -ls | grep -o '[0-9]*.".$dados_stm["login"]."_relay' | xargs -I{} screen -X -S {} quit");

				// Inicia o relay
				$autenticar = ($dados_stm["autenticar_live"] == "sim") ? "".$dados_stm["login"].":".$dados_stm["senha_transmissao"]."@" : "";
				$chave = ($dados_stm["aplicacao"] == 'tvstation') ? "live" : $dados_stm["login"];

				$ssh->executar('echo OK;screen -dmS '.$dados_stm["login"].'_relay bash -c \'/usr/local/bin/ffmpeg -re -reconnect 1 -reconnect_at_eof 1 -reconnect_streamed 1 -reconnect_delay_max 2 -i \''.$dados_stm["relay_url"].'\' -c:v copy -c:a copy -bsf:a aac_adtstoasc -preset medium -threads 1 -f flv \'rtmp://'.$autenticar.'localhost:1935/'.$dados_stm["login"].'/'.$chave.'\'; exec sh\'');

				echo "[".$dados_stm["login"]."]Relay reiniciado.\n";

			}

		}

	}

}
?>