<?php
header("Content-Type: text/html;  charset=ISO-8859-1",true);

ini_set("memory_limit", "128M");
ini_set("max_execution_time", 600);

// Inclusão de classes
require_once("admin/inc/classe.ssh.php");
require_once("admin/inc/classe.ftp.php");

function verifica_SSL_wowza( $domain ) {
    $res = false;
    $stream = @stream_context_create( array( 'ssl' => array( 'capture_peer_cert' => true ) ) );
    $socket = @stream_socket_client( 'ssl://' . $domain . ':443', $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $stream );

    if ( $socket ) {
        $cont = stream_context_get_params( $socket );
        $cert_ressource = $cont['options']['ssl']['peer_certificate'];
        $cert = openssl_x509_parse( $cert_ressource );

        $namepart = explode( '=', $cert['name'] );
        if ( count( $namepart ) == 2 ) {
            $cert_domain = trim( $namepart[1], '*. ' );
            $check_domain = substr( $domain, -strlen( $cert_domain ) );
            $res = ($cert_domain == $check_domain);
        }
    }

    return $res;
}


$login = code_decode(query_string('1'),"D");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$login."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

if($dados_servidor["nome_principal"]) {
$servidor_wowza = strtolower($dados_servidor["nome_principal"].".".$dados_config["dominio_padrao"]);
} else {
$servidor_wowza = strtolower($dados_servidor["nome"].".".$dados_config["dominio_padrao"]);
}

// Conexão SSH
$ssh = new SSH();
$ssh->conectar($dados_servidor["ip"],$dados_servidor["porta_ssh"]);
$ssh->autenticar("root",code_decode($dados_servidor["senha"],"D"));

/////////////////////////////////////////////////////
// Verifica se streaming esta configurado no wowza //
/////////////////////////////////////////////////////

$resultado_conf_wowza = $ssh->executar("[ -f /usr/local/WowzaStreamingEngine/conf/".$dados_stm["login"]."/Application.xml ] && echo OK || echo ERRO");

if($resultado_conf_wowza == "ERRO") {

	$aplicacao = ($dados_stm["aplicacao"]) ? $dados_stm["aplicacao"] : "tvstation";
	$senha_transmissao = (empty($dados_stm["senha_transmissao"])) ? $dados_stm["senha"] : $dados_stm["senha_transmissao"];
		
	$aplicacao_xml = ($dados_stm["autenticar_live"] == "nao") ? $aplicacao.'-sem-login' : $aplicacao;
		
	$ssh->executar("/usr/local/WowzaMediaServer/sincronizar ".$dados_stm["login"]." '".$senha_transmissao."' ".$dados_stm["bitrate"]." ".$dados_stm["espectadores"]." ".$aplicacao_xml."");

	echo "<span class='texto_status_alerta'>Foram encontrados e corrigidos problemas na configura&ccedil;&atilde;o do streaming no servidor.</span><br /><br/><a href='javascript:void(0);' onClick='document.getElementById(\"log-sistema-fundo\").style.display = \"none\";document.getElementById(\"log-sistema\").style.display = \"none\";' class='texto_status_atualizar'>[".$lang['lang_botao_titulo_fechar']."]</a>";

	exit();

}

//////////////////////////////////////
// Verifica se SSL esta funcionando //
//////////////////////////////////////

if(verifica_SSL_wowza($servidor_wowza) === false) {


	echo "<span class='texto_status_alerta'>Foram encontrados problemas no SSL do servidor, por favor contate o suporte. $servidor_wowza</span><br /><br/><a href='javascript:void(0);' onClick='document.getElementById(\"log-sistema-fundo\").style.display = \"none\";document.getElementById(\"log-sistema\").style.display = \"none\";' class='texto_status_atualizar'>[".$lang['lang_botao_titulo_fechar']."]</a>";

	exit();

}

//////////////////////////////////////////////
// Verifica se o link m3u8 esta funcionando //
//////////////////////////////////////////////

$url_source = "https://".$servidor_wowza."/".$dados_stm["login"]."/".$dados_stm["login"]."/playlist.m3u8";

$file_headers = @get_headers($url_source);
if($file_headers[0] == 'HTTP/1.0 404 Not Found') {

	$aplicacao = ($dados_stm["aplicacao"]) ? $dados_stm["aplicacao"] : "tvstation";
	$senha_transmissao = (empty($dados_stm["senha_transmissao"])) ? $dados_stm["senha"] : $dados_stm["senha_transmissao"];
		
	$aplicacao_xml = ($dados_stm["autenticar_live"] == "nao") ? $aplicacao.'-sem-login' : $aplicacao;
		
	$ssh->executar("/usr/local/WowzaMediaServer/sincronizar ".$dados_stm["login"]." '".$senha_transmissao."' ".$dados_stm["bitrate"]." ".$dados_stm["espectadores"]." ".$aplicacao_xml."");

	echo "<span class='texto_status_alerta'>Foram encontrados e corrigidos problemas na configura&ccedil;&atilde;o do streaming no servidor.</span><br /><br/><a href='javascript:void(0);' onClick='document.getElementById(\"log-sistema-fundo\").style.display = \"none\";document.getElementById(\"log-sistema\").style.display = \"none\";' class='texto_status_atualizar'>[".$lang['lang_botao_titulo_fechar']."]</a>";

	exit();

}

echo "<span class='texto_status_sucesso'>Nenhum problema foi encontrato, caso tenha alguma duvida entre contato com suporte.</span><br /><br/><a href='javascript:void(0);' onClick='document.getElementById(\"log-sistema-fundo\").style.display = \"none\";document.getElementById(\"log-sistema\").style.display = \"none\";' class='texto_status_atualizar'>[".$lang['lang_botao_titulo_fechar']."]</a>";

exit();