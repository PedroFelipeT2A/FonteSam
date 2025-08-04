<?php
require_once("admin/inc/protecao-final.php");

$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM stmvideo.streamings where login = '".$_SESSION["login_logado"]."'"));
$dados_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM stmvideo.revendas WHERE codigo = '".$dados_stm["codigo_cliente"]."'"));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Streaming</title>
<meta http-equiv="cache-control" content="no-cache">
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
<link href="inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="inc/javascript.js"></script>
<script type="text/javascript">
   window.onload = function() {
	fechar_log_sistema();
   };
</script>
</head>

<body>
<div id="sub-conteudo">
<div id="quadro">
            	<div id="quadro-topo"><strong>App Transmissao ao vivo Celular </strong></div>
                <div class="texto_medio" id="quadro-conteudo">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="25" class="texto_padrao">Utilize o ManyCam para transmitir ao vivo diretamente de seu smartphone para seu streaming de forma f&aacute;cil e com qualidade.<br />
    <br />
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
      
      
      </tr>
      <tr>
    
      </tr>
      <tr>
        <td height="50" align="center"><a href="https://clientes.samhost.com.br/index.php?rp=/knowledgebase/82/Transmitindo-TV-ao-vivo-pelo-Celular.html" target="_blank"><img src="img/img-logo-google-play.png" alt="Acesse aqui" width="150" height="44" border="0" /></a></td>
     
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