<?php
require_once("inc/protecao-admin.php");
require_once("inc/classe.ssh.php");

@mysqli_query($conexao,"ALTER TABLE `servidores` ADD `instalacao_status` INT(1) NOT NULL DEFAULT '0'");
@mysqli_query($conexao,"ALTER TABLE `servidores` ADD `instalacao_porta_ssh` INT(6) NOT NULL DEFAULT '22'");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));

if($_POST) {

if(empty($_POST["nome"]) or empty($_POST["ip"]) or empty($_POST["senha"]) or empty($_POST["porta_ssh_atual"]) or empty($_POST["porta_ssh"]) or empty($_POST["porta_apache"])) {
die ("<script> alert(\"Você deixou campos em branco!\\n \\nPor favor volte e tente novamente.\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
}

//Valida DNS
$dominio_servidor = strtolower($_POST["nome"]).".".$dados_config["dominio_padrao"];
$ip_dominio_servidor = gethostbyname($dominio_servidor);

if($ip_dominio_servidor != $_POST["ip"]) {

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Ocorreu um erro! Não foi possível validar o DNS do domínio do servidor: ".$dominio_servidor."","erro");
$_SESSION["status_acao"] .= status_acao("Verifique se o domínio esta correto e se o DNS já esta propagado antes de continuar.","alerta");

header("Location: /admin/admin-instalar-servidor");
exit();
}

mysqli_query($conexao,"INSERT INTO servidores (nome,ip,senha,porta_ssh,limite_streamings,instalacao_status,instalacao_porta_ssh,status) VALUES ('".$_POST["nome"]."','".$_POST["ip"]."','".code_decode($_POST["senha"],"E")."','".$_POST["porta_ssh"]."','".$_POST["limite_streamings"]."','1','".$_POST["porta_ssh_atual"]."','off')") or die("Erro ao processar query.<br>Mensagem do servidor: ".mysqli_error($conexao));
$codigo_servidor = mysqli_insert_id($conexao);

$dados_servidor_novo = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores WHERE codigo = '".$codigo_servidor."'"));

// Conexão SSH
$ssh = new SSH();
$ssh->conectar($dados_servidor_novo["ip"],$dados_servidor_novo["instalacao_porta_ssh"]);
$ssh->autenticar("root",code_decode($dados_servidor_novo["senha"],"D"));

$ssh->enviar_arquivo("/home/painelvideo/public_html/src-instalador-almalinux/auto-instalador-wowza.sh","/home/auto-instalador-wowza.sh",0777);

$ssh->executar("dnf install epel-release -y;dnf install screen -y");

sleep(10);

$ssh->executar('echo OK;screen -dmS install bash -c "/home/auto-instalador-wowza.sh '.$_SERVER['HTTP_HOST'].' '.$dominio_servidor.' '.$user.' '.$bd_streaming.' \''.$pass.'\' '.$_POST["porta_ssh"].' '.$_POST["porta_ssh_atual"].' '.$_POST["porta_apache"].' \''.$_POST["senha"].'\'; exec sh"');

sleep(3);

$resultado = $ssh->executar("cat /home/auto-instalador-wowza.log");

if(preg_match('/INICIADO/i',$resultado)) {

$servidor_code = code_decode($dados_servidor_novo["codigo"],"E");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Instalação iniciada com sucesso! O tempo médio de instalação é de 30 minutos.","ok");
$_SESSION["status_acao"] .= status_acao("Não é necessário permanecer nesta página, você pode voltar depois para ver o progresso.","alerta");

$_SESSION["servidor"] = "Servidor: ".$dominio_servidor."";
$_SESSION["log_instalacao"] = '====================================<br />Instalação iniciada em '.date("d/m/Y H:i:s").'<br />====================================';

header("Location: /admin/admin-instalar-servidor/".$servidor_code."");
exit();
} else {

mysqli_query($conexao,"Delete From servidores where codigo = '".$dados_servidor_novo["codigo"]."'");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Ocorreu um erro! Não foi possível iniciar a Instalação.","erro");
$_SESSION["status_acao"] .= status_acao("Tente novamente revisando se os dados informados estão corretos.","alerta");
$_SESSION["status_acao"] .= status_acao("IMPORTANTE: Antes de iniciar, acesse o SSH do novo servidor e execute os comandos abaixo para prepagar o servidor para a instala&ccedil;&atilde;o: update-crypto-policies --set DEFAULT:SHA1;dnf install epel-release -y;dnf install screen -y","alerta");

header("Location: /admin/admin-instalar-servidor");
exit();
}

}

// Se estiver instalando Instalando...
if(!empty(query_string('2'))) {

$dados_servidor_instalando = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores WHERE codigo = '".code_decode(query_string('2'),"D")."'"));

$verificacao_porta_ssh = @fsockopen($dados_servidor_instalando["ip"], $dados_servidor_instalando["porta_ssh"], $errno, $errstr, 2);
@stream_set_timeout($verificacao_porta_ssh, 3);

$porta_ssh = ($verificacao_porta_ssh) ? $dados_servidor_instalando["porta_ssh"] : $dados_servidor_instalando["instalacao_porta_ssh"];

$_SESSION["servidor"] = "Servidor: ".strtolower($dados_servidor_instalando["nome"]).".".$dados_config["dominio_padrao"];

// Conexão SSH
$ssh = new SSH();
$ssh->conectar($dados_servidor_instalando["ip"],$porta_ssh);
$ssh->autenticar("root",code_decode($dados_servidor_instalando["senha"],"D"));

$resultado = $ssh->executar("cat /home/auto-instalador-wowza.log");

if(preg_match('/ERRO/i',$resultado)) {

$resultado = str_replace("**INICIADO**", "", $resultado);
$resultado = str_replace("**ERRO**", "", $resultado);
$resultado = str_replace("**CONCLUIDO**", "", $resultado);

mysqli_query($conexao,"Delete From servidores where codigo = '".$dados_servidor_instalando["codigo"]."'");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Ocorreu um erro! Não foi possível efetuar a Instalação.","erro");
$_SESSION["status_acao"] .= status_acao("Verifique log abaixo, qualquer dúvida entre em contato com suporte do módulo.","alerta");

$_SESSION["log_instalacao"] = nl2br($resultado);

header("Location: /admin/admin-instalar-servidor");
exit();
}

if(preg_match('/CONCLUIDO/i',$resultado)) {

$resultado = str_replace("**INICIADO**", "", $resultado);
$resultado = str_replace("**ERRO**", "", $resultado);
$resultado = str_replace("**CONCLUIDO**", "", $resultado);

$servidor_code = code_decode($dados_servidor_instalando["codigo"],"E");

mysqli_query($conexao,"Update servidores set instalacao_status = '0', status = 'on' where codigo = '".$dados_servidor_instalando["codigo"]."'");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Instalação concluída com sucesso! O servidor já esta pronto para uso.","ok");

header("Location: /admin/admin-servidores");
exit();
}

$resultado = str_replace("**INICIADO**", "", $resultado);
$resultado = str_replace("**ERRO**", "", $resultado);
$resultado = str_replace("**CONCLUIDO**", "", $resultado);

$_SESSION["status_acao"] = status_acao("Instalação em andamento. Por favor aguarde!","ok");
$_SESSION["status_acao"] .= status_acao("Esta página será atualizada automáticamente cada 1 minuto.","ok");
$_SESSION["log_instalacao"] = nl2br($resultado);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Streaming</title>
<meta http-equiv="cache-control" content="no-cache">
<?php if(!empty(query_string('2'))) { ?>
<meta http-equiv="Refresh" content="60">
<?php } ?>
<link rel="shortcut icon" href="/admin/img/favicon.ico" type="image/x-icon" />
<link href="/admin/inc/estilo.css" rel="stylesheet" type="text/css" />
<link href="/admin/inc/estilo-menu.css" rel="stylesheet" type="text/css" />
<link href="inc/estilo.css" rel="stylesheet" type="text/css" />
<link href="inc/estilo-menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/admin/inc/ajax.js"></script>
<script type="text/javascript" src="/admin/inc/javascript.js"></script>
</head>

<body>
<div id="topo">
<div id="topo-conteudo"><br /><center><span class="texto_titulo">Instalação Novo Servidor</span></center></div>
</div>
<div id="conteudo">
<?php
if($_SESSION['status_acao']) {

$status_acao = stripslashes($_SESSION['status_acao']);

echo '<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px">'.$status_acao.'</table>';

unset($_SESSION['status_acao']);
}
?>
<?php if(empty(query_string('2'))) { ?>
  <form method="post" action="/admin/admin-instalar-servidor" style="padding:0px; margin:0px">
    <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid;">
      <tr>
        <td width="130" height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Nome</td>
        <td align="left"><input name="nome" type="text" class="input" id="nome" style="width:100px;" placeholder="Ex: stm1" /><strong class="texto_padrao">.<?php echo $dados_config["dominio_padrao"];?></strong></td>
      </tr>
      <tr>
        <td width="130" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">IP</td>
        <td align="left"><input name="ip" type="text" class="input" id="ip" style="width:250px;" /></td>
      </tr>
      <tr>
        <td width="130" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Senha Root</td>
        <td align="left"><input name="senha" type="password" class="input" id="senha" style="width:250px;" value="" /></td>
      </tr>
      <tr>
        <td width="130" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Porta SSH Atual</td>
        <td align="left"><input name="porta_ssh_atual" type="number" class="input" id="porta_ssh_atual" style="width:250px;" value="22" /></td>
      </tr>
      <tr>
        <td width="130" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Porta SSH Nova</td>
        <td align="left"><input name="porta_ssh" type="number" class="input" id="porta_ssh" style="width:250px;" value="6985" /></td>
      </tr>
      <tr>
        <td width="130" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Porta SSL Apache</td>
        <td align="left"><input name="porta_apache" type="number" class="input" id="porta_apache" style="width:250px;" value="1443" /></td>
      </tr>
      <tr>
        <td width="130" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Limite Streamings</td>
        <td align="left"><input name="limite_streamings" type="number" class="input" id="limite_streamings" style="width:250px;" value="200" /></td>
      </tr>
      <tr>
        <td width="130" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Sistema Operacional</td>
        <td align="left" class="texto_padrao_pequeno"><input name="os" type="radio" id="os" value="almalinux9" checked="checked" />&nbsp;Alma Linux 9.x 64bits</td>
      </tr>
      <tr>
        <td width="130" height="40">&nbsp;</td>
        <td align="left"><input type="submit" class="botao" value="Instalar" onclick="abrir_log_sistema();" /></td>
      </tr>
    </table><br />
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid;">
  <tr>
    <td height="45" align="left" class="texto_padrao" style="padding:5px"><span class="texto_padrao_destaque">Instruções de Uso</span><br />
      <br />
      <strong>IMPORTANTE:</strong>Antes de iniciar, acesse o SSH do novo servidor e execute os comandos abaixo para prepagar o servidor para a instala&ccedil;&atilde;o:<br /><br />update-crypto-policies --set DEFAULT:SHA1<br>dnf install epel-release -y<br>dnf install screen -y<br><br>
      <strong>1-</strong>O DNS deve estar configurado e propagado antes de iniciar a instalação para que o SSL seja instalado corretamente.<br />
      <br /><strong>2-</strong>Preencha o formul&aacute;rio com os dados do servidor a ser instalado. Em<strong> Porta SSH Atual</strong> informe a porta padr&atilde;o assim como foi entregue o servidor pelo data center.<br />
      <br />
      <strong>3-</strong>Ap&oacute;s preencher todo o formul&aacute;rio, clique no botao instalar para iniciar e aguarde at&eacute; que seja conclu&iacute;do. Voc&ecirc; ser&aacute; informado se algum erro ocorrer.<br />
      <br />
      <strong>Importante:</strong> Não &eacute; necess&aacute;rio cadastrar o servidor, ele ser&aacute; cadastrado autom&aacute;ticamente ap&oacute;s a instalação e aparecer&aacute; na lista de servidores.</td>
  </tr>
</table>
  </form>
<?php } ?>
<?php if(!empty($_SESSION["log_instalacao"])) { ?>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid; margin-top:10px; margin-bottom: 20px">
      <tr>
        <td height="30" align="left" class="texto_padrao_pequeno" style="padding:5px;background: url('/img/ajax-loader.gif') center right no-repeat;"><strong><?php echo $_SESSION["servidor"];unset($_SESSION["servidor"]); ?></strong><br /><br />
<?php echo $_SESSION["log_instalacao"];unset($_SESSION["log_instalacao"]); ?></td>
      </tr>
    </table>
<?php } ?>
</div>
<!-- Início div log do sistema -->
<div id="log-sistema-fundo"></div>
<div id="log-sistema">
<div id="log-sistema-botao"><img src="/admin/img/icones/img-icone-fechar.png" onclick="document.getElementById('log-sistema-fundo').style.display = 'none';document.getElementById('log-sistema').style.display = 'none';" style="cursor:pointer" title="Fechar" /></div>
<div id="log-sistema-conteudo"><img src="/admin/img/ajax-loader.gif" /></div>
</div>
<!-- Fim div log do sistema -->
</body>
</html>
