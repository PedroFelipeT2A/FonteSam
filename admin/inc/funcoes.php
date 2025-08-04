<?php
// Fun��es de uso no painel

////////////////////////////////////////
//////////// Fun��es Gerais ////////////
////////////////////////////////////////

// Fun��o para gerenciar query string
function query_string($posicao='0') {

$gets = explode("/",str_replace(strrchr($_SERVER["REQUEST_URI"], "?"), "", $_SERVER["REQUEST_URI"]));
array_shift($gets);

return utf8_decode(urldecode($gets[$posicao]));

}

// Fun��o para codificar e decodificar strings
function code_decode($texto, $tipo = "E") {

  if($tipo == "E") {
  
  $sesencoded = $texto;
  $num = mt_rand(0,3);
  for($i=1;$i<=$num;$i++)
  {
     $sesencoded = base64_encode($sesencoded);
  }
  $alpha_array = array('1','Z','3','R','1','Y','2','N','A','T','Z','X','A','E','Y','6','9','4','F','S','X');
  $sesencoded =
  $sesencoded."+".$alpha_array[$num];
  $sesencoded = base64_encode($sesencoded);
  return $sesencoded;
  
  } else {
  
   $alpha_array = array('1','Z','3','R','1','Y','2','N','A','T','Z','X','A','E','Y','6','9','4','F','S','X');
   $decoded = base64_decode($texto);
   list($decoded,$letter) = explode("+",$decoded);
   for($i=0;$i<@count($alpha_array);$i++)
   {
   if($alpha_array[$i] == $letter)
   break;
   }
   for($j=1;$j<=$i;$j++)
   {
      $decoded = base64_decode($decoded);
   }
   return $decoded;
  }
}

// Fun��o para gerar ID da revenda
function gera_id() {

$aux = microtime();
$id = substr(md5($aux),0,6);

return $id;
}

// Fun��o para criar c�lulas de logs do sistema
function status_acao($status,$tipo) {

if($tipo == 'ok') {
$celula_status = '<tr style="background-color:#A6EF7B;">
      <td width="790" height="35" class="texto_log_sistema" scope="col">
	  <div align="center">'.$status.'</div>
	  </td>
</tr>
<tr><td scope="col" height="2" width="770"></td></tr>
';
} elseif($tipo == 'ok2') {
$celula_status = '<tr style="background-color:#A6EF7B;">
      <td width="790" height="35" class="texto_log_sistema" scope="col">
	  <div align="center">'.$status.'</div>
	  </td>
</tr>
<tr><td scope="col" height="2" width="770"></td></tr>
';
} elseif($tipo == 'alerta') {
$celula_status = '<tr style="background-color:#FFFF66;">
      <td width="790" height="35" class="texto_log_sistema_alerta" scope="col">
	  <div align="center">'.$status.'</div>

	  </td>
</tr>
<tr><td scope="col" height="2" width="770"></td></tr>
';
} else {
$celula_status = '<tr style="background-color:#F2BBA5;">
      <td width="790" height="35" class="texto_log_sistema_erro" scope="col">
	  <div align="center">'.$status.'</div>
	  </td>
</tr>
<tr><td scope="col" height="2" width="770"></td></tr>
';
}  

return $celula_status;
}

// Fun��o para remover acentos e espa�os
function formatar_nome_playlist($playlist) {

$array_caracteres = array("/[�����]/"=>"a","/[�����]/"=>"a","/[����]/"=>"e","/[����]/"=>"e","/[����]/"=>"i","/[����]/"=>"i","/[�����]/"=>"o", "/[�����]/"=>"o","/[����]/"=>"u","/[����]/"=>"u","/�/"=>"c","/�/"=> "c","/ /"=> "","/_/"=> "");

$formatado = preg_replace(array_keys($array_caracteres), array_values($array_caracteres), $playlist);

return strtolower($formatado);
}

// Fun��o para remover acentos e espa�os
function formatar_nome_ip_camera($ip_camera) {

$array_caracteres = array("/[�����]/"=>"a","/[�����]/"=>"a","/[����]/"=>"e","/[����]/"=>"e","/[����]/"=>"i","/[����]/"=>"i","/[�����]/"=>"o", "/[�����]/"=>"o","/[����]/"=>"u","/[����]/"=>"u","/�/"=>"c","/�/"=> "c","/ /"=> "","/_/"=> "");

$formatado = preg_replace(array_keys($array_caracteres), array_values($array_caracteres), $ip_camera);

return strtolower($formatado);
}

// Fun��o para formatar texto retirando acentos e caracteres especiais
function formatar_texto($texto) {

$characteres = array(
    'S'=>'S', 's'=>'s', '�'=>'Dj','Z'=>'Z', 'z'=>'z', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A',
    '�'=>'A', '�'=>'A', '�'=>'C', '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'I', '�'=>'I', '�'=>'I',
    '�'=>'I', '�'=>'N', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'U', '�'=>'U',
    '�'=>'U', '�'=>'U', '�'=>'Y', '�'=>'B', '�'=>'Ss','�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a',
    '�'=>'a', '�'=>'a', '�'=>'c', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'i', '�'=>'i', '�'=>'i',
    '�'=>'i', '�'=>'o', '�'=>'n', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'u',
    '�'=>'u', '�'=>'u', '�'=>'y', '�'=>'y', '�'=>'b', '�'=>'y', 'f'=>'f', '�'=> '', '�'=> '', '&'=> 'e',
	'�'=> '', '�'=> '', '$'=> '', '%'=> '', '�'=> '', '�'=> '', '�'=> '', '�'=> '', '�'=> '', 'ã'=> '',
	'('=> '', ')'=> '', "'"=> '', '@'=> '', '='=> '', ':'=> '', '!'=> '', '?'=> '', '...'=> '', '['=> '',
	']'=> '', '"'=> '', '.'=> ''
);

return strtr($texto, $characteres);

}

