<?php
require_once("inc/protecao-admin.php");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".code_decode(query_string('2'),"D")."'"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Streaming</title>
<meta http-equiv="cache-control" content="no-cache">
<link rel="shortcut icon" href="/admin/img/favicon.ico" type="image/x-icon" />
<link href="/admin/inc/estilo.css" rel="stylesheet" type="text/css" />
<link href="/admin/inc/estilo-menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/admin/inc/javascript.js"></script>
</head>

<body>
<div id="topo">
<div id="topo-conteudo" style="background:url(/admin/img/logo.png) no-repeat center;"></div>
</div>
<div id="menu">
<div id="menu-links">
  	<ul>
      <li style="width:150px">&nbsp;</li>
  		<li><a href="/admin/admin-streamings" class="texto_menu">Streamings</a></li>
  		<li><em></em><a href="/admin/admin-revendas" class="texto_menu">Revendas</a></li>
        <li><em></em><a href="/admin/admin-servidores" class="texto_menu">Servidores</a></li>
        <li><em></em><a href="/admin/admin-dicas" class="texto_menu">Dicas</a></li>
        <li><em></em><a href="/admin/admin-avisos" class="texto_menu">Avisos</a></li>
        <li><em></em><a href="/admin/admin-tutoriais" class="texto_menu">Tutoriais</a></li>
        <li><em></em><a href="/admin/admin-configuracoes" class="texto_menu">Configurações</a></li>
        <li><em></em><a href="/admin/sair" class="texto_menu">Sair</a></li>
  	</ul>
</div>
</div>
<div id="conteudo">
  <form method="post" action="/admin/admin-configura-streaming" style="padding:0px; margin:0px">
    <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid;">
      <tr>
        <td width="140" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Login</td>
        <td width="360" align="left" class="texto_padrao_destaque">
        <input type="text" class="input" style="width:250px; cursor:not-allowed" value="<?php echo $dados_stm["login"]; ?>" disabled="disabled" />        </td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Cliente</td>
        <td align="left">
        <select name="codigo_cliente" class="input" id="codigo_cliente" style="width:255px;">
        <option value="0">Nenhum</option>
        
<?php

$query = mysqli_query($conexao,"SELECT * FROM revendas ORDER by nome ASC");
while ($dados_revenda = mysqli_fetch_array($query)) {

$total_streamings = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where codigo_cliente = '".$dados_revenda["codigo"]."'"));

if($dados_revenda["codigo"] == $dados_stm["codigo_cliente"]) {
echo '<option value="' . $dados_revenda["codigo"] . '" selected="selected">' . $dados_revenda["nome"] . ' - ' . $dados_revenda["id"] . ' - ' . $dados_revenda["email"] . ' (' . $total_streamings . ')</option>';
} else {
echo '<option value="' . $dados_revenda["codigo"] . '">' . $dados_revenda["nome"] . ' - ' . $dados_revenda["id"] . ' - ' . $dados_revenda["email"] . ' (' . $total_streamings . ')</option>';
}

}
?>
          </select>        </td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Plano</td>
        <td align="left">
        <select name="plano" class="input" id="plano" style="width:255px;" onchange="configuracao_plano(this.value,'streaming');">
        <option value="" selected="selected" style="font-size:13px; font-weight:bold; background-color:#CCCCCC;">Selecione um plano padrão</option>
        <option value="50|48|10000">Streaming Econômico</option>
        <option value="500|128|50000">Streaming 01</option>
        <option value="1000|128|50000">Streaming 02</option>
        <option value="1500|128|50000">Streaming 03</option>
        <option value="99999|128|80000">Streaming Ilimitado</option>
        </select>          </td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Servidor</td>
        <td align="left">
        <select name="servidor" class="input" id="servidor" style="width:255px;">
