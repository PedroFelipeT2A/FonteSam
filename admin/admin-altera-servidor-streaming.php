<?php
// Proteção Login
require_once("inc/protecao-admin.php");

// Proteção contra acesso direto
if(!preg_match("/".str_replace("http://","",str_replace("www.","",$_SERVER['HTTP_HOST']))."/i",$_SERVER['HTTP_REFERER'])) {
die("<span class='texto_status_erro'>Atenção! Acesso não autorizado, favor entrar em contato com nosso atendimento para maiores informações!</span>");
}

if(empty($_POST["servidor_atual"]) or empty($_POST["servidor_novo"])) {
die ("<script> alert(\"Você deixou campos em branco!\\n \\nPor favor volte e tente novamente.\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
}

$dados_servidor_atual = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$_POST["servidor_atual"]."'"));
$dados_servidor_novo = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$_POST["servidor_novo"]."'"));

$sql = mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".$_POST["servidor_atual"]."'");
while ($dados_stm = mysqli_fetch_array($sql)) {

mysqli_query($conexao,"UPDATE streamings set codigo_servidor = '".$dados_servidor_novo["codigo"]."' WHERE codigo = '".$dados_stm["codigo"]."'");

}

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Servidor alterado com sucesso para ".$dados_servidor_novo["nome"]."","ok");

header("Location: /admin/admin-streamings/resultado-servidor/".code_decode($_POST["servidor_atual"],"E")."");
?>