function pais_ip($ip,$tipo) {

$dados_api_geoip = api_geoip($ip);

$pais_codigo = $dados_api_geoip["pais_sigla"];
$pais_nome = $dados_api_geoip["pais_nome"];

if($tipo == "nome") {
return $pais_nome;
} else {
return $pais_codigo;
}

}

// Fun��o para remover acentos
function remover_acentos($msg) {
$a = array("/[�����]/"=>"A","/[�����]/"=>"a","/[����]/"=>"E","/[����]/"=>"e","/[����]/"=>"I","/[����]/"=>"i","/[�����]/"=>"O",	"/[�����]/"=>"o","/[����]/"=>"U","/[����]/"=>"u","/�/"=>"c","/�/"=> "C");

return preg_replace(array_keys($a), array_values($a), $msg);
}

// Fun��o para formatar os segundos em segundos, minutos e horas
function tempo_conectado($segundos) {

$days=intval($segundos/86400);
$remain=$segundos%86400;
$hours=intval($remain/3600);
$remain=$remain%3600;
$mins=intval($remain/60);
$secs=$remain%60;
if (strlen($mins)<2) {
$mins = '0'.$mins;
}
if($days > 0) $dia = $days.'d';
if($hours > 0) $hora = $hours.'hr, ';
if($mins > 0) $minuto = $mins.'min, ';

$segundo = $secs.'seg';
$segundos = $dia.$hora.$minuto.$segundo;

return $segundos;

}

function seconds2time($segundos) {

return @gmdate("H:i:s", round($segundos));

}

// Fun��o para retornar o tipo de medida do tamanho do arquivo(Byts, Kbytes, Megabytes, Gigabytes, etc...)
function tamanho($size)
{
    $filesizename = array(" MB", " GB", " TB", " PB", " EB", " ZB", " YB");
    return $size ? round($size/pow(1000, ($i = floor(log($size, 1000)))), 2) . $filesizename[$i] : '0 Bytes';
}

// Fun��o para criar um barra de porcentagem de uso do plano
function barra_uso_plano($porcentagem,$descricao) {

$porcentagem_progresso = ($porcentagem > 100) ? "100" : $porcentagem;

$cor = "#00CC00";
$cor = ($porcentagem_progresso > 50 && $porcentagem_progresso < 80) ? "#FFE16C" : $cor;
$cor = ($porcentagem_progresso > 80) ? "#FF0000" : $cor;

return "<div class='barra-uso-plano-corpo' title='".$descricao."'>
<div class='barra-uso-plano-progresso' style='background-color: ".$cor."; width: ".round($porcentagem_progresso)."%;'>
<div class='barra-uso-plano-texto'>".round($porcentagem)."%</div>
</div>
</div>";

}

// Fun��o para calcular tempo de exceuss�o
function tempo_execucao() {
    $sec = explode(" ",microtime());
    $tempo = $sec[1] + $sec[0];
    return $tempo;
}

function anti_sql_injection($str) {
    if (!is_numeric($str)) {
        $str = str_replace("'='","",$str);
    }
    return $str;
}

function zebrar($i) {
    return func_get_arg(abs($i) % (func_num_args() - 1) + 1);
}

// Fun��o para conectar a uma URL
function conectar_url($url,$timeout) {

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)');
$resultado = curl_exec($ch);
curl_close($ch);

if($resultado === false) {
return "erro";
} else {

return $resultado;

}

}

// Fun��o para carregar avisos para streamings na inicializa��o
function carregar_avisos_streaming_inicializacao($conexao,$login,$servidor) {

$sql = mysqli_query($conexao,"SELECT * FROM avisos WHERE area = 'streaming'");
while ($dados_aviso = mysqli_fetch_array($sql)) {

if($dados_aviso["status"] == "sim") {

$checar_status_aviso = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM avisos_desativados where codigo_aviso = '".$dados_aviso["codigo"]."' AND area = 'streaming' AND login = '".$login."'"));

if($checar_status_aviso == 0 && ($dados_aviso["codigo_servidor"] == "0" || $dados_aviso["codigo_servidor"] == $servidor)) {

echo "exibir_aviso('".$dados_aviso["codigo"]."');";

} // if aviso desativado usuario
} // if exibir sim/nao
} // while avisos

}

// Fun��o para carregar avisos para streamings
function carregar_avisos_streaming($conexao,$login,$servidor) {

$avisos = "";
$total_avisos = 0;

$sql = mysqli_query($conexao,"SELECT *, DATE_FORMAT(data,'%d/%m/%Y') AS data FROM avisos WHERE area = 'streaming'");
while ($dados_aviso = mysqli_fetch_array($sql)) {

if($dados_aviso["status"] == "sim" && ($dados_aviso["codigo_servidor"] == "0" || $dados_aviso["codigo_servidor"] == $servidor)) {

$checar_status_aviso = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM avisos_desativados where codigo_aviso = '".$dados_aviso["codigo"]."' AND area = 'streaming' AND login = '".$login."'"));

if($checar_status_aviso == 0) {

$avisos .= "[".$dados_aviso["data"]."] ".$dados_aviso["descricao"]."&nbsp;<a href='#' onclick='exibir_aviso(\"".$dados_aviso["codigo"]."\");'>[+]</a><br />";

$total_avisos++;

} // if exibir sim/nao DESATIVADO
} // if exibir sim/nao
} // while avisos

if($total_avisos > 0) {
return $avisos;
}

}