<?php
$query_servidor = mysqli_query($conexao,"SELECT * FROM servidores ORDER by ordem ASC");
while ($dados_servidor = mysqli_fetch_array($query_servidor)) {

$total_streamings = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".$dados_servidor["codigo"]."'"));

if($dados_stm["codigo_servidor"] == $dados_servidor["codigo"]) {
echo '<option value="'.$dados_servidor["codigo"].'" selected="selected">'.$dados_servidor["nome"].' - '.$dados_servidor["ip"].' ('.$total_streamings.')</option>';
} else {
echo '<option value="'.$dados_servidor["codigo"].'">'.$dados_servidor["nome"].' - '.$dados_servidor["ip"].' ('.$total_streamings.')</option>';
}

}
?>
        </select>        </td>
      </tr>
      <tr>
        <td height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Espectadores</td>
        <td align="left"><input name="espectadores" type="number" class="input" id="espectadores" style="width:250px;" value="<?php echo $dados_stm["espectadores"]; ?>" /></td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque"> Bitrate</td>
        <td align="left"><input name="bitrate" type="number" class="input" id="bitrate" style="width:250px;" value="<?php echo $dados_stm["bitrate"]; ?>" /></td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Espaço FTP</td>
        <td align="left" class="texto_padrao_pequeno">
        <input name="espaco" type="number" class="input" id="espaco" style="width:250px;" value="<?php echo $dados_stm["espaco"]; ?>" />
        <img src="/admin/img/icones/ajuda.gif" title="Ajuda sobre este item." width="16" height="16" onclick="alert('O valor deve ser em megabytes ex.: 1GB = 1000');" style="cursor:pointer" />        </td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Senha</td>
        <td align="left"><input name="senha" type="text" class="input" id="senha" style="width:250px;" value="<?php echo $dados_stm["senha"]; ?>" />
        <img src="/admin/img/icones/ajuda.gif" title="Ajuda sobre este item." width="16" height="16" onclick="alert('Use apenas lestras e/ou números.\n\nCaracteres como !@#$%¨& não irão funcionar corretamente.');" style="cursor:pointer" /></td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Senha Transmiss&atilde;o</td>
        <td align="left"><input name="senha_transmissao" type="text" class="input" id="senha_transmissao" style="width:250px;" value="<?php echo $dados_stm["senha_transmissao"]; ?>" />
        <img src="/admin/img/icones/ajuda.gif" title="Ajuda sobre este item." width="16" height="16" onclick="alert('Use apenas lestras e/ou números.\n\nCaracteres como !@#$%¨& não irão funcionar corretamente.');" style="cursor:pointer" /></td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">E-mail</td>
        <td align="left"><input name="email" type="text" class="input" id="email" style="width:250px;" value="<?php echo $dados_stm["email"]; ?>" />
          <img src="/admin/img/icones/ajuda.gif" title="Ajuda sobre este item." width="16" height="16" onclick="alert('Informe um e-mail para receber avisos do painel, como espaço em disco excedido, migrações etc...\n\nO envio será feito caso a revenda tenha configurado um SMTP.');" style="cursor:pointer" /></td>
      </tr>
      <tr>
        <td height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Autentica&ccedil;&atilde;o</td>
        <td align="left" class="texto_padrao">
          <input type="radio" name="autenticar_live" id="autenticar_live" value="sim" <?php if($dados_stm["autenticar_live"] == "sim") { echo 'checked="checked"';} ?> />&nbsp;Sim
          <input type="radio" name="autenticar_live" id="autenticar_live" value="nao" <?php if($dados_stm["autenticar_live"] == "nao") { echo 'checked="checked"';} ?> />&nbsp;Não
        <img src="/admin/img/icones/ajuda.gif" title="Ajuda sobre este item." width="16" height="16" onclick="alert('Use esta opção para ativar ou desativar uso de login e senha nos encoders para transmissão ao vivo. Alguns encoders não tem opção de colocar login e senha então desativando a autenticação ajudará nisso.');" style="cursor:pointer" />          </td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Transmiss&atilde;o SRT</td>
        <td align="left" class="texto_padrao">
        <input type="radio" name="srt_status" id="srt_status" value="sim" <?php if($dados_stm["srt_status"] == "sim") { echo 'checked="checked"';} ?> />&nbsp;Sim
        <input type="radio" name="srt_status" id="srt_status" value="nao" <?php if($dados_stm["srt_status"] == "nao") { echo 'checked="checked"';} ?> />&nbsp;N&atilde;o
        </td>
      </tr>
      <tr>
        <td width="150" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Aplica&ccedil;&atilde;o</td>
        <td align="left" class="texto_padrao_destaque">
        <select name="aplicacao" id="aplicacao" style="width:255px;">
		  <option value="tvstation" <?php if($dados_stm["aplicacao"] == "tvstation") { echo 'selected="selected"';} ?>>Tv Station (live & ondemand)</option>
          <option value="live" <?php if($dados_stm["aplicacao"] == "live") { echo 'selected="selected"';} ?>>Live</option>
          <option value="vod" <?php if($dados_stm["aplicacao"] == "vod") { echo 'selected="selected"';} ?>>OnDemand</option>
          <option value="ipcamera" <?php if($dados_stm["aplicacao"] == "ipcamera") { echo 'selected="selected"';} ?>>IP Camera</option>
        </select>
        </td>
      </tr>
      <tr>
        <td height="40">
          <input name="login" type="hidden" id="login" value="<?php echo $dados_stm["login"]; ?>" /></td>
        <td align="left">
          <input type="submit" class="botao" value="Alterar Dados" />
          <input type="button" class="botao" value="Cancelar" onclick="window.location = '/admin/admin-streamings';" /></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>