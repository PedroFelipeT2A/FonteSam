<?php
require_once("admin/inc/protecao-final.php");
require_once("admin/inc/classe.ftp.php");

@mysqli_query($conexao,"DROP TABLE IF EXISTS `relay_agendamentos`;
CREATE TABLE IF NOT EXISTS `relay_agendamentos` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `codigo_stm` int(10) NOT NULL,
  `servidor_relay` varchar(255) NOT NULL,
  `frequencia` int(1) NOT NULL DEFAULT 1,
  `data` date NOT NULL,
  `hora` char(2) NOT NULL,
  `minuto` char(2) NOT NULL,
  `dias` varchar(50) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `duracao` char(6) NOT NULL,
  `log_data_inicio` datetime NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=MyISAM;");

@mysqli_query($conexao,"DROP TABLE IF EXISTS `relay_agendamentos_logs`;
CREATE TABLE IF NOT EXISTS `relay_agendamentos_logs` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `codigo_agendamento` int(10) NOT NULL,
  `codigo_stm` int(10) NOT NULL,
  `data` datetime NOT NULL,
  `servidor_relay` varchar(255) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=MyISAM;");

$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));

if($_POST["cadastrar"]) {

// Verifica se o link m3u8 esta online
if(preg_match('/m3u8/i',$_POST["servidor_relay"])) {
  $file_headers = @get_headers($_POST["servidor_relay"]);

  if(!preg_match('/200 OK/i',$file_headers[0])) {
  // Cria o sessão do status das ações executadas e redireciona.
  $_SESSION["status_acao"] .= status_acao("No fue posible agregar el enlace informado, parece estar offline o bloqueado para relay.","erro");
  $_SESSION["status_acao"] .= status_acao("Enlace ".$_POST["servidor_relay"]." status ".$file_headers[0]."","alerta");

  header("Location: /gerenciar-agendamentos-relay");
  exit();
  }
}

list($hora,$minuto) = explode(":",$_POST["hora"]);

if(count($_POST["dias"]) > 0){
  $dias = implode(",",$_POST["dias"]);
}

$duracao = ($_POST["duracao"]) ? $_POST["duracao"] : "00:00";

mysqli_query($conexao,"INSERT INTO relay_agendamentos (codigo_stm,servidor_relay,frequencia,data,hora,minuto,dias,duracao) VALUES ('".$dados_stm["codigo"]."','".$_POST["servidor_relay"]."','".$_POST["frequencia"]."','".$_POST["data"]."','".sprintf("%02d",$hora)."','".sprintf("%02d",$minuto)."','".$dias.",','".$_POST["duracao"]."')");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] .= status_acao("".$lang['lang_acao_gerenciador_agendamentos_resultado_ok']."","ok");

header("Location: /gerenciar-agendamentos-relay");
exit();
}

if(query_string('1') == "remover") {

$codigo = code_decode(query_string('2'),"D");
  
  mysqli_query($conexao,"Delete From relay_agendamentos where codigo = '".$codigo."'");
  
  if(!mysqli_error($conexao)) {
  
  echo "<span class='texto_status_sucesso'>".$lang['lang_info_remover_agendamento_resultado_ok']."</span><br /><a href='javascript:window.location.reload()' class='texto_status_atualizar'>[".$lang['lang_botao_titulo_atualizar']."]</a>";
  
  } else {
  
  echo "<span class='texto_status_erro'>".$lang['lang_info_remover_agendamento_resultado_erro']." ".mysqli_error($conexao)."</span>";
  
  }
  
  exit();
}
if($_POST["remover_logs"]) {
mysqli_query($conexao,"Delete From relay_agendamentos_logs Where codigo_stm = '".$dados_stm["codigo"]."'");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] .= status_acao("Logs removidos com sucesso.","ok");