// Fun��o para carregar avisos para revendas
function carregar_avisos_revenda($conexao) {

$total_avisos = 0;

$sql = mysqli_query($conexao,"SELECT *, DATE_FORMAT(data,'%d/%m/%Y') AS data FROM avisos WHERE area = 'revenda' ORDER by data DESC");
while ($dados_aviso = mysqli_fetch_array($sql)) {

if($dados_aviso["status"] == "sim") {

echo "[".$dados_aviso["data"]."] ".$dados_aviso["descricao"]."&nbsp;<a href='#' onclick='exibir_aviso(\"".$dados_aviso["codigo"]."\");'>[+]</a><br />";

$total_avisos++;
}
}

if($total_avisos == 0) {
echo "<span class='texto_padrao'>N�o h� registro de avisos.</span>";
}

}

// Fun��o para carregar avisos para streamings
function carregar_avisos_streaming_revenda($conexao,$login,$servidor) {

$total_avisos = 0;

$sql = mysqli_query($conexao,"SELECT *, DATE_FORMAT(data,'%d/%m/%Y') AS data FROM avisos WHERE area = 'streaming'");
while ($dados_aviso = mysqli_fetch_array($sql)) {

if($dados_aviso["status"] == "sim") {

$checar_status_aviso = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM avisos_desativados where codigo_aviso = '".$dados_aviso["codigo"]."' AND area = 'streaming' AND login = '".$login."'"));

if($checar_status_aviso == 0 && ($dados_aviso["codigo_servidor"] == "0" || $dados_aviso["codigo_servidor"] == $servidor)) {

echo "[".$dados_aviso["data"]."] ".$dados_aviso["descricao"]."&nbsp;<a href='#' onclick='exibir_aviso(\"".$dados_aviso["codigo"]."\");'>[+]</a><br />";

$total_avisos++;

} // if aviso desativado usuario
} // if exibir sim/nao
} // while avisos

if($total_avisos == 0) {
echo "<span class='texto_padrao'>N�o h� registro de avisos.</span>";
}

}

// Fun��o para criar formatar dom�nio do servidor
function dominio_servidor($conexao, $nome ) {

if($_SESSION["login_logado"]) {
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));
$dados = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM revendas WHERE codigo = '".$dados_stm["codigo_cliente"]."'"));
} elseif($_SESSION["code_user_logged"] && $_SESSION["type_logged_user"] == "cliente") {
$dados = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM revendas WHERE codigo = '".$_SESSION["code_user_logged"]."'"));
} else {
$dados = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
}

if($dados["dominio_padrao"]) {
return strtolower($nome).".".$dados["dominio_padrao"];
} else {
$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
return strtolower($nome).".".$dados_config["dominio_padrao"];
}

}

function xml_entity_decode($_string) {
    // Set up XML translation table
    $_xml=array();
    $_xl8=get_html_translation_table(HTML_ENTITIES,ENT_COMPAT);
    while (list($_key,)=each($_xl8))
        $_xml['&#'.ord($_key).';']=$_key;
    return strtr($_string,$_xml);
}

// Fun��o abreviar o nome do navegador
function formatar_navegador($navegador) {

if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$navegador,$matched)) {
return  'IE '.$matched[1].'';
} elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$navegador,$matched)) {
return  'Opera '.$matched[1].'';
} elseif(preg_match('|Firefox/([0-9\.]+)|',$navegador,$matched)) {
return  'Firefox '.$matched[1].'';
} elseif(preg_match('|Chrome/([0-9\.]+)|',$navegador,$matched)) {
return  'Chrome '.$matched[1].'';
} elseif(preg_match('|Safari/([0-9\.]+)|',$navegador,$matched)) {
return  'Safari '.$matched[1].'';
} else {
return 'Desconhecido';
}

}

// Fun��o para inserir registro do log de a��es do painel de administra��o/revenda no banco de dados
function logar_acao($conexao,$log) {

mysqli_query($conexao,"INSERT INTO logs (data,host,ip,navegador,log) VALUES (NOW(),'http://".$_SERVER['HTTP_HOST']."','".$_SERVER['REMOTE_ADDR']."','".formatar_navegador($_SERVER['HTTP_USER_AGENT'])."','".$log."')") or die("Erro ao inserir log: ".mysqli_error($conexao)."");

}

// Fun��o para inserir registro do log de a��es do painel de streaming no banco de dados
function logar_acao_streaming($conexao,$codigo_stm,$log) {

mysqli_query($conexao,"INSERT INTO logs_streamings (codigo_stm,data,host,ip,navegador,log) VALUES ('".$codigo_stm."',NOW(),'http://".$_SERVER['HTTP_HOST']."','".$_SERVER['REMOTE_ADDR']."','".formatar_navegador($_SERVER['HTTP_USER_AGENT'])."','".$log."')") or die("Erro ao inserir log: ".mysqli_error($conexao)."");

}

////////////////////////////////////////////////
/////////////// Fun��es  Wowza /////////////////
////////////////////////////////////////////////

// Verifica se esta ao vivo
function status_aovivo($agent) {

$array_user_agents_live = array("Wirecast","Teradek","vmix","Vmix","FMLE","GoCoder","PUBLISH");

foreach($array_user_agents_live as $user_agent_live) {

if(strpos($agent, $user_agent_live) !== FALSE) { 
return "aovivo";
}

}

}

