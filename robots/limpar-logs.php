<?php
ini_set("max_execution_time", 600);

require_once("/home/painelvideo/public_html/admin/inc/conecta.php");

$data = date("Y-m-d",mktime (0, 0, 0, date("m")  , date("d")-180, date("Y")));

mysqli_query($conexao,"Delete From logs_streamings WHERE data < '".$data."'");

?>