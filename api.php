<?php
$login = code_decode(query_string('1'),"D");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$login."'"));
$dados_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM revendas WHERE codigo = '".$dados_stm["codigo_cliente"]."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

if($dados_revenda["dominio_padrao"]) {
$servidor = strtolower($dados_servidor["nome"]).".".$dados_revenda["dominio_padrao"];
} else {
$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$servidor = strtolower($dados_servidor["nome"]).".".$dados_config["dominio_padrao"];
}

$xml = new XMLWriter;
$xml->openMemory();
$xml->startDocument('1.0','iso-8859-1');

$xml->startElement("info");

if($dados_stm["status"] == 1) {

############################ Ouvintes Conectados ############################

$stats_wowza = total_espectadores_conectados($dados_servidor["ip"],$dados_servidor["senha"],$dados_stm["login"]);
$espectadores_conectados = $stats_wowza["espectadores"];

#############################################################################
$xml->writeElement("ip", $servidor);
$xml->writeElement("espectadores_conectados", $espectadores_conectados);
$xml->writeElement("plano_espectadores", $dados_stm["espectadores"]);
$xml->writeElement("plano_ftp", tamanho($dados_stm["espaco"]));
$xml->writeElement("plano_bitrate", $dados_stm["bitrate"]."Kbps");
$xml->writeElement("rtmp", "rtmp://".$servidor."/".$dados_stm["login"]."/".$dados_stm["login"]."");
$xml->writeElement("rtsp", "rtsp://".$servidor."/".$dados_stm["login"]."/".$dados_stm["login"]."");

} else {

$xml->writeElement("status", "Desligado");

}

$xml->endElement();

header('Content-type: text/xml');

print $xml->outputMemory(true);

?>