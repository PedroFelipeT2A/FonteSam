<?php
require_once("inc/protecao-admin.php");
require_once("inc/classe.ssh.php");

@mysqli_query($conexao,"CREATE TABLE `servidores_migracao` ( `codigo` INT(10) NOT NULL AUTO_INCREMENT , `codigo_servidor` INT(10) NOT NULL , `ip` VARCHAR(255) NOT NULL , `senha` VARCHAR(255) NOT NULL , `porta_ssh` INT(10) NOT NULL , `data_inicio` DATETIME NOT NULL , `status` INT(1) NOT NULL , PRIMARY KEY (`codigo`)) ENGINE = MyISAM;");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));

if($_POST) {

if(empty($_POST["codigo_servidor"]) or empty($_POST["ip"]) or empty($_POST["senha"]) or empty($_POST["porta_ssh"])) {
die ("<script> alert(\"Você deixou campos em branco!\\n \\nPor favor volte e tente novamente.\"); 
		 window.location = 'javascript:history.back(1)'; </script>");
}

mysqli_query($conexao,"INSERT INTO servidores_migracao (codigo_servidor,ip,senha,porta_ssh,data_inicio,status) VALUES ('".$_POST["codigo_servidor"]."','".$_POST["ip"]."','".code_decode($_POST["senha"],"E")."','".$_POST["porta_ssh"]."',NOW(),'1')") or die("Erro ao processar query.<br>Mensagem do servidor: ".mysqli_error($conexao));
$codigo = mysqli_insert_id($conexao);

$dados_migracao = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores_migracao WHERE codigo = '".$codigo."'"));
$dados_servidor_migracao = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores WHERE codigo = '".$_POST["codigo_servidor"]."'"));

$dominio_servidor = strtolower($dados_servidor_migracao["nome"]).'.'.$dados_config["dominio_padrao"];

// Conexão SSH
$ssh = new SSH();
$ssh->conectar($dados_migracao["ip"],$dados_migracao["porta_ssh"]);
$ssh->autenticar("root",code_decode($dados_migracao["senha"],"D"));

$ssh->enviar_arquivo("/home/painelvideo/public_html/src-instalador-almalinux/auto-instalador-migrador-wowza.sh","/home/auto-instalador-migrador-wowza.sh",0777);

$ssh->executar("dnf install epel-release -y;dnf install screen -y");

sleep(5);

$ssh->executar('echo OK;screen -dmS install bash -c "/home/auto-instalador-migrador-wowza.sh '.$_SERVER['HTTP_HOST'].' '.$dominio_servidor.' '.$dados_migracao["porta_ssh"].' \''.code_decode($dados_servidor_migracao["senha"],"D").'\' \''.$_POST["senha"].'\' '.$dados_servidor_migracao["ip"].' '.$dados_servidor_migracao["porta_ssh"].'; exec sh"');

sleep(3);

$resultado = $ssh->executar("cat /home/auto-instalador-migrador-wowza.log");

if(preg_match('/INICIADO/i',$resultado)) {

$servidor_code = code_decode($dados_migracao["codigo"],"E");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Instalação e migração iniciadas com sucesso!","ok");
$_SESSION["status_acao"] .= status_acao("Este processo pode levar várias horas de acordo com a quantidade de músicas a serem migradas.","alerta");
$_SESSION["status_acao"] .= status_acao("Não é necessário permanecer nesta página, você pode voltar depois para ver o progresso.","alerta");

$_SESSION["servidor"] = "Servidor: ".$dominio_servidor."";
$_SESSION["log_instalacao"] = '====================================<br />Instalação e migração iniciada em '.date("d/m/Y H:i:s").'<br />====================================';

header("Location: /admin/admin-migrar-servidor/".$servidor_code."");
exit();
} else {

mysqli_query($conexao,"Delete From servidores_migracao where codigo = '".$dados_migracao["codigo"]."'");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Ocorreu um erro! Não foi possível iniciar a Instalação.","erro");
$_SESSION["status_acao"] .= status_acao("Tente novamente revisando se os dados informados estão corretos.","alerta");
$_SESSION["status_acao"] .= status_acao("IMPORTANTE: Antes de iniciar, acesse o SSH do novo servidor e execute os comandos abaixo para prepagar o servidor para a instala&ccedil;&atilde;o: update-crypto-policies --set DEFAULT:SHA1;dnf install epel-release -y;dnf install screen -y","alerta");

header("Location: /admin/admin-migrar-servidor");
exit();
}

}

// Se estiver instalando...
if(!empty(query_string('2'))) {

$dados_migracao = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores_migracao WHERE codigo = '".code_decode(query_string('2'),"D")."'"));
$dados_servidor_migracao = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores WHERE codigo = '".$dados_migracao["codigo_servidor"]."'"));

$verificacao_porta_ssh = @fsockopen($dados_migracao["ip"], $dados_migracao["porta_ssh"], $errno, $errstr, 2);
@stream_set_timeout($verificacao_porta_ssh, 3);

$porta_ssh = ($verificacao_porta_ssh) ? $dados_migracao["porta_ssh"] : $dados_servidor_migracao["porta_ssh"];

$_SESSION["servidor"] = "Servidor: ".strtolower($dados_servidor_migracao["nome"]).".".$dados_config["dominio_padrao"];

// Conexão SSH
$ssh = new SSH();
$ssh->conectar($dados_migracao["ip"],$porta_ssh);
$ssh->autenticar("root",code_decode($dados_migracao["senha"],"D"));

$resultado = $ssh->executar("cat /home/auto-instalador-migrador-wowza.log");

if(preg_match('/INICIADO-MIGRACAO/i',$resultado)) {

$total_espaco_usado_atual = $ssh->executar("cat /home/espaco-usado-atual.log | awk '{ print \$1;}'");
$total_espaco_usado_novo = $ssh->executar("cat /home/espaco-usado-novo.log | awk '{ print \$1;}'");

$estatistica = "<br /><br />======================<br />Total Migrado: ".$total_espaco_usado_novo." de ".$total_espaco_usado_atual."<br />======================<br /><br />";
}

if(preg_match('/ERRO/i',$resultado)) {

$resultado = str_replace("**INICIADO**", "", $resultado);
$resultado = str_replace("**ERRO**", "", $resultado);
$resultado = str_replace("**CONCLUIDO**", "", $resultado);

mysqli_query($conexao,"Delete From servidores_migracao where codigo = '".$dados_migracao["codigo"]."'");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Ocorreu um erro! Não foi possível efetuar a Instalação.","erro");
$_SESSION["status_acao"] .= status_acao("Verifique log abaixo, qualquer dúvida entre em contato com suporte do módulo.","alerta");

$_SESSION["log_instalacao"] = nl2br($resultado);

header("Location: /admin/admin-migrar-servidor");
exit();
}

if(preg_match('/CONCLUIDO/i',$resultado)) {

$resultado = str_replace("**INICIADO**", "", $resultado);
$resultado = str_replace("**ERRO**", "", $resultado);
$resultado = str_replace("**CONCLUIDO-INSTALACAO**", "", $resultado);
$resultado = str_replace("**CONCLUIDO**", "", $resultado);

$servidor_code = code_decode($dados_migracao["codigo"],"E");

mysqli_query($conexao,"Update servidores set senha = '".$dados_migracao["senha"]."', ip = '".$dados_migracao["ip"]."' where ip = '".$dados_servidor_migracao["ip"]."'");
mysqli_query($conexao,"Delete From servidores_migracao where codigo = '".$dados_migracao["codigo"]."'");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Instalação e migração concluídas com sucesso! O servidor já esta pronto para uso.","ok");

$_SESSION["log_instalacao"] = nl2br($resultado).$estatistica;

header("Location: /admin/admin-servidores");
exit();
}

$resultado = str_replace("**INICIADO**", "", $resultado);
$resultado = str_replace("**INICIADO-MIGRACAO**", "", $resultado);
$resultado = str_replace("**ERRO**", "", $resultado);
$resultado = str_replace("**CONCLUIDO-INSTALACAO**", "", $resultado);
$resultado = str_replace("**CONCLUIDO**", "", $resultado);

$_SESSION["status_acao"] = status_acao("Migração em andamento. Por favor aguarde!","ok");
$_SESSION["status_acao"] .= status_acao("Esta página será atualizada automáticamente cada 1 minuto.","ok");
$_SESSION["log_instalacao"] = nl2br($resultado).$estatistica;
}

$total_migracoes_ativas = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM servidores_migracao where status = '1'"));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Streaming</title>
<meta http-equiv="cache-control" content="no-cache">
<?php if(!empty(query_string('2')) && empty(query_string('3'))) { ?>
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
<div id="topo-conteudo"><br /><center><span class="texto_titulo">Migração de Servidor</span></center></div>
</div>
<div id="conteudo">
<?php
if($_SESSION['status_acao']) {

$status_acao = stripslashes($_SESSION['status_acao']);

echo '<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px">'.$status_acao.'</table>';

unset($_SESSION['status_acao']);
}
?>
<?php if($total_migracoes_ativas > 0) { ?>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style=" border-top:#D5D5D5 1px solid; border-left:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;" id="tab" class="sortable">
    <tr style="background:url(img/img-fundo-titulo-tabela.png) repeat-x; cursor:pointer">
      <td width="400" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;Servidor</td>
      <td width="200" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid;">&nbsp;Ação</td>
    </tr>
<?php
$sql_migra = mysqli_query($conexao,"SELECT * FROM servidores_migracao WHERE status = '1' ORDER by codigo ASC");
while ($dados_migracao = mysqli_fetch_array($sql_migra)) {

$dados_servidor_migracao = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores WHERE codigo = '".$dados_migracao["codigo_servidor"]."'"));

$servidor_code = code_decode($dados_migracao["codigo"],"E");

echo "<tr style='background-color:#FFFFFF;'>
<td height='25' align='left' scope='col' class='texto_padrao'>&nbsp;".$dados_servidor_migracao["nome"]."</td>
<td height='25' align='left' scope='col' class='texto_padrao'><a href='/admin/admin-migrar-servidor/".$servidor_code."' target='_blank'>[Verificar Migração]</a></td>
</tr>";

}
?>
  </table>
<?php } ?>
<?php if(empty(query_string('2'))) { ?>
  <form method="post" action="/admin/admin-migrar-servidor" style="padding:0px; margin:0px">
    <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid;">
      <tr>
        <td width="130" height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Servidor Atual</td>
        <td align="left"><select name="codigo_servidor" class="input" id="codigo_servidor" style="width:255px;">
        <option value="" selected="selected">Selecione o servidor a ser migrado</option>
<?php
$query_servidor = mysqli_query($conexao,"SELECT * FROM servidores ORDER by codigo ASC");
while ($dados_servidor = mysqli_fetch_array($query_servidor)) {

$total_streamings = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".$dados_servidor["codigo"]."'"));

echo '<option value="'.$dados_servidor["codigo"].'">'.$dados_servidor["nome"].' - '.$dados_servidor["ip"].' ('.$total_streamings.')</option>';

}
?>
        </select></td>
      </tr>
      <tr>
        <td width="130" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">IP Novo</td>
        <td align="left"><input name="ip" type="text" class="input" id="ip" style="width:250px;" /></td>
      </tr>
      <tr>
        <td width="130" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Senha Root Novo</td>
        <td align="left"><input name="senha" type="password" class="input" id="senha" style="width:250px;" value="" /></td>
      </tr>
      <tr>
        <td width="130" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Porta SSH Novo</td>
        <td align="left" class="texto_padrao_pequeno"><input name="porta_ssh" type="number" class="input" id="porta_ssh" style="width:250px;" value="22" />
          (porta ssh atual do novo servidor)</td>
      </tr>
      <tr>
        <td width="130" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Sistema Operacional</td>
        <td align="left" class="texto_padrao_pequeno"><input name="os" type="radio" id="os" value="almalinux9" checked="checked" />&nbsp;Almalinux 9.x 64bits</td>
      </tr>
      <tr>
        <td width="130" height="40">&nbsp;</td>
        <td align="left"><input type="submit" class="botao" value="Migrar Servidor" onclick="abrir_log_sistema();" /></td>
      </tr>
    </table>
    <br />
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid;">
  <tr>
    <td height="45" align="left" class="texto_padrao" style="padding:5px"><span class="texto_padrao_destaque">Instruções de Uso</span><br />
      <br />
      <strong>IMPORTANTE:</strong>Antes de iniciar, acesse o SSH do novo servidor e execute os comandos abaixo para prepagar o servidor para a instala&ccedil;&atilde;o:<br /><br />update-crypto-policies --set DEFAULT:SHA1<br>dnf install epel-release -y<br>dnf install screen -y<br><br>
      <strong>1-</strong>Selecione o servidor atual(origem) e informe os dados de acesso SSH ao novo servidor(destino). O m&oacute;dulo far&aacute; a instalação do novo servidor e logo ap&oacute;s iniciar&aacute; a migração do conte&uacute;do do servidor atual para o novo servidor.<br />
      <br /><strong>2-</strong>Ap&oacute;s preencher todo o formul&aacute;rio, clique no botao instalar para iniciar e aguarde at&eacute; que seja conclu&iacute;do. Voc&ecirc; ser&aacute; informado se algum erro ocorrer.<br />
      <br />
      <strong>Importante:</strong> Não &eacute; necess&aacute;rio cadastrar o servidor novo ou alterar os dados do servidor atual para os dados do novo servidor, ele ser&aacute; atualizado autom&aacute;ticamente ap&oacute;s a instalação.<br />
      <br />
      O processo de migração pode levar algumas horas dependendo da quantidade de videos a serem migrados. Se algum erro ocorrer voc&ecirc; ser&aacute; informado nesta p&aacute;gina e devera reinicair o processo apos formatar o novo servidor.</td>
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
