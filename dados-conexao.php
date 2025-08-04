<?php
require_once("admin/inc/protecao-final.php");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));
$dados_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM revendas WHERE codigo = '".$dados_stm["codigo_cliente"]."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

$login_code = code_decode($dados_stm["login"],"E");

if(query_string('1') == "FMLE") {

$dominio = dominio_servidor($conexao,$dados_servidor["nome"]);
$login = $dados_stm["login"];
$stream = ($dados_stm["aplicacao"] == 'tvstation') ? "live" : $dados_stm["login"];

header('Content-disposition: attachment; filename=profile_fmle_'.$dados_stm["login"].'.xml');
header ("Content-Type:text/xml"); 
echo '<?xml version="1.0" encoding="UTF-8"?>
<flashmedialiveencoder_profile>
    <preset>
        <name>Custom</name>
        <description></description>
    </preset>
    <process>
        <video>
        <preserve_aspect></preserve_aspect>
        </video>
    </process>
	<capture>
        <video>
        <device></device>
        <crossbar_input>0</crossbar_input>
        <frame_rate>29.97</frame_rate>
        <size>
            <width></width>
            <height></height>
        </size>
        </video>
        <audio>
        <device></device>
        <crossbar_input>0</crossbar_input>
        <sample_rate>44100</sample_rate>
        <channels>2</channels>
        <input_volume>100</input_volume>
        </audio>
    </capture>
    <encode>
        <video>
        <format>H.264</format>
        <datarate>200;</datarate>
        <outputsize>320x240;</outputsize>
        <advanced>
            <profile>Baseline</profile>
            <level>3.1</level>
            <keyframe_frequency>5 Seconds</keyframe_frequency>
        </advanced>
        <autoadjust>
            <enable>false</enable>
            <maxbuffersize>1</maxbuffersize>
            <dropframes>
            <enable>false</enable>
            </dropframes>
            <degradequality>
            <enable>false</enable>
            <minvideobitrate></minvideobitrate>
            <preservepfq>false</preservepfq>
            </degradequality>
        </autoadjust>
        </video>
		<audio>
        <format>MP3</format>
        <datarate>96</datarate>
        </audio>
    </encode>
    <output>
        <rtmp>
        <url>rtmp://'.$dominio.':1935/'.$login.'</url>
        <backup_url></backup_url>
        <stream>'.$stream.'</stream>
        </rtmp>
    </output>
    <preview>
        <video>
        <input>
            <zoom>100%</zoom>
        </input>
        <output>
            <zoom>100%</zoom>
        </output>
        </video>
        <audio></audio>
    </preview>
</flashmedialiveencoder_profile>';
header("Expires: 0");
exit();
}
/////////////////////////////////////////////////
/////////////////// Idioma //////////////////////
/////////////////////////////////////////////////