// Fun��o para checar status do streaming
function status_streaming($ip,$senha,$login) {

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://".$ip.":555/webrtc-stats");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_USERPWD, "admin:".code_decode($senha,"D").""); 
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST); 
curl_setopt($ch, CURLOPT_USERAGENT, 'Painel de Streaming 3.0.0');
ob_start();
$resultado = curl_exec($ch);
$data = ob_get_clean();

$status_transmissao = "";

$xml = @simplexml_load_string(utf8_encode($resultado));

$total_streamings = count($xml->VHost->Application);

if($total_streamings > 0) {

for($i=0;$i<$total_streamings;$i++){

if($xml->VHost->Application[$i]->Name == $login) {

$status = $xml->VHost->Application[$i]->Status;

$total_rtmp = $xml->VHost->Application[$i]->ApplicationInstance->RTMPSessionCount;
$total_rtp = $xml->VHost->Application[$i]->ApplicationInstance->RTPSessionCount;

if($total_rtmp > 0) {

    for($a=0;$a<$total_rtmp;$a++){
        $status_transmissao = status_aovivo($xml->VHost->Application[$i]->ApplicationInstance->Client[$a]->FlashVersion);
        if($status_transmissao == "aovivo") {
            break;
        }
    }
}
if($total_rtp > 0) {
    for($b=0;$b<$total_rtp;$b++){
        $status_transmissao = status_aovivo($xml->VHost->Application[$i]->ApplicationInstance->RTPSession[$b]->Mode);
        if($status_transmissao == "aovivo") {
            break;
        }
    }
}
break;
}

}

}

return array("status" => $status, "status_transmissao" => $status_transmissao);

curl_close($ch);
}

// Fun��o para capturar o TOTAL de espectadores conectados
function total_espectadores_conectados($ip,$senha,$login) {

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://".$ip.":555/webrtc-stats");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_USERPWD, "admin:".code_decode($senha,"D").""); 
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST); 
curl_setopt($ch, CURLOPT_USERAGENT, 'Painel de Streaming 3.0.0');
ob_start();
$resultado = curl_exec($ch);
$data = ob_get_clean();

$total_espectadores = 0;

$xml = @simplexml_load_string(utf8_encode($resultado));

$total_streamings = count($xml->VHost->Application);

if($total_streamings > 0) {

for($i=0;$i<$total_streamings;$i++){

if($xml->VHost->Application[$i]->Name == $login) {

$status = $xml->VHost->Application[$i]->Status;
$total_espectadores = $xml->VHost->Application[$i]->ConnectionsCurrent;
 
$total_rtmp = $xml->VHost->Application[$i]->ApplicationInstance->RTMPSessionCount;
$total_rtp = $xml->VHost->Application[$i]->ApplicationInstance->RTPSessionCount;

if($total_rtmp > 0 || $total_rtp > 0) {

    for($a=0;$a<$total_rtmp;$a++){
        $check_status_transmissao = status_aovivo($xml->VHost->Application[$i]->ApplicationInstance->Client[$a]->FlashVersion);
        if($check_status_transmissao == "aovivo") {
            $total_espectadores = $total_espectadores-1;
            break;
        }
    }

    for($a=0;$b<$total_rtp;$b++){
        $check_status_transmissao = status_aovivo($xml->VHost->Application[$i]->ApplicationInstance->RTPSession[$b]->Mode);
        if($check_status_transmissao == "aovivo") {
            $total_espectadores = $total_espectadores-1;
            break;
        }
    }
}

break;
}

}

}

return array("espectadores" => $total_espectadores);

curl_close($ch);

}

// Fun��o para obter as estatisticas do streaming no servidor para os robots
function estatistica_streaming_robot($ip,$senha) {

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://".$ip.":555/stats");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_USERPWD, "admin:".code_decode($senha,"D").""); 
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST); 
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; pt-BR; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 ( .NET CLR 3.5.30729)');
$resultado = curl_exec($ch);
curl_close($ch);

return simplexml_load_string(utf8_encode($resultado));
}

function estatistica_streaming_robot_webrtc($ip,$senha) {

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://".$ip.":555/webrtc-stats");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_USERPWD, "admin:".code_decode($senha,"D").""); 
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST); 
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; pt-BR; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 ( .NET CLR 3.5.30729)');
$resultado = curl_exec($ch);
curl_close($ch);

return simplexml_load_string(utf8_encode($resultado));
}