header("Location: /gerenciar-agendamentos-relay");
exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="cache-control" content="no-cache">
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
<link href="/inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<link href="inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.3/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.3/themes/material_red.css">
<script type="text/javascript" src="/inc/ajax-streaming.js"></script>
<script type="text/javascript" src="/inc/javascript.js"></script>
<script type="text/javascript" src="/inc/javascript-abas.js"></script>
<script type="text/javascript" src="/inc/sorttable.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.3/flatpickr.min.js"></script>
<script type="text/javascript">
   window.onload = function() {
  fechar_log_sistema();
   };
function valida_opcoes_frequencia_agendamento_relay( frequencia ) {
  
document.getElementById("data").disabled = true;

for(var cont = 0; cont < document.agendamentos.dias.length; cont++) {
document.agendamentos.dias[cont].disabled = true;
}

if(frequencia == "1") {
document.getElementById("data").disabled = false;
}

if(frequencia == "3") {

for(var cont = 0; cont < document.agendamentos.dias.length; cont++) {
document.agendamentos.dias[cont].disabled = false;
}

}

}
// Função para remover um agendamento de relay
function remover( codigo ) {
  
  if(codigo == "") {
  alert("Error!\n\nPortuguês: Dados faltando, tente novamente ou contate o suporte.\n\nEnglish: Missing data try again or contact support.\n\nEspañol: Los datos que faltaban inténtelo de nuevo o contacte con Atención.");
  } else {
  
  document.getElementById("log-sistema-conteudo").innerHTML = "<img src='/img/ajax-loader.gif' />";
  document.getElementById('log-sistema-fundo').style.display = "block";
  document.getElementById('log-sistema').style.display = "block";
  
  var http = new Ajax();
  http.open("GET", "/gerenciar-agendamentos-relay/remover/"+codigo , true);
  http.onreadystatechange = function() {
  
  if(http.readyState == 4) {
  
  resultado = http.responseText;
  
  document.getElementById("log-sistema-conteudo").innerHTML = resultado;  
  
  }
  
  }
  http.send(null);
  delete http;
  }
}
</script>
</head>

<body>
<div id="sub-conteudo">
<?php
if($_SESSION['status_acao']) {

$status_acao = stripslashes($_SESSION['status_acao']);

echo '<table width="890" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px">'.$status_acao.'</table>';

unset($_SESSION['status_acao']);
}
?>
<div id="quadro">
<div id="quadro-topo"><strong><?php echo $lang['lang_info_gerenciador_agendamentos_tab_titulo']; ?></strong></div>
<div class="texto_medio" id="quadro-conteudo">
  <div class="tab-pane" id="tabPane1">
      <div class="tab-page" id="tabPage1">
        <h2 class="tab"><?php echo $lang['lang_info_gerenciador_agendamentos_aba_agendamentos']; ?> Relay</h2>
  <table width="890" border="0" align="center" cellpadding="0" cellspacing="0" style="border-left:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;; border-bottom:#D5D5D5 1px solid;" id="tab" class="sortable">
    <tr style="background:url(/img/img-fundo-titulo-tabela.png) repeat-x; cursor:pointer">
      <td width="362" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;Relay</td>
      <td width="394" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;<?php echo $lang['lang_info_gerenciador_agendamentos_horario_agendado']; ?></td>
      <td width="132" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid;">&nbsp;<?php echo $lang['lang_info_gerenciador_agendamentos_executar_acao']; ?></td>
    </tr>
<?php
$total_agendamentos = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM relay_agendamentos where codigo_stm = '".$dados_stm["codigo"]."' ORDER by data"));