if($dados_stm["idioma_painel"] == "pt-br") {

$lang[ 'lang_info_streaming_dados_conexao_info_srt' ] = 'Com o protocolo SRT n&atilde;o &eacute; necess&aacute;ria autentica&ccedil;&atilde;o.' ;

} else if($dados_stm["idioma_painel"] == "en") {

$lang[ 'lang_info_streaming_dados_conexao_servidor' ] = 'With the SRT protocol no authentication is required.' ;

} else {

$lang[ 'lang_info_streaming_dados_conexao_servidor' ] = 'Con el protocolo SRT no se requiere autenticacion.' ;

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Streaming</title>
<meta http-equiv="cache-control" content="no-cache">
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
<link href="/inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/inc/javascript.js"></script>
<script type="text/javascript" src="/inc/javascript-abas.js"></script>
<script type="text/javascript">
   window.onload = function() {
	fechar_log_sistema();
   };
function copy_text(local) {
    var copyText = document.getElementById(local);
    var textArea = document.createElement("textarea");
    textArea.value = copyText.textContent;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand("Copy");
    textArea.remove();
}
</script>
</head>

<body>
<div id="sub-conteudo-pequeno">
<div id="quadro">
<div id="quadro-topo"><strong><?php echo $lang['lang_info_streaming_dados_conexao_tab_titulo']; ?></strong></div>
<div class="texto_medio" id="quadro-conteudo">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="25" class="texto_padrao">
    <div class="tab-pane" id="tabPane1">
    <?php if($dados_stm["aplicacao"] == 'live' || $dados_stm["aplicacao"] == 'tvstation') { ?>
   	  <div class="tab-page" id="tabPage1">
       	<h2 class="tab"><?php echo $lang['lang_info_streaming_dados_conexao_aba_streaming']; ?></h2>
        <?php if($dados_stm["srt_status"] == 'nao') { ?>
        <table width="690" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid; border-left:#CCCCCC 1px solid; border-right:#CCCCCC 1px solid;">
          <tr>
            <td width="150" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_streaming_dados_conexao_servidor']; ?></td>
            <td align="left" class="texto_padrao_pequeno" id="dados_url">rtmp://<?php echo dominio_servidor($conexao,$dados_servidor["nome"]); ?>:1935/<?php echo $dados_stm["login"]; ?><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA50lEQVR42qWTPQqDMBxHf9HJ1TN0F3qCDi56AtfeQm+gt+gVXBTRoasOvUCLuHZxcFFQtGYQjCatoW/KB3k8/hCCmSiKJl3XsaWua2iahrZtb7ZtX8GBLALLsnaXcRzDNE0URYGmabiSnYA+kqnhCmRqpAT0vO97RiItoFBJlmWYBYQR3B8vtO8nV5DnObOvqgqO45DDBVuCIIDruuICuhZxOZ/g+z48z2MF4zgiSZJDBVzB3wXDMCBNUyw1IhRF4QvWQ5QqCMNwKssSXdfBMAwsNTzI/ERVVVawRvSxvg5xzbrmF1TwAVth4RFG+AZsAAAAAElFTkSuQmCC" title="Copiar/Copy" width="14" height="14" onclick="copy_text('dados_url');" style="cursor:pointer; padding-left:5px" align="absmiddle" /></td>
          </tr>
          <tr>
            <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Stream</td>
            <td align="left" class="texto_padrao_pequeno" id="dados_chave"><?php if($dados_stm["aplicacao"] == 'tvstation') { echo "live"; } else { echo $dados_stm["login"]; }?><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA50lEQVR42qWTPQqDMBxHf9HJ1TN0F3qCDi56AtfeQm+gt+gVXBTRoasOvUCLuHZxcFFQtGYQjCatoW/KB3k8/hCCmSiKJl3XsaWua2iahrZtb7ZtX8GBLALLsnaXcRzDNE0URYGmabiSnYA+kqnhCmRqpAT0vO97RiItoFBJlmWYBYQR3B8vtO8nV5DnObOvqgqO45DDBVuCIIDruuICuhZxOZ/g+z48z2MF4zgiSZJDBVzB3wXDMCBNUyw1IhRF4QvWQ5QqCMNwKssSXdfBMAwsNTzI/ERVVVawRvSxvg5xzbrmF1TwAVth4RFG+AZsAAAAAElFTkSuQmCC" title="Copiar/Copy" width="14" height="14" onclick="copy_text('dados_chave');" style="cursor:pointer; padding-left:5px" align="absmiddle" /></td>
          </tr>
          <tr>
            <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Bitrate</td>
            <td align="left" class="texto_padrao_pequeno"><?php echo $dados_stm["bitrate"]; ?> Kbps (video + audio)</td>
          </tr>
          <?php if($dados_stm["autenticar_live"] == 'sim') { ?>
          <tr>
            <td width="150" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_streaming_dados_conexao_usuario']; ?></td>
            <td align="left" class="texto_padrao_pequeno" id="dados_login"><?php echo $dados_stm["login"]; ?><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA50lEQVR42qWTPQqDMBxHf9HJ1TN0F3qCDi56AtfeQm+gt+gVXBTRoasOvUCLuHZxcFFQtGYQjCatoW/KB3k8/hCCmSiKJl3XsaWua2iahrZtb7ZtX8GBLALLsnaXcRzDNE0URYGmabiSnYA+kqnhCmRqpAT0vO97RiItoFBJlmWYBYQR3B8vtO8nV5DnObOvqgqO45DDBVuCIIDruuICuhZxOZ/g+z48z2MF4zgiSZJDBVzB3wXDMCBNUyw1IhRF4QvWQ5QqCMNwKssSXdfBMAwsNTzI/ERVVVawRvSxvg5xzbrmF1TwAVth4RFG+AZsAAAAAElFTkSuQmCC" title="Copiar/Copy" width="14" height="14" onclick="copy_text('dados_login');" style="cursor:pointer; padding-left:5px" align="absmiddle" /></td>
          </tr>
          <tr>
            <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_dados_conexao_senha']; ?></td>
            <td align="left" class="texto_padrao_pequeno" id="dados_senha"><?php echo $dados_stm["senha_transmissao"]; ?><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA50lEQVR42qWTPQqDMBxHf9HJ1TN0F3qCDi56AtfeQm+gt+gVXBTRoasOvUCLuHZxcFFQtGYQjCatoW/KB3k8/hCCmSiKJl3XsaWua2iahrZtb7ZtX8GBLALLsnaXcRzDNE0URYGmabiSnYA+kqnhCmRqpAT0vO97RiItoFBJlmWYBYQR3B8vtO8nV5DnObOvqgqO45DDBVuCIIDruuICuhZxOZ/g+z48z2MF4zgiSZJDBVzB3wXDMCBNUyw1IhRF4QvWQ5QqCMNwKssSXdfBMAwsNTzI/ERVVVawRvSxvg5xzbrmF1TwAVth4RFG+AZsAAAAAElFTkSuQmCC" title="Copiar/Copy" width="14" height="14" onclick="copy_text('dados_senha');" style="cursor:pointer; padding-left:5px" align="absmiddle" /></td>
          </tr>
          <?php } ?>
          <tr>
            <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_dados_conexao_profile_fmle']; ?></td>
            <td align="left" class="texto_padrao_pequeno"><a href="/dados-conexao/FMLE">[Download]</a><img src="img/icones/ajuda.gif" title="Ajuda sobre este item." width="16" height="16" onclick="alert('<?php echo $lang['lang_info_streaming_dados_conexao_profile_fmle_info']; ?>');" style="cursor:pointer" /></td>
          </tr>
        </table>
        <?php } else { ?>
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="padding:10px;background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid; border-left:#CCCCCC 1px solid; border-right:#CCCCCC 1px solid;">
          <tr>
            <td width="150" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo str_replace("FMS", "SRT", $lang['lang_info_streaming_dados_conexao_servidor']); ?></td>
            <td align="left" class="texto_padrao_pequeno"><span id="dados_url">srt://<?php echo dominio_servidor($conexao,$dados_servidor["nome"]); ?>:<?php echo $dados_stm["srt_porta"]; ?></span><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA50lEQVR42qWTPQqDMBxHf9HJ1TN0F3qCDi56AtfeQm+gt+gVXBTRoasOvUCLuHZxcFFQtGYQjCatoW/KB3k8/hCCmSiKJl3XsaWua2iahrZtb7ZtX8GBLALLsnaXcRzDNE0URYGmabiSnYA+kqnhCmRqpAT0vO97RiItoFBJlmWYBYQR3B8vtO8nV5DnObOvqgqO45DDBVuCIIDruuICuhZxOZ/g+z48z2MF4zgiSZJDBVzB3wXDMCBNUyw1IhRF4QvWQ5QqCMNwKssSXdfBMAwsNTzI/ERVVVawRvSxvg5xzbrmF1TwAVth4RFG+AZsAAAAAElFTkSuQmCC" title="Copiar/Copy" width="14" height="14" onclick="copy_text('dados_url');" style="cursor:pointer; padding-left:5px" align="absmiddle" /></td>
          </tr>
          <tr>
            <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque"><span id="dados_chave">Stream</span>&nbsp;</td>
            <td align="left" class="texto_padrao_pequeno"><span id="dados_chave2">live</span>&nbsp;<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA50lEQVR42qWTPQqDMBxHf9HJ1TN0F3qCDi56AtfeQm+gt+gVXBTRoasOvUCLuHZxcFFQtGYQjCatoW/KB3k8/hCCmSiKJl3XsaWua2iahrZtb7ZtX8GBLALLsnaXcRzDNE0URYGmabiSnYA+kqnhCmRqpAT0vO97RiItoFBJlmWYBYQR3B8vtO8nV5DnObOvqgqO45DDBVuCIIDruuICuhZxOZ/g+z48z2MF4zgiSZJDBVzB3wXDMCBNUyw1IhRF4QvWQ5QqCMNwKssSXdfBMAwsNTzI/ERVVVawRvSxvg5xzbrmF1TwAVth4RFG+AZsAAAAAElFTkSuQmCC" title="Copiar/Copy" width="14" height="14" onclick="copy_text('dados_chave2');" style="cursor:pointer; padding-left:5px" align="absmiddle" /></td>
          </tr>
          <tr>
            <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Bitrate</td>
            <td align="left" class="texto_padrao_pequeno"><?php echo $dados_stm["bitrate"]; ?> Kbps (video + audio)</td>
          </tr>
          <tr>
            <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">OBS Studio</td>
            <td align="left" class="texto_padrao_pequeno" height="215">
            <table width="500" border="0" cellspacing="0" cellpadding="0" style="background:url(/img/img-conf-obs-srt.jpg) no-repeat">
  <tr>
    <td height="208" align="right" valign="top" class="texto_pequeno_alerta" style="padding-right:15px;padding-top:67px" scope="col">srt://<?php echo dominio_servidor($conexao,$dados_servidor["nome"]); ?>:<?php echo $dados_stm["srt_porta"]; ?></td>
    </tr>
</table>
</td>
          </tr> 
        </table>   
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:5px;margin-left:0 auto; margin-right:0 auto; background-color: #C1E0FF; border: #006699 1px solid">
        <tr>
                    <td width="30" height="25" align="center" scope="col"><img src="img/icones/ajuda.gif" width="16" height="16" /></td>
                    <td align="left" class="texto_padrao_destaque" scope="col"><?php echo $lang['lang_info_streaming_dados_conexao_info_srt']; ?></td>
            </tr>
        </table>          
        <?php } ?>
      </div>
      <?php } ?>
      <?php if($dados_stm["aplicacao"] == 'tvstation' || $dados_stm["aplicacao"] == 'vod') { ?>
      <div class="tab-page" id="tabPage2">
       	<h2 class="tab"><?php echo $lang['lang_info_streaming_dados_conexao_aba_ftp']; ?></h2>
        <table width="690" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid; border-left:#CCCCCC 1px solid; border-right:#CCCCCC 1px solid;">
          <tr>
            <td width="150" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Servidor/Server/Host</td>
            <td align="left" class="texto_padrao_pequeno" id="dados_ftp_url"><?php echo dominio_servidor($conexao,$dados_servidor["nome"]); ?><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA50lEQVR42qWTPQqDMBxHf9HJ1TN0F3qCDi56AtfeQm+gt+gVXBTRoasOvUCLuHZxcFFQtGYQjCatoW/KB3k8/hCCmSiKJl3XsaWua2iahrZtb7ZtX8GBLALLsnaXcRzDNE0URYGmabiSnYA+kqnhCmRqpAT0vO97RiItoFBJlmWYBYQR3B8vtO8nV5DnObOvqgqO45DDBVuCIIDruuICuhZxOZ/g+z48z2MF4zgiSZJDBVzB3wXDMCBNUyw1IhRF4QvWQ5QqCMNwKssSXdfBMAwsNTzI/ERVVVawRvSxvg5xzbrmF1TwAVth4RFG+AZsAAAAAElFTkSuQmCC" title="Copiar/Copy" width="14" height="14" onclick="copy_text('dados_ftp_url');" style="cursor:pointer; padding-left:5px" align="absmiddle" /></td>
          </tr>
          <tr>
            <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_dados_conexao_usuario']; ?></td>
            <td align="left" class="texto_padrao_pequeno" id="dados_ftp_login"><?php echo $dados_stm["login"]; ?><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA50lEQVR42qWTPQqDMBxHf9HJ1TN0F3qCDi56AtfeQm+gt+gVXBTRoasOvUCLuHZxcFFQtGYQjCatoW/KB3k8/hCCmSiKJl3XsaWua2iahrZtb7ZtX8GBLALLsnaXcRzDNE0URYGmabiSnYA+kqnhCmRqpAT0vO97RiItoFBJlmWYBYQR3B8vtO8nV5DnObOvqgqO45DDBVuCIIDruuICuhZxOZ/g+z48z2MF4zgiSZJDBVzB3wXDMCBNUyw1IhRF4QvWQ5QqCMNwKssSXdfBMAwsNTzI/ERVVVawRvSxvg5xzbrmF1TwAVth4RFG+AZsAAAAAElFTkSuQmCC" title="Copiar/Copy" width="14" height="14" onclick="copy_text('dados_ftp_login');" style="cursor:pointer; padding-left:5px" align="absmiddle" /></td>
          </tr>
          <tr>
            <td width="150" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_streaming_dados_conexao_senha']; ?></td>
            <td align="left" class="texto_padrao_pequeno" id="dados_ftp_senha"><?php echo $dados_stm["senha"]; ?><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAA50lEQVR42qWTPQqDMBxHf9HJ1TN0F3qCDi56AtfeQm+gt+gVXBTRoasOvUCLuHZxcFFQtGYQjCatoW/KB3k8/hCCmSiKJl3XsaWua2iahrZtb7ZtX8GBLALLsnaXcRzDNE0URYGmabiSnYA+kqnhCmRqpAT0vO97RiItoFBJlmWYBYQR3B8vtO8nV5DnObOvqgqO45DDBVuCIIDruuICuhZxOZ/g+z48z2MF4zgiSZJDBVzB3wXDMCBNUyw1IhRF4QvWQ5QqCMNwKssSXdfBMAwsNTzI/ERVVVawRvSxvg5xzbrmF1TwAVth4RFG+AZsAAAAAElFTkSuQmCC" title="Copiar/Copy" width="14" height="14" onclick="copy_text('dados_ftp_senha');" style="cursor:pointer; padding-left:5px" align="absmiddle" /></td>
          </tr>
          <tr>
            <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_dados_conexao_ftp_porta']; ?></td>
            <td align="left" class="texto_padrao_pequeno">21</td>
          </tr>
        </table>
      </div>
      <?php } ?>
      </div></td>
  </tr>
</table>
    </div>
      </div>
<br />
<br />
</div>
<!-- Início div log do sistema -->
<div id="log-sistema-fundo"></div>
<div id="log-sistema">
<div id="log-sistema-botao"><img src="img/icones/img-icone-fechar.png" onclick="document.getElementById('log-sistema-fundo').style.display = 'none';document.getElementById('log-sistema').style.display = 'none';" style="cursor:pointer" title="<?php echo $lang['lang_titulo_fechar']; ?>" /></div>
<div id="log-sistema-conteudo"></div>
</div>
<!-- Fim div log do sistema -->
</body>
</html>