// Fun��o para obter as estatisticas do streaming no servidor para a pagina de espectadores conectados
function estatistica_espectadores_conectados($ip, $senha, $login, $aplicacao = 'tvstation')
{

    $url = ($aplicacao == "webrtc") ? "http://" . $ip . ":555/webrtc-stats" : "http://" . $ip . ":555/stats";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERPWD, "admin:" . code_decode($senha, "D") . "");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Painel de Streaming 3.0.0');
    ob_start();
    $resultado = curl_exec($ch);
    $data = ob_get_clean();

    $array_espectadores = array();

    $xml = @simplexml_load_string(utf8_encode($resultado));

    $total_streamings = count($xml->VHost->Application);

    if ($total_streamings > 0) {

        for ($i = 0; $i < $total_streamings; $i++) {

            if ($xml->VHost->Application[$i]->Name == $login) {

                // Aplicacao geral
                if ($aplicacao != "webrtc") {

                    $total_espectadores = count($xml->VHost->Application[$i]->ApplicationInstance->Client);

                    for ($ii = 0; $ii < $total_espectadores; $ii++) {

                        $status_transmissao = status_aovivo($xml->VHost->Application[$i]->ApplicationInstance->Client[$ii]->FlashVersion);

                        if ($status_transmissao != "aovivo") {

                            $ip = $xml->VHost->Application[$i]->ApplicationInstance->Client[$ii]->IpAddress;
                            $tempo_conectado = $xml->VHost->Application[$i]->ApplicationInstance->Client[$ii]->TimeRunning;
                            $player = formatar_useragent($xml->VHost->Application[$i]->ApplicationInstance->Client[$ii]->Proxy);

                            $array_espectadores[] = $ip . "|" . $tempo_conectado . "|" . $player . "";
                        }
                    }

                    $total_espectadores = count($xml->VHost->Application[$i]->ApplicationInstance[1]->Client);

                    for ($ii = 0; $ii < $total_espectadores; $ii++) {

                        $status_transmissao = status_aovivo($xml->VHost->Application[$i]->ApplicationInstance[1]->Client[$ii]->FlashVersion);

                        if ($status_transmissao != "aovivo") {

                            $ip = $xml->VHost->Application[$i]->ApplicationInstance[1]->Client[$ii]->IpAddress;
                            $tempo_conectado = $xml->VHost->Application[$i]->ApplicationInstance[1]->Client[$ii]->TimeRunning;
                            $player = formatar_useragent($xml->VHost->Application[$i]->ApplicationInstance[1]->Client[$ii]->Proxy);

                            $array_espectadores[] = $ip . "|" . $tempo_conectado . "|" . $player . "";
                        }
                    }

                    $total_espectadores = count($xml->VHost->Application[$i]->ApplicationInstance[2]->Client);

                    for ($ii = 0; $ii < $total_espectadores; $ii++) {

                        $status_transmissao = status_aovivo($xml->VHost->Application[$i]->ApplicationInstance[2]->Client[$ii]->FlashVersion);

                        if ($status_transmissao != "aovivo") {

                            $ip = $xml->VHost->Application[$i]->ApplicationInstance[2]->Client[$ii]->IpAddress;
                            $tempo_conectado = $xml->VHost->Application[$i]->ApplicationInstance[2]->Client[$ii]->TimeRunning;
                            $player = formatar_useragent($xml->VHost->Application[$i]->ApplicationInstance[2]->Client[$ii]->Proxy);

                            $array_espectadores[] = $ip . "|" . $tempo_conectado . "|" . $player . "";
                        }
                    }

                    // Aplicacao webrtc
                } else {

                    $total_espectadores = $xml->VHost->Application[$i]->ApplicationInstance->RTPSessionCount;

                    for ($ii = 0; $ii < $total_espectadores; $ii++) {

                        $check_status_transmissao = status_aovivo($xml->VHost->Application[$i]->ApplicationInstance->RTPSession[$ii]->Mode);
                        if ($check_status_transmissao != "aovivo") {
                            $array_espectadores[] = $xml->VHost->Application[$i]->ApplicationInstance->RTPSession[$ii]->IpAddress . "|" . $xml->VHost->Application[$i]->ApplicationInstance->RTPSession[$ii]->TimeRunning . "|WebRTC";
                        }
                    }
                } // Aplicacao

                break;
            }
        }
    }

    return $array_espectadores;
}

// Fun��o para gravar transmiss�o ao vivo
function gravar_transmissao($ip,$senha,$login,$arquivo,$acao) {

//$opcoes = ($acao == "iniciar") ? "action=startRecordingSegmentByDuration&appName=".$login."%2F_definst_&streamName=".$login."&option=version&segmentSize=10&segmentDuration=01%3A01%3A00.000&output=/home/streaming/".$login."/record&outputFile=&fileTemplate=".$arquivo."_%24%7BSegmentNumber%7D.mp4&format=MP4" : "action=stopRecording&appName=".$login."%2F_definst_&streamName=".$login."&option=&segmentSize=&segmentDuration=&segmentSchedule=&format=&outputPath=&outputFile=&fileTemplate=&appFilter=";
$opcoes = ($acao == "iniciar") ? "action=startRecordingSegmentByDuration&app=".$login."&streamname=".$login."&outputPath=/home/streaming/".$login."/record&outputFile=&fileTemplate=".$arquivo."_%24%7BSegmentNumber%7D.mp4&format=2" : "action=stopRecording&app=".$login."&streamname=".$login."";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://".$ip.":555/livestreamrecord?".$opcoes."");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERPWD, "admin:".code_decode($senha,"D").""); 
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
curl_setopt($ch, CURLOPT_USERAGENT, 'Painel de Streaming 3.0.0');
ob_start();
$resultado = curl_exec($ch);
ob_get_clean();

return $resultado;

curl_close($ch);
}

// Fun��o para gerar arquivo de configura��o do Ondemand
function gerar_playlist($config) {

$conteudo .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$conteudo .= "<smil title=\"".$config["login"]."\">\n";
$conteudo .= "<head></head>\n";
$conteudo .= "<body>\n";
$conteudo .= "<stream name=\"".$config["login"]."\"></stream>\n\n";

if($config["playlists"]) {

foreach($config["playlists"] as $playlist_config) {

if($playlist_config["total_videos"] > 0) {

$conteudo .= "<playlist name=\"".$playlist_config["playlist"]."\" playOnStream=\"".$config["login"]."\" repeat=\"true\" scheduled=\"".$playlist_config["data_inicio"]."\">\n";

$start = ($playlist_config["start"]) ? $playlist_config["start"] : "0";

$lista_videos = explode(",",$playlist_config["videos"]);

foreach($lista_videos as $video) {
$video = str_replace("%20"," ",$video);
$conteudo .= "<video length=\"-1\" src=\"mp4:".$video."\" start=\"".$start."\"></video>\n";
}
$conteudo .= "</playlist>\n\n";

}

}

}

$conteudo .= "</body>\n";
$conteudo .= "</smil>\n";

$handle_playlist = fopen("/home/painelvideo/public_html/temp/".$config["login"]."_playlists_agendamentos.smil" ,"w");
fwrite($handle_playlist, $conteudo);
fclose($handle_playlist);

return $config["login"]."_playlists_agendamentos.smil";

}

