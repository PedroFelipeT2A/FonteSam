<?php
require_once("admin/inc/protecao-final.php");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

$dados_app_criado = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM apps where codigo_stm = '".$dados_stm["codigo"]."'"));

// Verifica se é retorno do criador de app
if(query_string('1')) {

list($package, $hash) = explode("|",query_string('1'));

if($package != "erro") {

// Insere os dados no banco de dados
mysqli_query($conexao,"INSERT INTO apps (codigo_stm,package,data,hash,zip,compilado,status) VALUES ('".$dados_stm["codigo"]."','".$package."',NOW(),'".$hash."','".$hash.".zip','sim','1')");

} else {

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao("Ocorreu um erro ao criar seu aplicativo, por favor tente novamente ou contate nosso suporte.","erro");

}

header("Location: /app-android");
exit();
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
<script type="text/javascript" src="inc/sorttable.js"></script>
<script type="text/javascript">
   window.onload = function() {
	fechar_log_sistema();
   };
</script>
</head>

<body>
<div id="sub-conteudo">
<?php
if($_SESSION['status_acao']) {

$status_acao = stripslashes($_SESSION['status_acao']);

echo '<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px">'.$status_acao.'</table>';

unset($_SESSION['status_acao']);
}
?>
<?php if(isset($dados_app_criado["codigo"])) { ?>
<table width="740" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div id="quadro">
      <div id="quadro-topo"> <strong><?php echo $lang['lang_info_streaming_app_android_tab_titulo']; ?></strong>      </div>
      <div class="texto_medio" id="quadro-conteudo">
      <table width="720" border="0" align="center" cellpadding="0" cellspacing="0" style="border:#D5D5D5 1px solid;">
    <tr style="background:url(img/img-fundo-titulo-tabela.png) repeat-x; cursor:pointer">
      <td width="120" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;<?php echo $lang['lang_info_streaming_app_android_data']; ?></td>
      <td width="470" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;<?php echo $lang['lang_info_streaming_app_android_status']; ?></td>
      <td width="130" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid;">&nbsp;<?php echo $lang['lang_info_streaming_app_android_acao']; ?></td>
    </tr>
<?php
$sql = mysqli_query($conexao,"SELECT *, DATE_FORMAT(data,'%d/%m/%Y %H:%i:%s') AS data FROM apps WHERE codigo_stm = '".$dados_stm["codigo"]."'");
while ($dados_app = mysqli_fetch_array($sql)) {

$app_code = code_decode($dados_app["codigo"],"E");

if($dados_app["status"] == 1) {

$status = $lang['lang_info_streaming_app_android_requisicao_concluida'];

$acao = "<a href=\"/app/apps/".$dados_app["zip"]."\" target=\"_blank\">[Download]</a>&nbsp;<a href=\"javascript:executar_acao_streaming('".$app_code."','remover-app-android' );\">".$lang['lang_info_streaming_app_android_botao_remover_app']."</a>";

$cor_status = '#C6FFC6';

} elseif($dados_app["status"] == 2) {

$status = $dados_app["aviso"];
$acao = "<a href=\"javascript:executar_acao_streaming('".$app_code."','remover-app-android' );\">".$lang['lang_info_streaming_app_android_botao_remover_app']."</a>";
$cor_status = '#FFB9B9';

} else {

$status = $lang['lang_info_streaming_app_android_requisicao_em_andamento'];
$acao = "<a href=\"javascript:executar_acao_streaming('".$app_code."','remover-app-android' );\">".$lang['lang_info_streaming_app_android_botao_remover_app']."</a>";
$cor_status = '#FFFFFF';
}

echo "<tr style='background-color:".$cor_status.";'>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>&nbsp;".$dados_app["data"]."</td>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>&nbsp;".$status."</td>
<td height='25' align='center' scope='col' class='texto_padrao_pequeno'>&nbsp;".$acao."</td>
</tr>";

}
?>
  </table>
      </div>
    </div></td>
  </tr>
</table>
<br />
<?php } ?>
<?php if(!isset($dados_app_criado["codigo"]) || $dados_app_criado["status"] == 2) { ?>
<form action="/app/criar-app-video.php" method="post" name="form" enctype="multipart/form-data">
<table width="740" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
    <div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_streaming_app_android_tab_titulo_instrucoes']; ?></strong></div>
            <div class="texto_medio" id="quadro-conteudo">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100%" height="40" align="left" class="texto_padrao_pequeno">
      <?php echo $lang['lang_info_streaming_app_android_instrucoes_1']; ?><br />
	  <?php echo $lang['lang_info_streaming_app_android_instrucoes_2']; ?><br />
	  <?php echo $lang['lang_info_streaming_app_android_instrucoes_3']; ?><br />
	  <?php echo $lang['lang_info_streaming_app_android_instrucoes_4']; ?><br />
	  <?php echo $lang['lang_info_streaming_app_android_instrucoes_5']; ?>
      </td>
      </tr>
  </table>
  </div>
  </div>
    <br />
	<div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_streaming_app_android_tab_titulo_info_radio']; ?></strong>

              <input name="enviar" type="hidden" id="enviar" value="sim" />
            </div>
          <div class="texto_medio" id="quadro-conteudo">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_tv_nome']; ?></td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="webtv_nome" type="text" id="webtv_nome" style="width:350px" value="" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_tv_nome_info']; ?></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">FaceBook</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="webtv_facebook" type="text" id="webtv_facebook" style="width:350px" value="" /></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">Twitter</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="webtv_twitter" type="text" id="webtv_twitter" style="width:350px" value="" /></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">Site&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="webtv_site" type="text" id="webtv_site" style="width:350px" value="" /></td>
    </tr>
    <tr>
      <td width="20%" height="100" class="texto_padrao_destaque">Descrição</td>
      <td width="80%" class="texto_padrao_pequeno"><label>
        <textarea name="webtv_descricao" id="webtv_descricao" style="width:350px" rows="5"></textarea>
      </label></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_versao']; ?></td>
      <td class="texto_padrao_pequeno">
        <select name="versao" id="versao">
          <option value="1.0" selected="selected">1.0</option>
          <option value="1.1">1.1</option>
          <option value="1.2">1.2</option>
          <option value="1.3">1.3</option>
          <option value="1.4">1.4</option>
          <option value="1.5">1.5</option>
          <option value="1.6">1.6</option>
          <option value="1.7">1.7</option>
          <option value="1.8">1.8</option>
          <option value="1.9">1.9</option>
          <option value="1.10">1.10</option>
          <option value="1.11">1.11</option>
          <option value="1.12">1.12</option>
          <option value="1.13">1.13</option>
          <option value="1.14">1.14</option>
          <option value="1.15">1.15</option>
          <option value="1.16">1.16</option>
          <option value="1.17">1.17</option>
          <option value="1.18">1.18</option>
          <option value="1.19">1.19</option>
          <option value="1.20">1.20</option>
        </select>
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_versao']; ?></td>
    </tr>
  </table>
  </div>
  </div>
  <br />
<div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_streaming_app_android_tab_titulo_personalizacao_app']; ?></strong></div>
            <div class="texto_medio" id="quadro-conteudo">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_tv_logo']; ?></td>
      <td width="80%" class="texto_padrao_pequeno"><input name="logo" type="file" id="logo" style="width:350px" />
        <br />
        PNG / 500x500</td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_tv_icone']; ?></td>
      <td class="texto_padrao_pequeno"><input name="icone" type="file" id="icone" style="width:350px" />
        <br />PNG / 144x144</td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque">Background</td>
      <td class="texto_padrao_pequeno"><input name="fundo" type="file" id="fundo" style="width:350px" />
        <br />JPG / 720x1280</td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque">Tema</td>
      <td class="texto_padrao_pequeno">
      <select name="tema" id="tema">
        <option value="#0099CC|#003399" selected="selected">Azul/Blue</option>
        <option value="#00796B|#00695C">Verde/Green</option>
        <option value="#FF6699|#FF3366">Rosa/Pink</option>
        <option value="#FF5959|#FF0000">Vermelho/Red</option>
      </select>
      <input name="servidor" type="hidden" value="<?php echo strtolower($dados_servidor["nome"]).".".$dados_config["dominio_padrao"]; ?>" /><input name="login" type="hidden" value="<?php echo $dados_stm["login"]; ?>" /><input name="idioma_painel" type="hidden" value="<?php echo $dados_stm["idioma_painel"]; ?>" />
      </td>
    </tr>
  </table>
  </div>
  </div>
  <br />
  <center><input name="button" type="submit" class="botao" id="button" value="<?php echo $lang['lang_info_streaming_app_android_botao_submit']; ?>" onclick="abrir_log_sistema();" /></center>
  </td>
  </tr>
</table>
</form>
<br />
  <br />
  <br />
<?php } ?>
</div>
<!-- Início div log do sistema -->
<div id="log-sistema-fundo"></div>
<div id="log-sistema">
<div id="log-sistema-botao"><img src="img/icones/img-icone-fechar.png" onclick="document.getElementById('log-sistema-fundo').style.display = 'none';document.getElementById('log-sistema').style.display = 'none';" style="cursor:pointer" title="<?php echo $lang['lang_titulo_fechar']; ?>" /></div>
<div id="log-sistema-conteudo"><img src='/img/ajax-loader.gif' /></div>
</div>
<!-- Fim div log do sistema -->
</body>
</html>