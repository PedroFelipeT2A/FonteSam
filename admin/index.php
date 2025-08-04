<?php
ini_set("date.timezone","America/Sao_Paulo");
session_set_cookie_params(0);
session_start();

header("Content-Type: text/html;  charset=ISO-8859-1",true);

// Inclusуo de classes
require_once("inc/conecta.php");
require_once("inc/funcoes.php");
require_once("inc/classe.ssh.php");
require_once("inc/classe.ftp.php");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));

//////////////////////////////////////////////////////////////////
////// Verificar se dominio tem SSL e redireciona para https /////
//////////////////////////////////////////////////////////////////

function verifica_SSL($domain){$get=@stream_context_create(array("ssl"=>array("capture_peer_cert"=>TRUE)));$read=@stream_socket_client("ssl://".$domain.":443",$errno,$errstr,30,STREAM_CLIENT_CONNECT,$get);$cert=@stream_context_get_params($read);$certinfo=@openssl_x509_parse($cert['options']['ssl']['peer_certificate']);if(is_null($certinfo)||empty($certinfo)){return false;}else{return true;}}

if($_SERVER["SERVER_PORT"] == "80" && query_string('1') != "api") {
	if(verifica_SSL($_SERVER['HTTP_HOST'])) {
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		exit();
	}
}

//////////////////////////////////////////////////////////////////
//////////////////////// Idioma do Painel ////////////////////////
//////////////////////////////////////////////////////////////////

if($_SESSION["code_user_logged"] && $_SESSION["type_logged_user"] == "cliente") {

$dados_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM revendas WHERE codigo = '".$_SESSION["code_user_logged"]."'"));

if(file_exists("inc/lang-".$dados_revenda["idioma_painel"].".php")) {
require_once("inc/lang-".$dados_revenda["idioma_painel"].".php");
} else {
require_once("inc/lang-pt-br.php");
}

}

// Inclui funчѕes gerais do sistema
if($_SESSION["type_logged_user"] == "cliente") {
require_once("funcoes-ajax-revenda.php");
} else {
require_once("funcoes-ajax.php");
}

// Verifica se painel esta com manutenчуo ativada e entуo exibe a pсgina de manutenчуo
if($dados_config["manutencao"] == "sim" && $_SESSION["type_logged_user"] == "cliente") {

require("manutencao.php");

exit();

}

//////////////////////////////////////////////////////////////////
//////////////////////////// Navegaчуo ///////////////////////////
//////////////////////////////////////////////////////////////////

$pagina = query_string('1');

if(empty($pagina) || $pagina == "sair") {

unset($_SESSION["type_logged_user"]);
unset($_SESSION["code_user_logged"]);

header("Location: http://".$_SERVER['HTTP_HOST']."/admin/login");
exit();
}

if ($pagina == "") {
require("login.php");
} elseif (!file_exists($pagina.".php")) {
require("manutencao.php");
} else {
require("".$pagina.".php");
}
?>