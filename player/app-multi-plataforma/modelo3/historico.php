<?php

$historico_shoutcast = @file_get_contents("http://".$_GET['servidor'].":".$_GET['porta']."/played.html?sid=1");

if(empty($historico_shoutcast)) {
	die("<strong>No pudo obtener datos del historico de canciones.</strong>");
}

$strip_tags = "table|tr|td";

$historico_shoutcast = strip_tags($historico_shoutcast,'<table><tr><td>');

$partes1 = explode("<table",$historico_shoutcast);
$partes2 = explode("</table>",$partes1[2]);

$final = str_replace('<td style="padding: 0 1em;">Current Song</td>', '', $partes2[0]);
$final = str_replace("<td>", "<td style='width:100%;height:30px'>&#9835; ", $final);
$final = str_replace("<td>Song Title</td>", "", $final);

echo "<table id='tabela_historico' style='width:100%;margin:10px;' ".$final;
?>