// Fun��o de monitoramento contra ataques
function monitoramento_ataques() {

$headers = "";
$headers .= 'MIME-Version: 1.0'."\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
$headers .= 'From: Painel de Streaming <operador@painelcast.com>'."\r\n";
$headers .= 'To: operador@painelcast.com'."\r\n";
$headers .= "X-Sender: Painel de Streaming <operador@painelcast.com>\n";
$headers .= 'X-Mailer: PHP/' . phpversion();
$headers .= "X-Priority: 1\n";
$headers .= "Return-Path: operador@painelcast.com\n";

$mensagem = "";
$mensagem .= "==========================================<br>";
$mensagem .= "======== Tentativa de invas�o! ========<br>";
$mensagem .= "==========================================<br>";
$mensagem .= "IP: ".$_SERVER["REMOTE_ADDR"]."<br>";
$mensagem .= "Host: ".gethostbyaddr($_SERVER["REMOTE_ADDR"])."<br>";
$mensagem .= "Data: ".date("d/m/Y H:i:s")."<br>";
$mensagem .= "URI: ".$_SERVER['REQUEST_URI']."<br>";
$mensagem .= "==========================================<br>";
$mensagem .= "======== Informa��es Diversas ========<br>";
$mensagem .= "==========================================<br>";
$mensagem .= "".$_SERVER['HTTP_REFERER']."<br>";
$mensagem .= "".$_SERVER['HTTP_HOST']."<br>";
$mensagem .= "".$_SERVER['HTTP_USER_AGENT']."<br>";
$mensagem .= "".$_SERVER['QUERY_STRING']."<br>";
$mensagem .= "".$_SERVER['REQUEST_METHOD']."<br>";
$mensagem .= "==========================================";

mail("operador@painelcast.com","[Alerta] Tentativa de invas�o!",$mensagem,$headers);

}

// Fun��o abreviar o nome do navegador
function formatar_useragent($useragent) {

if(preg_match('/VLC/i',$useragent)) {
return  'VLC';
} elseif(preg_match('/ExoPlayer/i',$useragent)) {
return  'App Android';
} elseif(preg_match('/ExoMedia/i',$useragent)) {
return  'App Android';
} elseif(preg_match('/GoPlayer/i',$useragent)) {
return  'App Android';
} elseif(preg_match('/Linux/i',$useragent) && preg_match('/Android/i',$useragent)) {
return  'HTML5 Mobile';
} elseif(preg_match('/samsung-agent/i',$useragent)) {
return  'Smart TV';
} elseif(preg_match('/Roku/i',$useragent)) {
return  'Smart TV';
} elseif(preg_match('/SmartTV/i',$useragent)) {
return  'Smart TV';
} elseif(preg_match('/TV/i',$useragent)) {
return  'Smart TV';
} elseif(preg_match('/DTV/i',$useragent)) {
return  'Smart TV';
} elseif(preg_match('/Lavf/i',$useragent)) {
return  'App Android';
} elseif(preg_match('/stagefright/i',$useragent)) {
return  'App Android';
} elseif(preg_match('/NetCast/i',$useragent)) {
return  'Smart TV';
} elseif(preg_match('/Xtream-Codes/i',$useragent)) {
return  'Xtream-Codes';
} elseif(preg_match('/IPTV/i',$useragent)) {
return  'Smart TV';
} elseif(preg_match('/Kodi/i',$useragent)) {
return  'Smart TV';
} elseif(preg_match('/Chrome/i',$useragent)) {
return  'HTML5';
} elseif(preg_match('/Edg/i',$useragent)) {
return  'HTML5';
} elseif(preg_match('/Safari/i',$useragent)) {
return  'HTML5';
} elseif(preg_match('/AppleCoreMedia/i',$useragent)) {
return  'iOS';
} elseif(preg_match('/Nimble/i',$useragent)) {
return  'Nimble';
} else {
return 'HTML5';
}

}

// Fun��o para inserir elementos em uma array
function array_insert(&$array, $position, $insert)
{
    if (is_int($position)) {
        array_splice($array, $position, 0, $insert);
    } else {
        $pos   = array_search($position, array_keys($array));
        $array = array_merge(
            array_slice($array, 0, $pos),
            $insert,
            array_slice($array, $pos)
        );
    }
}

// Fun��o para transformar v�rias arrays dentro de uma mesma array em uma s� array
function flatten_array($array) {
    if (!is_array($array)) {
        // nothing to do if it's not an array
        return array($array);
    }

    $result = array();
    foreach ($array as $value) {
        // explode the sub-array, and add the parts
        $result = array_merge($result, flatten_array($value));
    }

    return $result;
}

