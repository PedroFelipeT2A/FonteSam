<?php
ini_set("memory_limit", "128M");
ini_set("max_execution_time", 600);

require_once("inc/protecao-admin.php");

// Proteção contra acesso direto
if(!preg_match("/".str_replace("http://","",str_replace("www.","",$_SERVER['HTTP_HOST']))."/i",$_SERVER['HTTP_REFERER'])) {
die("<span class='texto_status_erro'>Atenção! Acesso não autorizado, favor entrar em contato com nosso atendimento para maiores informações!</span>");
}

// Proteção contra usuario não logados
if(empty($_SESSION["code_user_logged"])) {
die("<span class='texto_status_erro'>0x005 - Atenção! Acesso não autorizado, favor entrar em contato com nosso atendimento para maiores informações!</span>");
}

if(empty($_POST["codigo_revenda"]) or empty($_POST["nome"]) or empty($_POST["email"]) or empty($_POST["streamings"]) or empty($_POST["espectadores"]) or empty($_POST["bitrate"]) or empty($_POST["espaco"])) {
die ("<script> alert(\"Você deixou campos em branco!\\n \\nPor favor volte e tente novamente.\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
}

$dados_revenda_atual = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM revendas WHERE codigo = '".$_POST["codigo_revenda"]."'"));

$srt_status = ($_POST["srt_status"]) ? $_POST["srt_status"] : "nao";

if($_POST["senha"]) {

mysqli_query($conexao,"Update revendas set id = '".$_POST["id"]."', nome = '".$_POST["nome"]."', email = '".$_POST["email"]."', senha = PASSWORD('".$_POST["senha"]."'), subrevendas = '".$_POST["subrevendas"]."', streamings = '".$_POST["streamings"]."', espectadores = '".$_POST["espectadores"]."', bitrate = '".$_POST["bitrate"]."', espaco = '".$_POST["espaco"]."', dominio_padrao = '".$_POST["dominio_padrao"]."', srt_status = '".$srt_status."' where codigo = '".$_POST["codigo_revenda"]."'");

} else {

mysqli_query($conexao,"Update revendas set id = '".$_POST["id"]."', nome = '".$_POST["nome"]."', email = '".$_POST["email"]."', subrevendas = '".$_POST["subrevendas"]."', streamings = '".$_POST["streamings"]."', espectadores = '".$_POST["espectadores"]."', bitrate = '".$_POST["bitrate"]."', espaco = '".$_POST["espaco"]."', dominio_padrao = '".$_POST["dominio_padrao"]."', srt_status = '".$srt_status."' where codigo = '".$_POST["codigo_revenda"]."'");

}

// Altera o bitrate dos streamings de acordo com o novo bitrate caso tenha sido alterado
if($dados_revenda_atual["bitrate"] != $_POST["bitrate"]) {

$sql_stm = mysqli_query($conexao,"SELECT * FROM streamings where codigo_cliente = '".$dados_revenda_atual["codigo"]."'");
while ($dados_stm = mysqli_fetch_array($sql_stm)) {

if($dados_stm["bitrate"] > $_POST["bitrate"]) {
mysqli_query($conexao,"Update streamings set bitrate = '".$_POST["bitrate"]."' where codigo = '".$dados_stm["codigo"]."'") or die(mysqli_error($conexao));
}

}

}

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Configurações da revenda ".$_POST["nome"]." alteradas com sucesso.","ok");

header("Location: /admin/admin-revendas");
?>