if($total_agendamentos > 0) {

$sql = mysqli_query($conexao,"SELECT * FROM relay_agendamentos where codigo_stm = '".$dados_stm["codigo"]."' ORDER by data");
while ($dados_agendamento = mysqli_fetch_array($sql)) {

list($ano,$mes,$dia) = explode("-",$dados_agendamento["data"]);
$data = $dia."/".$mes."/".$ano;

if($dados_agendamento["frequencia"] == "1") {
$descricao = "Executar no dia ".$data." ".sprintf("%02d",$dados_agendamento["hora"]).":".sprintf("%02d",$dados_agendamento["minuto"])."";
} elseif($dados_agendamento["frequencia"] == "2") {
$descricao = "Executar diariamente às ".sprintf("%02d",$dados_agendamento["hora"]).":".sprintf("%02d",$dados_agendamento["minuto"])."";
} else {

$array_dias = explode(",",$dados_agendamento["dias"]);

foreach($array_dias as $dia) {

if($dia == "1") {
$dia_nome = "<font color='#003399'>".$lang['lang_label_segunda']."</font>";
} elseif($dia == "2") {
$dia_nome = "<font color='#FF0000'>".$lang['lang_label_terca']."</font>";
} elseif($dia == "3") {
$dia_nome = "<font color='#FF9900'>".$lang['lang_label_quarta']."</font>";
} elseif($dia == "4") {
$dia_nome = "<font color='#CC0066'>".$lang['lang_label_quinta']."</font>";
} elseif($dia == "5") {
$dia_nome = "<font color='#009900'>".$lang['lang_label_sexta']."</font>";
} elseif($dia == "6") {
$dia_nome = "<font color='#663300'>".$lang['lang_label_sabado']."</font>";
} elseif($dia == "7") {
$dia_nome = "<font color='#663399'>".$lang['lang_label_domingo']."</font>";
} else {
$dia_nome = "";
}

$lista_dias .= "".$dia_nome.", ";

}

$descricao = "Executar ".substr($lista_dias, 0, -2)." ".sprintf("%02d",$dados_agendamento["hora"]).":".sprintf("%02d",$dados_agendamento["minuto"])."";
}

$agendamento_code = code_decode($dados_agendamento["codigo"],"E");

echo "<tr>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>&nbsp;".$dados_agendamento["servidor_relay"]."</td>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>&nbsp;".$descricao."</td>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>";

echo "<select style='width:100%' id='".$agendamento_code."' onchange='remover(this.id);'>
  <option value='' selected='selected'>".$lang['lang_info_gerenciador_agendamentos_acao']."</option>
  <option value='remover'>".$lang['lang_info_gerenciador_agendamentos_acao_remover']."</option>
</select>";

echo "</td>
</tr>";
unset($lista_dias);
unset($descricao);
}

} else {

echo "<tr>
    <td height='23' colspan='3' align='center' class='texto_padrao'>".$lang['lang_info_sem_registros']."</td>
  </tr>";

}
?>
  </table>
  </div>
      <div class="tab-page" id="tabPage2">
        <h2 class="tab"><?php echo $lang['lang_info_gerenciador_agendamentos_aba_cadastrar_agendamento']; ?> Relay</h2>
        <form method="post" action="/gerenciar-agendamentos-relay" style="padding:0px; margin:0px" name="agendamentos">
    <table width="890" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid; border-left:#CCCCCC 1px solid; border-right:#CCCCCC 1px solid;">
      <tr>
        <td width="160" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Relay RMTP/M3U8</td>
        <td width="730" align="left"><input name="servidor_relay" type="text" class="input" id="servidor_relay" style="width:350px;" placeholder="https://.......m3u8" />
        &nbsp;<img src="img/icones/ajuda.gif" title="Ajuda sobre este item." width="16" height="16" onclick="alert('Use rtmp://dominio:1935/xxx/xxx OU https://dominio/xxx/xxx/playlist.m3u8');" style="cursor:pointer" /></td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Frequência</td>
        <td align="left">
        <select name="frequencia" id="frequencia" style="width:250px;" onchange="valida_opcoes_frequencia_agendamento_relay(this.value);">
          <option value="1" selected="selected">Executar em uma data específica</option>
          <option value="2">Executar diariamente</option>
          <option value="3">Executar em dias da semana</option>
        </select>
        </td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque"><?php echo $lang['lang_info_gerenciador_agendamentos_data_inicio']; ?></td>
        <td align="left" class="texto_padrao_vermelho_destaque"><input name="data" type="date" id="data" style="width:130px;" /></td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque"><?php echo $lang['lang_info_gerenciador_agendamentos_horario_inicio']; ?></td>
        <td align="left" class="texto_padrao_pequeno"><input name="hora" type="time" id="hora" value="" style="width:130px;" /></td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Duracion</td>
        <td align="left" class="texto_padrao_pequeno"><input name="duracao" type="time" id="duracao" value="" style="width:130px;" /><?php if($dados_stm["relay_ilimitado"] == 'sim') { ?><strong>(Deje en blanco para relay directo sin fin)</strong><?php } ?></td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Dias Semana</td>
        <td align="left" valign="middle" class="texto_padrao">
        <input name="dias[]" type="checkbox" value="1" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_segunda']; ?>&nbsp;
        <input name="dias[]" type="checkbox" value="2" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_terca']; ?>&nbsp;
        <input name="dias[]" type="checkbox" value="3" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_quarta']; ?>&nbsp;
        <input name="dias[]" type="checkbox" value="4" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_quinta']; ?>&nbsp;
        <input name="dias[]" type="checkbox" value="5" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_sexta']; ?>&nbsp;
        <input name="dias[]" type="checkbox" value="6" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_sabado']; ?>&nbsp;
        <input name="dias[]" type="checkbox" value="7" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_domingo']; ?></td>
      </tr>
      <tr>
        <td height="40">&nbsp;</td>
        <td align="left">
          <input type="submit" class="botao" value="<?php echo $lang['lang_botao_titulo_cadastrar']; ?>" />
          <input name="cadastrar" type="hidden" id="cadastrar" value="sim" />          </td>
      </tr>
    </table>
    </form>
      </div>
      <div class="tab-page" id="tabPage3">
        <h2 class="tab">Logs&nbsp;<img src="/admin/img/icones/img-icone-fechar.png" onclick="document.form_remover_logs.submit();" style="cursor:pointer" title="Reset Logs" width="12" height="12" align="absmiddle" /></h2>
        <form action="/gerenciar-agendamentos-relay" method="post" name="form_remover_logs"><input name="remover_logs" type="hidden" value="sim" /></form>
        <table width="890" border="0" align="center" cellpadding="0" cellspacing="0" style="border-left:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid; border-bottom:#D5D5D5 1px solid;" id="tab2" class="sortable">
          <tr style="background:url(/img/img-fundo-titulo-tabela.png) repeat-x; cursor:pointer">
            <td width="200" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;Data</td>
            <td width="690" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid;">&nbsp;Log</td>
          </tr>