// Calcula a diferen�a de horas entre 2 time zones
function get_timezone_offset($remote_tz, $origin_tz = null) {
    if($origin_tz === null) {
        if(!is_string($origin_tz = date_default_timezone_get())) {
            return false; // A UTC timestamp was returned -- bail out!
        }
    }
    $origin_dtz = new DateTimeZone($origin_tz);
    $remote_dtz = new DateTimeZone($remote_tz);
    $origin_dt = new DateTime("now", $origin_dtz);
    $remote_dt = new DateTime("now", $remote_dtz);
    $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
	
	$offset = $offset/3600;
    return $offset;
}

// Fun��o para formatar datas
function formatar_data($formato, $data, $timezone) {

$formato = (preg_match('/:/i',$data)) ? $formato : str_replace("H:i:s","",$formato);

$offset = get_timezone_offset('America/Sao_Paulo',$timezone);

$nova_data = strtotime ( ''.$offset.' hour' , strtotime ( $data ) ) ;
$nova_data = date ( $formato , $nova_data );

return $nova_data;

}

function isSSL() {

if( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' )
	return true;

if( !empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' )
	return true;

return false;
}

function date_diff_minutes( $date ) {

$first  = new DateTime( $date );
$second = new DateTime( "now" );

$diff = $first->diff( $second );

return $diff->format( '%I' );

}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

/////////////////////////////////////////////
//////////// Fun��es App Android ////////////
/////////////////////////////////////////////

// Fun��o para formatar o nome da radio retirando acentos e caracteres especiais
function formatar_nome_webtv($nome) {

$characteres = array(
    'S'=>'S', 's'=>'s', '�'=>'Dj','Z'=>'Z', 'z'=>'z', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A',
    '�'=>'A', '�'=>'A', '�'=>'C', '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'I', '�'=>'I', '�'=>'I',
    '�'=>'I', '�'=>'N', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'U', '�'=>'U',
    '�'=>'U', '�'=>'U', '�'=>'Y', '�'=>'B', '�'=>'Ss','�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a',
    '�'=>'a', '�'=>'a', '�'=>'c', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'i', '�'=>'i', '�'=>'i',
    '�'=>'i', '�'=>'o', '�'=>'n', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'u',
    '�'=>'u', '�'=>'u', '�'=>'y', '�'=>'y', '�'=>'b', '�'=>'y', 'f'=>'f', '�'=> '', '�'=> '', '&'=> 'e',
	'�'=> '', '�'=> '', '$'=> '', '%'=> '', '�'=> '', '�'=> '', '�'=> '', '�'=> '', '�'=> '', 'ã'=> '',
	'('=> '', ')'=> '', "'"=> '', '@'=> '', '='=> '', ':'=> '', '!'=> '', '?'=> '', '...'=> '', '�'=> '',
	'/'=> '', '�'=> '', '+'=> '', '*'=> '', '['=> '', ']'=> ''
);

return strtr($nome, $characteres);

}

// Fun��o para formatar o nome do app para o google play retirando acentos e caracteres especiais
function nome_app_play($texto) {

$characteres = array(
    'S'=>'S', 's'=>'s', '�'=>'Dj','Z'=>'Z', 'z'=>'z', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A',
    '�'=>'A', '�'=>'A', '�'=>'C', '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'I', '�'=>'I', '�'=>'I',
    '�'=>'I', '�'=>'N', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'U', '�'=>'U',
    '�'=>'U', '�'=>'U', '�'=>'Y', '�'=>'B', '�'=>'Ss','�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a',
    '�'=>'a', '�'=>'a', '�'=>'c', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'i', '�'=>'i', '�'=>'i',
    '�'=>'i', '�'=>'o', '�'=>'n', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'u',
    '�'=>'u', '�'=>'u', '�'=>'y', '�'=>'y', '�'=>'b', '�'=>'y', 'f'=>'f', '�'=> '', '�'=> '', '&'=> 'e',
	'�'=> '', '�'=> '', '$'=> '', '%'=> '', '�'=> '', '�'=> '', '�'=> '', '�'=> '', '�'=> '', 'ã'=> '',
	'('=> '', ')'=> '', "'"=> '', '@'=> '', '='=> '', ':'=> '', '!'=> '', '?'=> '', '...'=> '', ' '=> '',
	'-'=> '', '^'=> '', '~'=> '', '.'=> '', '|'=> '', ','=> '', '<'=> '', '>'=> '', '{'=> '', '}'=> '',
	'�'=> '', '/'=> '', '�'=> '', '+'=> '', '*'=> '', '['=> '', ']'=> ''
);

return strtolower(strtr($texto, $characteres));

}

// Fun��o para formatar o nome do apk do app retirando acentos e caracteres especiais
function nome_app_apk($texto) {

$characteres = array(
    'S'=>'S', 's'=>'s', '�'=>'Dj','Z'=>'Z', 'z'=>'z', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A',
    '�'=>'A', '�'=>'A', '�'=>'C', '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'I', '�'=>'I', '�'=>'I',
    '�'=>'I', '�'=>'N', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'U', '�'=>'U',
    '�'=>'U', '�'=>'U', '�'=>'Y', '�'=>'B', '�'=>'Ss','�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a',
    '�'=>'a', '�'=>'a', '�'=>'c', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'i', '�'=>'i', '�'=>'i',
    '�'=>'i', '�'=>'o', '�'=>'n', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'u',
    '�'=>'u', '�'=>'u', '�'=>'y', '�'=>'y', '�'=>'b', '�'=>'y', 'f'=>'f', '�'=> '', '�'=> '', '&'=> 'e',
	'�'=> '', '�'=> '', '$'=> '', '%'=> '', '�'=> '', '�'=> '', '�'=> '', '�'=> '', '�'=> '', 'ã'=> '',
	'('=> '', ')'=> '', "'"=> '', '@'=> '', '='=> '', ':'=> '', '!'=> '', '?'=> '', '...'=> '', ' '=> '',
	'-'=> '', '^'=> '', '~'=> '', '.'=> '', '|'=> '', ','=> '', '<'=> '', '>'=> '', '{'=> '', '}'=> '',
	' '=> '', '�'=> '', '/'=> '', '�'=> '', '+'=> '', '*'=> '', '['=> '', ']'=> ''
);

return strtr($texto, $characteres);

}

// Fun��o para copiar o source para o novo app
function copiar_source($DirFont, $DirDest) {
    
    mkdir($DirDest);
    if ($dd = opendir($DirFont)) {
        while (false !== ($Arq = readdir($dd))) {
            if($Arq != "." && $Arq != ".."){
                $PathIn = "$DirFont/$Arq";
                $PathOut = "$DirDest/$Arq";
                if(is_dir($PathIn)){
                    copiar_source($PathIn, $PathOut);
					chmod($PathOut,0777);
                }elseif(is_file($PathIn)){
                    copy($PathIn, $PathOut);
					chmod($PathOut,0777);
                }
            }
        }
        closedir($dd);
	}

}

// Fun��o para criar arquivos de configura��o do app
function criar_arquivo_config($arquivo,$conteudo) {

$fd = fopen ($arquivo, "w");
fputs($fd, $conteudo);
fclose($fd);

}

// Fun��o para carregar todos os arquivos e pastas de um diretorio
function browse($dir) {
global $filenames;
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && is_file($dir.'/'.$file)) {
                $filenames[] = $dir.'/'.$file;
            }
            else if ($file != "." && $file != ".." && is_dir($dir.'/'.$file)) {
                browse($dir.'/'.$file);
            }
        }
        closedir($handle);
    }
    return $filenames;
}

