<?php
require_once("inc/protecao-admin.php");
require_once("inc/classe.ssh.php");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
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
<script type="text/javascript" src="/admin/inc/ajax.js"></script>
<script type="text/javascript" src="/admin/inc/javascript.js"></script>
<script type="text/javascript" src="/admin/inc/sorttable.js"></script>
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
  <table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="border:#D5D5D5 1px solid;" id="tab" class="sortable">
    <tr style="background:url(/img/img-fundo-titulo-tabela.png) repeat-x; cursor:pointer">
      <td width="350" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid;border-right:#D5D5D5 1px solid;">&nbsp;Login</td>
      <td width="150" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;Servidor</td>
      <td width="200" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;Limite Plano</td>
      <td width="200" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid;">&nbsp;Total Conectados</td>
    </tr>
<?php

$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".code_decode(query_string('2'),"D")."'"));

$sql = mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".$dados_servidor["codigo"]."' ORDER by login ASC");

while ($dados_stm = mysqli_fetch_array($sql)) {

$dados_wowza = total_espectadores_conectados($dados_servidor["ip"],$dados_servidor["senha"],$dados_stm["login"]);
$espectadores_conectados = $dados_wowza["espectadores"];

if(empty($espectadores_conectados)) {
$espectadores_conectados = 0;
}

$limite_espectadores = ($dados_stm["espectadores"] > "9999") ? '<span class="texto_padrao_pequeno">ILIMITADO</span>' : $dados_stm["espectadores"];

echo "<tr>
<td height='25' align='left' scope='col' class='texto_padrao'>&nbsp;<a href='/admin/admin-streamings/resultado/".$dados_stm["login"]."' class='texto_padrao'>".$dados_stm["login"]."</a></td>
<td height='25' align='left' scope='col' class='texto_padrao'>&nbsp;".$dados_servidor["nome"]."</td>
<td height='25' align='left' scope='col' class='texto_padrao'>&nbsp;".$limite_espectadores."</td>
<td height='25' align='left' scope='col' class='texto_padrao'>&nbsp;".$espectadores_conectados."</td>
</tr>";

}
?>
  </table>
<br>
<br>
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
