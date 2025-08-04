<?php
session_start();

require_once("../admin/inc/conecta.php");
require_once("../admin/inc/funcoes.php");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));

// Verifica se painel esta com manutenчуo ativada e entуo exibe a pсgina de manutenчуo
if($dados_config["manutencao"] == "sim") {

require("manutencao.php");

exit();

}

//////////////////////////////////////////////////////////////////
//////////////////////// Idioma do Painel ////////////////////////
//////////////////////////////////////////////////////////////////

if($_SESSION["login_logado"]) {

$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));

if(file_exists("../inc/lang-".$dados_stm["idioma_painel"].".php")) {
require_once("../inc/lang-".$dados_stm["idioma_painel"].".php");
} else {
require_once("../inc/lang-pt-br.php");
}

}

//////////////////////////////////////////////////////////////////
//////////////////////////// Navegaчуo ///////////////////////////
//////////////////////////////////////////////////////////////////

// Navegaчуo
$pagina = query_string('1');

if($pagina == "sair") {

$pagina = "login";

unset($_SESSION["login_logado"]);
}

if ($pagina == "") {
require("login.php");
} elseif (!file_exists($pagina.".php")) {
require("login.php");
} else {
require("".$pagina.".php");
}
?>