// Fun��o para substituir uma string dentro de um arquivo de texto
function replace($arquivo,$string_atual,$string_nova) {

//$str = implode("\n",file($arquivo));
//$fp = fopen($arquivo,'w');
//$str = str_replace($string_atual,$string_nova,$str);

//fwrite($fp,$str,strlen($str));

$str = file_get_contents($arquivo);
$str = str_replace($string_atual,$string_nova,$str);
file_put_contents($arquivo,$str);

}

// Fun��o para remover o source do novo app
function remover_source_app($Dir){
    
    if ($dd = @opendir($Dir)) {
        while (false !== ($Arq = @readdir($dd))) {
            if($Arq != "." && $Arq != ".."){
                $Path = "$Dir/$Arq";
                if(is_dir($Path)){
                    remover_source_app($Path);
                }elseif(is_file($Path)){
                    @unlink($Path);
                }
            }
        }
        @closedir($dd);
    }
    @rmdir($Dir);
}

// Fun��o para mudar a permiss�o de todos os arquivos e pasta no source do app
function mudar_permissao($Dir){

    if ($dd = opendir($Dir)) {
        while (false !== ($Arq = readdir($dd))) {
            if($Arq != "." && $Arq != ".."){
                $Path = "$Dir/$Arq";
                @chmod($Path,0777);
            }
        }
        closedir($dd);
    }

}

function youtube_parser($url) {

preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);

return $matches[1];
}

// Fun��o para recarregar as playlists no wowza sem reinicair streaming
function recarregar_playlists_agendamentos($ip,$senha,$login) {

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://".$ip.":555/schedules?appName=".$login."&action=reloadSchedule");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERPWD, "admin:".code_decode($senha,"D").""); 
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST); 
curl_setopt($ch, CURLOPT_USERAGENT, 'Painel de Streaming 3.0.0');
$resultado = curl_exec($ch);

$retry = 0;
while((curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200 || curl_errno($ch) != 0) && $retry < 10){
    $resultado = curl_exec($ch);
    $retry++;
	sleep(2);
}

if(preg_match('/DONE/i',$resultado)) {
return "ok";
} else {
return $resultado;
}

curl_close($ch);
}
// Fun��o para conectar a API de GEOIP
function api_geoip($ip) {

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://get.geojs.io/v1/ip/geo/".$ip.".json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($ch, CURLOPT_TIMEOUT, 2);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)');
$resultado = curl_exec($ch);
curl_close($ch);

$dados_geoip = json_decode($resultado);

$pais_codigo = ($dados_geoip->country_code) ? $dados_geoip->country_code : "desconhecido";
$pais_nome = ($dados_geoip->country) ? utf8_decode($dados_geoip->country) : "Desconhecido/Unknown";

return array("pais_sigla" => $pais_codigo, "pais_nome" => $pais_nome, "estado" => utf8_decode(addslashes($dados_geoip->region)), "cidade" => utf8_decode(addslashes($dados_geoip->city)), "latitude" => $dados_geoip->latitude , "longitude" => $dados_geoip->longitude );
}

function stripInvalidXml($value)
{
    $ret = "";
    $current;
    if (empty($value)) 
    {
        return $ret;
    }

    $length = strlen($value);
    for ($i=0; $i < $length; $i++)
    {
        $current = ord($value{$i});
        if (($current == 0x9) ||
            ($current == 0xA) ||
            ($current == 0xD) ||
            (($current >= 0x20) && ($current <= 0xD7FF)) ||
            (($current >= 0xE000) && ($current <= 0xFFFD)) ||
            (($current >= 0x10000) && ($current <= 0x10FFFF)))
        {
            $ret .= chr($current);
        }
        else
        {
            $ret .= " ";
        }
    }
    return $ret;
}
?>