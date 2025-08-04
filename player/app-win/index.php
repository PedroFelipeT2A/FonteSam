<?php
require_once("../../admin/inc/conecta.php");
require_once("../../admin/inc/funcoes.php");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
header('Access-Control-Allow-Credentials: true');

//////////////////////////////////////////////////////////////////
//////////////////////////// Navegaчуo ///////////////////////////
//////////////////////////////////////////////////////////////////

$pagina = query_string('1');

if (!file_exists($pagina.".php")) {
require("erro.php");
}

require("".$pagina.".php");
?>