<?php
require_once("inc/protecao-admin.php");

$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores WHERE codigo = '".code_decode(query_string('2'),"D")."'"));
$total_streamings = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".code_decode(query_string('2'),"D")."'"));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Streaming</title>
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<link rel="shortcut icon" href="/admin/img/favicon.ico" type="image/x-icon" />
<link href="/admin/inc/estilo.css" rel="stylesheet" type="text/css" />
<link href="/admin/inc/estilo-menu.css" rel="stylesheet" type="text/css" />
<script language="javascript">

  function resizePage(){
    var width = 1000;
    var height = 650;
    window.resizeTo(width, height);
    window.moveTo(((screen.width - width) / 2), ((screen.height - height) / 2));      
  }
</script>
</head>

<body onload="resizePage();">
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid; margin-top:5px;">
<tr>
      <td width="140" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Servidor</td>
    <td align="left" class="texto_padrao">&nbsp;<?php echo $dados_servidor["nome"]; ?></td>
  </tr>
    <tr>
      <td height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Streamings</td>
      <td align="left" class="texto_padrao">&nbsp;<?php echo $total_streamings; ?></td>
    </tr>
  </table>
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid; margin-top:5px;">
<tr>
        <td height="30" align="center" class="texto_padrao_destaque" style="padding:5px;">
          <textarea name="lista-streamings" id="lista-streamings" style="width:98%; height:400px;">
<?php
$sql = mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".code_decode(query_string('2'),"D")."' ORDER by login ASC");
while ($dados_stm = mysqli_fetch_array($sql)) {

$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

echo "sshpass -p 'Ra&YZ!zZ[h' rsync --progress -rogpae 'ssh -p 6985 -o StrictHostKeyChecking=no' root@".$dados_servidor["ip"].":/home/streaming/".$dados_stm["login"]."/ /home/streaming/".$dados_stm["login"]."/ &\n";

}
?></textarea>
<br /><br />
<textarea name="lista-streamings" id="lista-streamings" style="width:98%; height:60px;">
<?php

echo "sshpass -p 'Ra&YZ!zZ[h' rsync --progress --files-from='/home/streaming/lista-streamings-migrar.txt' -rogpae 'ssh -p 19000 -o StrictHostKeyChecking=no' root@".$dados_servidor["ip"].":/home/streaming/ /home/streaming/ &\n";

?></textarea>
<br /><br />
<textarea name="lista-streamings" id="lista-streamings" style="width:98%; height:400px;">
<?php
$sql = mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".code_decode(query_string('2'),"D")."' ORDER by login ASC");
while ($dados_stm = mysqli_fetch_array($sql)) {

$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

echo "".$dados_stm["login"]."\n";

}
?></textarea>
<br /><br />
<textarea name="lista-streamings" id="lista-streamings" style="width:98%; height:400px;">
<?php
$sql = mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".code_decode(query_string('2'),"D")."' ORDER by login ASC");
while ($dados_stm = mysqli_fetch_array($sql)) {

$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

echo "rm -Rfv /home/streaming/".$dados_stm["login"]."\n";

}
?></textarea>
<br /><br />
<textarea name="lista-streamings" id="lista-streamings" style="width:98%; height:400px;">
<?php
$sql = mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".code_decode(query_string('2'),"D")."' ORDER by login ASC");
while ($dados_stm = mysqli_fetch_array($sql)) {

$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

echo "/bin/ps aux | /bin/grep sc_ | /bin/grep ".$dados_stm["login"]." | /bin/awk '{ print $2;}' | /usr/bin/xargs /bin/kill -9\n";

}
?></textarea>
        </td>
      </tr>
    </table>
<br />
</body>
</html>
