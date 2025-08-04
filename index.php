<?php
ini_set("date.timezone","America/Sao_Paulo");
session_set_cookie_params(0);
session_start();

header("Content-Type: text/html;  charset=ISO-8859-1",true);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
header('Access-Control-Allow-Credentials: true');

// Inclusуo de classes
require_once("admin/inc/conecta.php");
require_once("admin/inc/funcoes.php");
require_once("admin/inc/classe.ssh.php");
require_once("admin/inc/classe.ftp.php");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));

//////////////////////////////////////////////////////////////////
////// Verificar se dominio tem SSL e redireciona para https /////
//////////////////////////////////////////////////////////////////

function verifica_SSL($domain){$get=@stream_context_create(array("ssl"=>array("capture_peer_cert"=>TRUE)));$read=@stream_socket_client("ssl://".$domain.":443",$errno,$errstr,30,STREAM_CLIENT_CONNECT,$get);$cert=@stream_context_get_params($read);$certinfo=@openssl_x509_parse($cert['options']['ssl']['peer_certificate']);if(is_null($certinfo)||empty($certinfo)){return false;}else{return true;}}

if($_SERVER["SERVER_PORT"] == "80") {
	if(verifica_SSL($_SERVER['HTTP_HOST'])) {
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		exit();
	}
}

//////////////////////////////////////////////////////////////////
/////////////////////////// Manutenчуo ///////////////////////////
//////////////////////////////////////////////////////////////////
if($dados_config["manutencao"] == "sim" && !preg_match('/player/i',query_string('0')) ) {

require("manutencao.php");

exit();

}

//////////////////////////////////////////////////////////////////
////////////////// Idioma e TimeZone do Painel ///////////////////
//////////////////////////////////////////////////////////////////

if($_SESSION["login_logado"]) {

$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));

if(file_exists("inc/lang-".$dados_stm["idioma_painel"].".php")) {
require_once("inc/lang-".$dados_stm["idioma_painel"].".php");
} else {
require_once("inc/lang-pt-br.php");
}

}

//////////////////////////////////////////////////////////////////
//////////////////////////// Navegaчуo ///////////////////////////
//////////////////////////////////////////////////////////////////

$pagina = query_string('0');

if($pagina == "sair") {

$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));

// Insere a aчуo executada no registro de logs.
logar_acao_streaming($conexao,"".$dados_stm["codigo"]."","".$lang['lang_info_log_logout_painel']."");

$pagina = "login";

unset($_SESSION["login_logado"]);
}

if ($pagina == "") {
require("login.php");
} elseif (!file_exists($pagina.".php")) {
require("manutencao.php");
} else {
require("".$pagina.".php");
}
?>