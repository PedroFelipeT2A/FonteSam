<?php
require_once("admin/inc/protecao-final.php");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));

$url_player = (!empty($dados_revenda["dominio_padrao"])) ? "playerv.".$dados_revenda["dominio_padrao"]."" : "playerv.".$dados_config["dominio_padrao"]."";

if($_POST["acao_form"] == "configurar") {

if($_FILES["logo"]["tmp_name"]) {
@copy($_FILES["logo"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/player/app-win/logo-".$dados_stm["login"].".png");
}
if($_FILES["fundo"]["tmp_name"]) {
@copy($_FILES["fundo"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/player/app-win/background-".$dados_stm["login"].".jpg");
}

$app_win_url_chat = ($_POST["ativar_chat"]) ? $_POST["app_win_url_chat"] : "";

$app_win_whatsapp = str_replace("+", "", $_POST["app_win_whatsapp"]);
$app_win_whatsapp = str_replace(" ", "", $_POST["app_win_whatsapp"]);
$app_win_whatsapp = str_replace("(", "", $_POST["app_win_whatsapp"]);
$app_win_whatsapp = str_replace(")", "", $_POST["app_win_whatsapp"]);

// Atualiza configuracoes do app com logo e fundo
mysqli_query($conexao,"Update streamings set app_win_nome = '".$_POST["app_win_nome"]."', app_win_email = '".$_POST["app_win_email"]."', app_win_whatsapp = '".$app_win_whatsapp."', app_win_url_facebook = '".$_POST["app_win_url_facebook"]."', app_win_url_instagram = '".$_POST["app_win_url_instagram"]."', app_win_url_twitter = '".$_POST["app_win_url_twitter"]."', app_win_url_site = '".$_POST["app_win_url_site"]."', app_win_cor_texto = '".$_POST["app_win_cor_texto"]."', app_win_cor_menu_claro = '".$_POST["app_win_cor_menu_claro"]."', app_win_cor_menu_escuro = '".$_POST["app_win_cor_menu_escuro"]."', app_win_url_logo = '/app-win/logo-".$dados_stm["login"].".png', app_win_url_background = '/app-win/background-".$dados_stm["login"].".jpg', app_win_url_chat = '".$app_win_url_chat."', app_win_text_prog = '".$_POST["app_win_text_prog"]."', app_win_text_hist = '".$_POST["app_win_text_hist"]."', app_win_url_youtube = '".$_POST["app_win_url_youtube"]."' where codigo = '".$dados_stm["codigo"]."'");

$_SESSION["status_acao"] = status_acao($lang['lang_info_config_painel_resultado_ok'],"ok");

header("Location: /app-windows");
exit();
}

/////////////////////////////////////////////////
/////////////////// Idioma //////////////////////
/////////////////////////////////////////////////

if($dados_stm["idioma_painel"] == "pt-br") {

$lang[ 'lang_info_streaming_app_android_tab_app_pronto' ] = 'Prévia App' ;
$lang[ 'lang_info_streaming_app_android_tab_configurar_app' ] = 'Configurar App' ;
$lang[ 'lang_info_streaming_app_android_info_configuracoes' ] = 'Ao alterar as configurações abaixo, as mesmas serão atualizadas automaticamente no app sem precisar reinstalar.' ;
$lang[ 'lang_info_streaming_app_android_info_desativar_campo' ] = '(deixe este campo em branco para desativa-lo)';
$lang[ 'lang_info_streaming_app_android_app_cor_texto' ] = 'Cor Texto';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_claro' ] = 'Cor Menu Claro';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_escuro' ] = 'Cor Menu Escuro';
$lang[ 'lang_info_streaming_app_android_info_instalar_app' ] = 'Bot&atilde;o Instala&ccedil;&atilde;o:';
$lang[ 'lang_info_streaming_app_android_info_instalar_app2' ] = 'Coloque o c&oacute;digo acima em seu site, ao clicar, ser&aacute; aberto o app no navegador do ouvinte e ser&aacute; exibido uma mensagem para instala&ccedil;&atilde;o do app.';

} else if($dados_stm["idioma_painel"] == "en") {

$lang[ 'lang_info_streaming_app_android_tab_app_pronto' ] = 'App Preview' ;
$lang[ 'lang_info_streaming_app_android_tab_configurar_app' ] = 'Configure App' ;
$lang[ 'lang_info_streaming_app_android_info_configuracoes' ] = 'When changing the settings below, they will be updated automatically in the app without having to install again.' ;
$lang[ 'lang_info_streaming_app_android_info_desativar_campo' ] = '(leave empty to disable displaying)';
$lang[ 'lang_info_streaming_app_android_app_cor_texto' ] = 'Text Color';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_claro' ] = 'Menu Color Light';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_escuro' ] = 'Menu Color Dark';
$lang[ 'lang_info_streaming_app_android_info_instalar_app' ] = 'Install Button:';
$lang[ 'lang_info_streaming_app_android_info_instalar_app2' ] = 'Place the code above on your website, when clicking, it will be open the app in the listener\'s browser and a message is displayed to install the app.';

} else {

$lang[ 'lang_info_streaming_app_android_tab_app_pronto' ] = 'Vista Previa App' ;
$lang[ 'lang_info_streaming_app_android_tab_configurar_app' ] = 'Configurar App' ;
$lang[ 'lang_info_streaming_app_android_info_configuracoes' ] = 'Al cambiar la configuración a continuación, los datos se actualizarán automáticamente en la aplicación sin tener que instalar de nuevo.' ;
$lang[ 'lang_info_streaming_app_android_info_desativar_campo' ] = '(deje este campo en blanco para deshabilitarlo)';
$lang[ 'lang_info_streaming_app_android_app_cor_texto' ] = 'Color Texto';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_claro' ] = 'Color Menu Claro';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_escuro' ] = 'Color Menu Oscuro';
$lang[ 'lang_info_streaming_app_android_info_instalar_app' ] = 'Botón de Instalación:';
$lang[ 'lang_info_streaming_app_android_info_instalar_app2' ] = 'Coloque el c&oacute;digo anterior en su sitio web, al hacer clic, abra la aplicaci&oacute;n en el navegador del oyente y se muestra un mensaje para instalar la aplicaci&oacute;n.';

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
<link href="inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="inc/ajax-streaming.js"></script>
<script type="text/javascript" src="inc/javascript.js"></script>
<script type="text/javascript" src="inc/javascript-abas.js"></script>
<script type="text/javascript" src="/admin/inc/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
   window.onload = function() {
	fechar_log_sistema();
   };
   function abrir_log_sistema_app() {
	
	window.parent.document.getElementById('log-sistema-conteudo').innerHTML = "<img src='/img/ajax-loader.gif' />";
	window.parent.document.getElementById('log-sistema-fundo').style.display = "block";
	window.parent.document.getElementById('log-sistema').style.display = "block";

}
</script>
</head>

<body>
<div id="sub-conteudo">
  <?php
if($_SESSION['status_acao']) {

$status_acao = stripslashes($_SESSION['status_acao']);

echo '<table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px">'.$status_acao.'</table>';

unset($_SESSION['status_acao']);
}
?>
    <div id="quadro">
      <div id="quadro-topo"><strong>App Windows</strong></div>
      <div class="texto_medio" id="quadro-conteudo">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td height="25"><div class="tab-pane" id="tabPane1">
            <?php if(!empty($dados_stm["app_win_nome"]) && !empty($dados_stm["app_win_url_logo"])) { ?>
              <div class="tab-page" id="tabPage1">
                <h2 class="tab"><?php echo $lang['lang_info_streaming_app_android_tab_app_pronto']; ?></h2>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="65%" height="400" align="center"><iframe src="https://<?php echo $url_player; ?>/player-app-win/<?php echo $dados_stm["login"]; ?>?app-win=preview" style="width:99%; height:350px" frameborder="0" onmousewheel=""></iframe></td>
    <td width="35%" align="center" style="padding:10px"><span class="texto_padrao_vermelho_destaque"><?php echo $lang['lang_info_streaming_app_android_info_instalar_app']; ?></span><br />
      <br /><a href="https://<?php echo $url_player; ?>/player-app-win/<?php echo $dados_stm["login"]; ?>" target="_blank"><img src="https://<?php echo $url_player; ?>/app-win/img-instalar-app.png" width="150" height="48" /></a><br />
        <br /><textarea name="textarea" readonly="readonly" style="width:95%; height:70px;font-size:11px" onmouseover="this.select()"><a href="https://<?php echo $url_player; ?>/player-app-win/<?php echo $dados_stm["login"]; ?>"><img src="https://<?php echo $url_player; ?>/app-win/img-instalar-app.png" width="150" height="48" /></a></textarea><br /><br /><span class="texto_padrao_pequeno"><?php echo $lang['lang_info_streaming_app_android_info_instalar_app2']; ?></span></td>
  </tr>
</table></div>
<?php } ?>
                <div class="tab-page" id="tabPage2">
                <h2 class="tab"><?php echo $lang['lang_info_streaming_app_android_tab_configurar_app']; ?></h2>
  <form method="post" action="/app-windows" style="padding:0px; margin:0px" enctype="multipart/form-data">
      <table width="890" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px; background-color: #C1E0FF; border: #006699 1px solid">
      <tr>
        <td width="30" height="25" align="center" scope="col"><img src="/img/icones/ajuda.gif" width="16" height="16" /></td>
        <td width="860" align="left" class="texto_padrao" scope="col"><?php echo $lang['lang_info_streaming_app_android_info_configuracoes']; ?></td>
      </tr>
    </table>
     <table width="100%" border="0" cellpadding="0" cellspacing="0">
     <tr>
      <td width="21%" height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_tv_nome']; ?></td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_win_nome" type="text" id="app_win_nome" style="width:350px" value="<?php echo $dados_stm["app_win_nome"]; ?>" />
        <br /></td>
    </tr>
    <tr>
      <td width="21%" height="50" class="texto_padrao_destaque">E-mail</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_win_email" type="text" id="app_win_email" style="width:350px" value="<?php echo $dados_stm["app_win_email"]; ?>" /></td>
    </tr>
    <tr>
      <td width="21%" height="50" class="texto_padrao_destaque">Site</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_win_url_site" type="text" id="app_win_url_site" style="width:350px" value="<?php echo $dados_stm["app_win_url_site"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>
    <tr>
      <td width="21%" height="50" class="texto_padrao_destaque">FaceBook</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_win_url_facebook" type="text" id="app_win_url_facebook" style="width:350px" value="<?php echo $dados_stm["app_win_url_facebook"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>
    <tr>
      <td width="21%" height="50" class="texto_padrao_destaque">Twitter</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_win_url_twitter" type="text" id="app_win_url_twitter" style="width:350px" value="<?php echo $dados_stm["app_win_url_twitter"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>    
    <tr>
      <td width="21%" height="50" class="texto_padrao_destaque">Instagram</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_win_url_instagram" type="text" id="app_win_url_instagram" style="width:350px" value="<?php echo $dados_stm["app_win_url_instagram"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr> 
    <tr>
      <td width="21%" height="50" class="texto_padrao_destaque">Canal YouTube</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_win_url_youtube" type="text" id="app_win_url_youtube" style="width:350px" value="<?php echo $dados_stm["app_win_url_youtube"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>
    <tr>
      <td width="21%" height="50" class="texto_padrao_destaque">WhatsApp</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_win_whatsapp" type="text" id="app_win_whatsapp" style="width:350px" value="<?php echo $dados_stm["app_win_whatsapp"]; ?>" />
        <br />
        +00 00000000000 <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>
    <tr>
      <td width="21%" height="100" class="texto_padrao_destaque">Texto Programação</td>
      <td width="80%" class="texto_padrao_pequeno"><textarea name="app_win_text_prog" id="app_win_text_prog" style="width:350px" rows="5"><?php echo $dados_stm["app_win_text_prog"]; ?></textarea></td>
    </tr>
    <tr>
      <td width="21%" height="100" class="texto_padrao_destaque">Texto História</td>
      <td width="80%" class="texto_padrao_pequeno"><textarea name="app_win_text_hist" id="app_win_text_hist" style="width:350px" rows="5"><?php echo $dados_stm["app_win_text_hist"]; ?></textarea></td>
    </tr>
    <tr>
      <td width="21%" height="50" class="texto_padrao_destaque">Modulo Chat</td>
      <td width="80%" class="texto_padrao_pequeno"><input name="ativar_chat" type="checkbox" value="sim" <?php if($dados_stm["app_win_url_chat"]) { echo ' checked="checked"'; } ?> />
        <input name="app_win_url_chat" type="hidden" id="app_win_url_chat" value="<?php echo "/app-win/chat/".$dados_stm["login"].""; ?>" /></td>
    </tr>
	<?php if($dados_stm["camera_studio"] == "sim" && $dados_stm["camera_studio_instalado"] == "sim") { ?>
    <?php } else { ?>
    <?php } ?> 
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_texto']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="app_win_cor_texto" style="width:100px; height:30px" value="<?php echo $dados_stm['app_win_cor_texto']; ?>" /></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_menu_claro']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="app_win_cor_menu_claro" style="width:100px; height:30px" value="<?php echo $dados_stm['app_win_cor_menu_claro']; ?>" /></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_menu_escuro']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="app_win_cor_menu_escuro" style="width:100px; height:30px" value="<?php echo $dados_stm['app_win_cor_menu_escuro']; ?>" /></td>
    </tr>
    <tr>
      <td width="21%" height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_tv_logo']; ?></td>
      <td width="80%" class="texto_padrao_pequeno"><input name="logo" type="file" id="logo" style="width:350px" />
        <br />PNG / 300x300</td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque">Background</td>
      <td class="texto_padrao_pequeno"><input name="fundo" type="file" id="fundo" style="width:350px" />
        <br />JPG / 640x1136</td>
    </tr>
          <tr>
            <td height="40" colspan="2" align="center"><input type="submit" class="botao" value="<?php echo $lang['lang_botao_titulo_alterar_config']; ?>" onclick="abrir_log_sistema_app();" /><input name="acao_form" type="hidden" id="acao_form" value="configurar" /></td>
          </tr>
  </table>
  </form>
                </div>		      
            </div></td>
          </tr>
        </table>
      </div>
    </div>
</div>
<br><br><br><br>
<!-- Início div log do sistema -->
<div id="log-sistema-fundo"></div>
<div id="log-sistema">
<div id="log-sistema-botao"><img src="img/icones/img-icone-fechar.png" onclick="document.getElementById('log-sistema-fundo').style.display = 'none';document.getElementById('log-sistema').style.display = 'none';" style="cursor:pointer" title="<?php echo $lang['lang_titulo_fechar']; ?>" /></div>
<div id="log-sistema-conteudo"><img src='/img/ajax-loader.gif' /></div>
</div>
<!-- Fim div log do sistema -->
<script language='JavaScript' type='text/javascript'>
tinyMCE.init({
  mode : 'exact',
  elements : 'app_win_text_prog,app_win_text_hist',
  theme : "advanced",
  skin : "o2k7",
  skin_variant : "silver",
  plugins : "table,inlinepopups,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking",
  dialog_type : 'modal',
  force_br_newlines : true,
  force_p_newlines : false,
  theme_advanced_toolbar_location : 'top',
  theme_advanced_toolbar_align : 'left',
  theme_advanced_path_location : 'bottom',
  theme_advanced_buttons1 : 'newdocument,|,bold,italic,underline,|,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,justifyfull,|,undo,redo,|,link,unlink,image,media,|,code',
  theme_advanced_buttons2 : '',
  theme_advanced_buttons3 : '',
  theme_advanced_resize_horizontal : false,
  theme_advanced_resizing : false,
  valid_elements : "*[*]"
});
</script>
</body>
</html>
