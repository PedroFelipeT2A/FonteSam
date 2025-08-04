<?php
require_once("admin/inc/protecao-final.php");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));
$dados_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM revendas WHERE codigo = '".$dados_stm["codigo_cliente"]."'"));

//$url_player = (!empty($dados_revenda["dominio_padrao"])) ? "playerv.".$dados_revenda["dominio_padrao"]."" : "playerv.".$dados_config["dominio_padrao"]."";

$url_player = "playerv.".$dados_config["dominio_padrao"];

if($dados_servidor["nome_principal"]) {
$servidor = code_decode($dados_servidor["nome_principal"].".".$dados_config["dominio_padrao"],"E");
} else {
$servidor = code_decode($dados_servidor["nome"].".".$dados_config["dominio_padrao"],"E");
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
<script type="text/javascript" src="/inc/javascript.js"></script>
<script type="text/javascript" src="/inc/javascript-abas.js"></script>
<script type="text/javascript">
   window.onload = function() {
	fechar_log_sistema();
   };
</script>
</head>

<body>
<div id="sub-conteudo">
  <table width="880" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
    <tr>
      <th scope="col"><div id="quadro">
          <div id="quadro-topo"><strong><?php echo $lang['lang_info_players_tab_players']; ?></strong></div>
        <div class="texto_medio" id="quadro-conteudo">
            <table width="870" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid;">
              <tr>
                <td height="30" align="center" class="texto_padrao_destaque" style="padding-left:5px;"><select name="players" class="input" id="players" style="width:98%;" onchange="window.open(this.value,'conteudo');">
                    <option value="/gerenciar-player-webrtc"><?php echo $lang['lang_info_players_player_selecione']; ?></option>
                    <option value="/gerenciar-player-webrtc"><?php echo $lang['lang_info_players_player_flash_html5']; ?></option>
                    <option value="/app-android-webrtc"><?php echo $lang['lang_info_players_player_app_android']; ?></option>
                  </select>
                </td>
              </tr>
            </table>
        </div>
      </div></th>
    </tr>
  </table>
<table width="880" border="0" align="center" cellpadding="0" cellspacing="0" style="padding-bottom:10px;">
  <tr>
    <th scope="col">
    	<div id="quadro">
        <div id="quadro-topo"><strong><?php echo $lang['lang_info_players_player_flash_html5']; ?></strong></div>
      	<div class="texto_medio" id="quadro-conteudo">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td>
                <div class="tab-pane" id="tabPane1">
                 <?php if($_POST) { ?>
                 <div class="tab-page" id="tabPage1">
                    <h2 class="tab"><?php echo $lang['lang_info_players_player_flash_html5_aba_codigo_html']; ?></h2>
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid; border-left:#CCCCCC 1px solid; border-right:#CCCCCC 1px solid;">
                      <tr>
                        <td align="left" class="texto_padrao_vermelho_destaque" style="padding:5px">
<?php
$largura = ($_POST["responsivo"] == "true") ? '100%' : $_POST["largura"]."px";
$altura = ($_POST["responsivo"] == "true") ? '100%' : $_POST["altura"]."px";
$share = ($_POST["share"]) ? "sim" : "nao";
$espectadores = ($_POST["espectadores"]) ? "sim" : "nao";
?>
URL Direta:<br />
<center><input type="text" readonly="readonly" style="width:99%; height:30px;font-size:11px" onmouseover="this.select()" value="https://<?php echo $url_player; ?>/webrtc/<?php echo $dados_stm["login"]; ?>/<?php echo $_POST["autoplay"]; ?>/<?php echo $_POST["mute"]; ?>/<?php echo $share; ?>/<?php echo $espectadores; ?>" /></center><br />
Iframe:<br />
<center><textarea readonly="readonly" style="width:99%; height:50px;font-size:11px" onmouseover="this.select()"><iframe style="width:<?php echo $largura; ?>; height:<?php echo $altura; ?>;" src="https://<?php echo $url_player; ?>/webrtc/<?php echo $dados_stm["login"]; ?>/<?php echo $_POST["autoplay"]; ?>/<?php echo $_POST["mute"]; ?>/<?php echo $share; ?>/<?php echo $espectadores; ?>" scrolling="no" frameborder="0" allowfullscreen></iframe></textarea></center>
                        </td>
                        </tr>
                    </table>
                  </div>
                <?php } ?>
                  <div class="tab-page" id="tabPage1">
                    <h2 class="tab">Player</h2>
                    <form action="/gerenciar-player-webrtc" method="post">
                    <table width="870" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid; border-left:#CCCCCC 1px solid; border-right:#CCCCCC 1px solid;">
                      <tr>
                        <td width="150" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_players_player_flash_html5_largura']; ?></td>
                        <td width="720" align="left"><input type="number" name="largura" style="width:300px" value="640" /></td>
                      </tr>
                      <tr>
                        <td height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_players_player_flash_html5_altura']; ?></td>
                        <td align="left"><input type="number" name="altura" style="width:300px" value="480" /></td>
                      </tr>
                      <tr>
                        <td height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_players_player_flash_html5_autoplay']; ?></td>
                        <td align="left">
                        <input name="autoplay" type="checkbox" value="autoplay" style="vertical-align:middle" />
                        &nbsp;<?php echo $lang['lang_label_sim']; ?>                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_players_player_flash_html5_responsivo']; ?></td>
                        <td align="left">
                        <input name="responsivo" type="checkbox" value="true" style="vertical-align:middle" />&nbsp;<?php echo $lang['lang_label_sim']; ?>&nbsp;<img src="img/icones/ajuda.gif" title="Ajuda sobre este item." width="16" height="16" onclick="alert('<?php echo $lang['lang_info_players_player_flash_html5_responsivo_info']; ?>');" style="cursor:pointer" /></td>
                      </tr>
                      <tr>
                        <td height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_players_player_flash_html5_mudo']; ?></td>
                        <td align="left">
                        <input name="mute" type="checkbox" value="muted" style="vertical-align:middle" />&nbsp;<?php echo $lang['lang_label_sim']; ?>&nbsp;<img src="img/icones/ajuda.gif" title="Ajuda sobre este item." width="16" height="16" onclick="alert('<?php echo $lang['lang_info_players_player_flash_html5_mudo_info']; ?>');" style="cursor:pointer" /></td>
                      </tr>
                      <tr>
                        <td height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Compartilhar</td>
                        <td align="left">
                        <input name="share" type="checkbox" value="sim" style="vertical-align:middle" />&nbsp;<?php echo $lang['lang_label_sim']; ?>&nbsp;<img src="img/icones/ajuda.gif" title="Ajuda sobre este item." width="16" height="16" onclick="alert('Exibir botão lateral de compartilhar o player em redes sociais');" style="cursor:pointer" /></td>
                      </tr>
                      <tr>
                        <td height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Espectadores</td>
                        <td align="left">
                        <input name="espectadores" type="checkbox" value="sim" style="vertical-align:middle" />&nbsp;<?php echo $lang['lang_label_sim']; ?>&nbsp;<img src="img/icones/ajuda.gif" title="Ajuda sobre este item." width="16" height="16" onclick="alert('Exibir icone lateral com total de espectadores assistindo o player');" style="cursor:pointer" /></td>
                      </tr>
                      <tr>
                        <td height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">&nbsp;</td>
                        <td align="left"><input type="submit" class="botao" value="OK" /></td>
                      </tr>
                    </table>
                    </form>
                  </div>
				</td>
              </tr>
            </table>
              </div>
      	</div>
      </th>
  </tr>
</table>
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