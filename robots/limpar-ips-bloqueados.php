<?php
require_once("/home/painelvideo/public_html/admin/inc/conecta.php");

$data = date("Y-m-d H:i:s",mktime (0, 0, 0, date("m")  , date("d")-1, date("Y")));

mysqli_query($conexao,"Delete From bloqueios_login WHERE data < '".$data."'");
?>