<?php
$total_logs_agendamentos = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM relay_agendamentos_logs where codigo_stm = '".$dados_stm["codigo"]."' ORDER by data"));

if($total_logs_agendamentos > 0) {

$sql = mysqli_query($conexao,"SELECT * FROM relay_agendamentos_logs WHERE codigo_stm = '".$dados_stm["codigo"]."' ORDER by data DESC LIMIT 100");
while ($dados_log_agendamento = mysqli_fetch_array($sql)) {

echo "<tr>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>&nbsp;".formatar_data($dados_stm["formato_data"], $dados_log_agendamento["data"], "America/Sao_Paulo")."</td>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>&nbsp;".$dados_log_agendamento["servidor_relay"]."</td>
</tr>";

}

} else {

echo "<tr>
    <td height='23' colspan='2' align='center' class='texto_padrao'>".$lang['lang_info_sem_registros']."</td>
  </tr>";

}
?>
        </table>
      </div>
      </div>
</div>
    </div>
</div>
<!-- Início div log do sistema -->
<div id="log-sistema-fundo"></div>
<div id="log-sistema">
<div id="log-sistema-botao"><img src="/admin/img/icones/img-icone-fechar.png" onclick="document.getElementById('log-sistema-fundo').style.display = 'none';document.getElementById('log-sistema').style.display = 'none';" style="cursor:pointer" title="<?php echo $lang['lang_titulo_fechar']; ?>" /></div>
<div id="log-sistema-conteudo"></div>
</div>
<!-- Fim div log do sistema -->
</body>
</html>
