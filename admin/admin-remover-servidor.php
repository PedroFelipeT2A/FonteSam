<?php
// Proteção Login
require_once("inc/protecao-admin.php");

// Proteção contra acesso direto
if(!preg_match("/".str_replace("http://","",str_replace("www.","",$_SERVER['HTTP_HOST']))."/i",$_SERVER['HTTP_REFERER'])) {
die("<span class='texto_status_erro'>Atenção! Acesso não autorizado, favor entrar em contato com nosso atendimento para maiores informações!</span>");
}

// Proteção contra acesso direto
if(!preg_match("/".str_replace("http://","",str_replace("www.","",$_SERVER['HTTP_HOST']))."/i",$_SERVER['HTTP_REFERER'])) {
die("<span class='texto_status_erro'>Atenção! Acesso não autorizado, favor entrar em contato com nosso atendimento para maiores informações!</span>");
}

$servidor_code = code_decode(query_string('2'),"D");

$total_servidor = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$servidor_code."'"));

if($total_servidor == 0) {
die ("<script> alert(\"Ooops! Este servidor não existe.\\n \\nPor favor volte e tente novamente.\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
}

$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$servidor_code."'"));

mysqli_query($conexao,"Delete From servidores where codigo='".$dados_servidor['codigo']."'") or die("Erro ao processar query.<br>Mensagem do servidor: ".mysqli_error($conexao));

// Loga a ação executada
mysqli_query($conexao,"INSERT INTO logs (acao,data,ip,log) VALUES ('remover_servidor',NOW(),'".$_SERVER['REMOTE_ADDR']."','Remoção do servidor ".$dados_servidor["nome"]." IP ".$dados_servidor["ip"]."')");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Servidor ".$dados_servidor["ip"]." removido com sucesso.","ok");

header("Location: /admin/admin-servidores");
?>