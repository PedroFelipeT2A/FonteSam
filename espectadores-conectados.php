<?php
@mysqli_query($conexao,"CREATE TABLE IF NOT EXISTS `espectadores_conectados` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `codigo_stm` int(10) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `tempo_conectado` varchar(255) NOT NULL,
  `pais_sigla` varchar(255) NOT NULL,
  `pais_nome` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `estado` varchar(255) NOT NULL,
  `player` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL DEFAULT '0.0',
  `longitude` varchar(255) NOT NULL DEFAULT '0.0',
  PRIMARY KEY (`codigo`)
) ENGINE=MyISAM;");

@mysqli_query($conexao,"ALTER TABLE `estatisticas` ADD `cidade` VARCHAR( 255 ) NOT NULL, ADD `estado` VARCHAR( 255 ) NOT NULL;");
@mysqli_query($conexao,"ALTER TABLE `espectadores_conectados` ADD `atualizacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;");

// Fim funcoes internas


if(query_string('1') != '' && !is_numeric(query_string('1'))) {
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".code_decode(query_string('1'),"D")."'"));
} elseif(is_numeric(query_string('1'))) {
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".query_string('1')."'"));
} else {
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));
}
//echo "<pre>";
//var_dump(query_string('1'));
//echo "<hr><br>";
//var_dump($dados_stm);
//echo "</pre>";
//exit();

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM revendas where codigo = '".$dados_stm["codigo_cliente"]."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

if($dados_stm["idioma_painel"]) {
require_once("inc/lang-ec-".$dados_stm["idioma_painel"].".php");
} else {
require_once("inc/lang-ec-pt-br.php");
}

$status_streaming = status_streaming($dados_servidor["ip"],$dados_servidor["senha"],$dados_stm["login"]);

if ($status_streaming["status"] == "loaded") {

	$array_estatisticas = estatistica_espectadores_conectados($dados_servidor["ip"], $dados_servidor["senha"], $dados_stm["login"], $dados_stm["aplicacao"]);

	$estatisticas = $array_estatisticas;

	// Insere os espectadores conectados na tabela temporaria
	if (count($estatisticas) > 0) {
		$array_uniq_ips = array();

		foreach ($estatisticas as $estatistica) {

			list($ip, $tempo_conectado, $player) = explode("|", $estatistica);

			if (!in_array($ip, $array_uniq_ips)) {

				if (filter_var($ip, FILTER_VALIDATE_IP)) {

					// Verifica se ja tem no banco de dados de geoip e usa banco de dados ao invez da API
					$verifica_ip_db_atual = mysqli_num_rows(mysqli_query($conexao, "SELECT * FROM espectadores_conectados where ip = '" . $ip . "' AND codigo_stm = '" . $dados_stm["codigo"] . "'"));

					if ($verifica_ip_db_atual == 0) {

						$dados_api_geoip = api_geoip($ip);

						$ip_pais_codigo = $dados_api_geoip["pais_sigla"];
						$ip_pais_nome = $dados_api_geoip["pais_nome"];
						$ip_estado = $dados_api_geoip["estado"];
						$ip_cidade = $dados_api_geoip["cidade"];
						$latitude = $dados_api_geoip["latitude"];
            $longitude = $dados_api_geoip["longitude"];
	          mysqli_query($conexao, "INSERT INTO espectadores_conectados (codigo_stm,ip,tempo_conectado,pais_sigla,pais_nome,cidade,estado,player,latitude,longitude) VALUES ('" . $dados_stm["codigo"] . "','" . $ip . "','" . $tempo_conectado . "','" . $ip_pais_codigo . "','" . $ip_pais_nome . "','" . $ip_estado . "','" . $ip_cidade . "','" . $player . "','" . $latitude . "','" . $longitude . "')");
					} else {
						mysqli_query($conexao, "Update espectadores_conectados set tempo_conectado = '" . $tempo_conectado . "', atualizacao = NOW() where ip = '" . $ip . "' AND codigo_stm = '" . $dados_stm["codigo"] . "'");
					} // fim if verifica ip "cache" banco de dados

					$array_uniq_ips[] = $ip;
				}
			}

			$get_Avg = mysqli_fetch_array(mysqli_query($conexao, "SELECT AVG(tempo_conectado) AS tempo FROM espectadores_conectados WHERE codigo_stm = '" . $dados_stm["codigo"] . "'"));
			$total_espectadores = mysqli_num_rows(mysqli_query($conexao, "SELECT * FROM espectadores_conectados WHERE codigo_stm = '" . $dados_stm["codigo"] . "'"));
		}
	}
} else {

	die('<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:5px; background-color:#FFFF66; border:#DFDF00 1px solid">
	<tr>
		<td width="30" height="25" align="center" scope="col"><img src="/img/icones/atencao.png" width="16" height="16" /></td>
    	<td align="left" scope="col" style="color: #AB1C10;	font-family: Geneva, Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold;">' . $lang['lang_info_espectadores_conectados_alerta_streaming_desligado'] . '</td>
  	</tr>
</table>');
}

$COUNTRY_LAT_LANG = array("AD" => array(42.50, 1.50), "AE" => array(24.00, 54.00), "AF" => array(33.00, 65.00), "AG" => array(17.05, -61.80), "AI" => array(18.22, -63.05), "AL" => array(41.00, 20.00), "AM" => array(40.00, 45.00), "AN" => array(12.17, -69.00), "AO" => array(-12.50, 18.50), "AQ" => array(-77.85, 166.67), "AR" => array(-34.00, -64.00), "AS" => array(-14.32, -170.50), "AT" => array(47.33, 13.33), "AU" => array(-25.00, 135.00), "AW" => array(12.50, -69.97), "AX" => array(60.12, 19.90), "AZ" => array(40.50, 47.50), "BA" => array(44.25, 17.83), "BB" => array(13.17, -59.53), "BD" => array(24.00, 90.00), "BE" => array(50.83, 4.00), "BF" => array(13.00, -2.00), "BG" => array(43.00, 25.00), "BH" => array(26.00, 50.50), "BI" => array(-3.50, 30.00), "BJ" => array(9.50, 2.25), "BM" => array(32.33, -64.75), "BN" => array(4.50, 114.67), "BO" => array(-17.00, -65.00), "BR" => array(-10.00, -55.00), "BS" => array(24.00, -76.00), "BT" => array(27.50, 90.50), "BV" => array(-54.43, 3.40), "BW" => array(-22.00, 24.00), "BY" => array(53.00, 28.00), "BZ" => array(17.25, -88.75), "CA" => array(60.00, -96.00), "CC" => array(-12.17, 96.83), "CD" => array(-3.52, 23.42), "CF" => array(7.00, 21.00), "CG" => array(-1.00, 15.00), "CH" => array(47.00, 8.00), "CI" => array(8.00, -5.00), "CK" => array(-20.00, -158.00), "CL" => array(-30.00, -71.00), "CM" => array(6.00, 12.00), "CN" => array(35.00, 105.00), "CO" => array(4.00, -72.00), "CR" => array(10.00, -84.00), "CS" => array(44.8, 20.5), "CU" => array(21.50, -80.00), "CV" => array(16.00, -24.00), "CX" => array(-10.50, 105.67), "CY" => array(35.00, 33.00), "CZ" => array(49.75, 15.00), "DE" => array(51.50, 10.50), "DJ" => array(11.50, 42.50), "DK" => array(56.00, 10.00), "DM" => array(15.50, -61.33), "DO" => array(19.00, -70.67), "DZ" => array(28.00, 3.00), "EC" => array(-2.00, -77.50), "EE" => array(59.00, 26.00), "EG" => array(27.00, 30.00), "EH" => array(23.00, -14.00), "ER" => array(15.00, 39.00), "ES" => array(40.00, -4.00), "ET" => array(8.00, 39.00), "FI" => array(64.00, 26.00), "FJ" => array(-18.00, 178.00), "FK" => array(-51.75, -59.00), "FM" => array(5.00, 152.00), "FO" => array(62.00, -7.00), "FR" => array(46.00, 2.00), "FX" => array(48.87, 2.33), "GA" => array(-1.00, 11.75), "GB" => array(54.00, -4.50), "GD" => array(12.12, -61.67), "GE" => array(42.00, 43.50), "GF" => array(4.00, -53.00), "GG" => array(49.45, -2.55), "GH" => array(8.00, -2.00), "GI" => array(36.13, -5.35), "GL" => array(72.00, -40.00), "GM" => array(13.50, -15.50), "GN" => array(11.00, -10.00), "GP" => array(16.25, -61.58), "GQ" => array(2.00, 10.00), "GR" => array(39.00, 22.00), "GS" => array(-54.50, -37.00), "GT" => array(15.50, -90.25), "GU" => array(13.47, 144.83), "GW" => array(12.00, -15.00), "GY" => array(5.00, -59.00), "HK" => array(22.25, 114.17), "HM" => array(-53.10, 73.52), "HN" => array(15.00, -86.50), "HR" => array(45.17, 15.50), "HT" => array(19.00, -72.42), "HU" => array(47.00, 20.00), "ID" => array(-5.00, 120.00), "IE" => array(53.00, -8.00), "IL" => array(31.50, 34.75), "IM" => array(54.23, -4.55), "IN" => array(20.00, 77.00), "IO" => array(-6.00, 71.50), "IQ" => array(33.00, 44.00), "IR" => array(32.00, 53.00), "IS" => array(65.00, -18.00), "IT" => array(42.83, 12.83), "JE" => array(49.19, -2.11), "JM" => array(18.25, -77.50), "JO" => array(31.00, 36.00), "JP" => array(36.00, 138.00), "KE" => array(1.00, 38.00), "KG" => array(41.00, 75.00), "KH" => array(13.00, 105.00), "KI" => array(-5.00, -170.00), "KM" => array(-12.17, 44.25), "KN" => array(17.33, -62.75), "KP" => array(40.00, 127.00), "KR" => array(37.00, 127.50), "KW" => array(29.50, 47.75), "KY" => array(19.50, -80.67), "KZ" => array(48.00, 68.00), "LA" => array(18.00, 105.00), "LB" => array(33.83, 35.83), "LC" => array(13.88, -60.97), "LI" => array(47.17, 9.53), "LK" => array(7.00, 81.00), "LR" => array(6.50, -9.50), "LS" => array(-29.50, 28.25), "LT" => array(56.00, 24.00), "LU" => array(49.75, 6.17), "LV" => array(57.00, 25.00), "LY" => array(25.00, 17.00), "MA" => array(32.00, -5.00), "MC" => array(43.73, 7.42), "MD" => array(47.00, 29.00), "ME" => array(42.80, 19.20), "MG" => array(-20.00, 47.00), "MH" => array(11.00, 168.00), "MK" => array(41.83, 22.00), "ML" => array(17.00, -4.00), "MM" => array(22.00, 98.00), "MN" => array(46.00, 105.00), "MO" => array(22.00, 113.00), "MP" => array(15.12, 145.67), "MQ" => array(14.67, -61.00), "MR" => array(20.00, -12.00), "MS" => array(16.75, -62.20), "MT" => array(35.92, 14.42), "MU" => array(-20.30, 57.58), "MV" => array(3.20, 73.00), "MW" => array(-13.50, 34.00), "MX" => array(23.00, -102.00), "MY" => array(4.22, 101.97), "MZ" => array(-18.25, 35.00), "NA" => array(-22.00, 17.00), "NC" => array(-21.50, 165.50), "NE" => array(16.00, 8.00), "NF" => array(-29.08, 167.92), "NG" => array(10.00, 8.00), "NI" => array(13.00, -85.00), "NL" => array(52.50, 5.75), "NO" => array(62.00, 10.00), "NP" => array(28.00, 84.00), "NR" => array(-0.53, 166.92), "NU" => array(-19.03, -169.87), "NZ" => array(-42.00, 174.00), "OM" => array(21.00, 57.00), "PA" => array(9.00, -80.00), "PE" => array(-10.00, -76.00), "PF" => array(-15.00, -140.00), "PG" => array(-6.00, 147.00), "PH" => array(13.00, 122.00), "PK" => array(30.00, 70.00), "PL" => array(52.00, 20.00), "PM" => array(46.83, -56.33), "PN" => array(-25.07, -130.08), "PR" => array(18.23, -66.55), "PS" => array(32.0000, 35.2500), "PT" => array(39.50, -8.00), "PW" => array(6.00, 134.00), "PY" => array(-23.00, -58.00), "QA" => array(25.50, 51.25), "RE" => array(-21.10, 55.60), "RO" => array(46.00, 25.00), "RS" => array(43.80, 21.00), "RU" => array(60.00, 47.00), "RW" => array(-2.00, 30.00), "SA" => array(25.00, 45.00), "SB" => array(-8.00, 159.00), "SC" => array(-4.58, 55.67), "SD" => array(15.00, 30.00), "SE" => array(62.00, 15.00), "SG" => array(1.37, 103.80), "SH" => array(-15.95, -5.70), "SI" => array(46.25, 15.17), "SJ" => array(78.00, 20.00), "SK" => array(48.67, 19.50), "SL" => array(8.50, -11.50), "SM" => array(43.93, 12.42), "SN" => array(14.00, -14.00), "SO" => array(6.00, 48.00), "SR" => array(4.00, -56.00), "ST" => array(1.00, 7.00), "SU" => array(60.00, 47.00), "SV" => array(13.83, -88.92), "SY" => array(35.00, 38.00), "SZ" => array(-26.50, 31.50), "TC" => array(21.73, -71.58), "TD" => array(15.00, 19.00), "TF" => array(-43.00, 67.00), "TG" => array(8.00, 1.17), "TH" => array(15.00, 100.00), "TJ" => array(39.00, 71.00), "TK" => array(-9.00, -171.75), "TL" => array(-8.87, 125.72), "TM" => array(40.00, 60.00), "TN" => array(34.00, 9.00), "TO" => array(-20.00, -175.00), "TP" => array(-9.00, 125.00), "TR" => array(39.00, 35.00), "TT" => array(11.00, -61.00), "TV" => array(-8.00, 178.00), "TW" => array(23.50, 121.00), "TZ" => array(-6.00, 35.00), "UA" => array(49.00, 32.00), "UG" => array(2.00, 33.00), "UM" => array(10.00, -175.00), "US" => array(38.00, -98.00), "UY" => array(-33.00, -56.00), "UZ" => array(41.00, 64.00), "VA" => array(41.90, 12.45), "VC" => array(13.08, -61.20), "VE" => array(8.00, -66.00), "VG" => array(18.50, -64.50), "VI" => array(18.50, -64.43), "VN" => array(16.00, 106.00), "VU" => array(-16.00, 167.00), "WF" => array(-14.00, -177.00), "WS" => array(-13.58, -172.33), "YE" => array(15.50, 47.50), "YT" => array(-12.83, 45.17), "YU" => array(44.00, 21.00), "ZA" => array(-30.00, 26.00), "ZM" => array(-15.00, 30.00), "ZR" => array(-1.00, 22.00), "ZW" => array(-19.00, 29.00));

/////////////////////////////////////////////////
/////////////////// Idioma //////////////////////
/////////////////////////////////////////////////
if($dados_stm["idioma_painel"] == "pt-br") {
$lang[ 'lang_info_mapa_filtrar_pais' ] = 'Filtrar por Pa�s' ;
$lang[ 'lang_info_mapa_filtrar_estado' ] = 'Filtrar por Estado' ;
$lang[ 'lang_info_mapa_geral' ] = 'Mapa Geral' ;
$lang[ 'lang_info_espectadores_conectados_localidade' ] = 'Localidade' ;
} else if($dados_stm["idioma_painel"] == "en") {
$lang[ 'lang_info_mapa_filtrar_pais' ] = 'Filter by Country' ;
$lang[ 'lang_info_mapa_filtrar_estado' ] = 'Filter by State' ;
$lang[ 'lang_info_mapa_geral' ] = 'General Map' ;
$lang[ 'lang_info_espectadores_conectados_localidade' ] = 'Location' ;
} else {
$lang[ 'lang_info_mapa_filtrar_pais' ] = 'Filtro por Pais' ;
$lang[ 'lang_info_mapa_filtrar_estado' ] = 'Filtro por Departamento' ;
$lang[ 'lang_info_mapa_geral' ] = 'Mapa General' ;
$lang[ 'lang_info_espectadores_conectados_localidade' ] = 'Ubicaci�n' ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="cache-control" content="no-cache">
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
<link href="/inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<link href="inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
<script type="text/javascript" src="/inc/javascript.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-C6RHVB41C9"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-C6RHVB41C9');
</script>
<!--[if IE]><script type="text/javascript" src="/inc/excanvas.js"></script><![endif]-->
<script type="text/javascript">
   window.onload = function() {
    setTimeout("window.location.reload(true);",120000);
	fechar_log_sistema();
	// Status de exibi��o dos quadros
	document.getElementById('tabela_info').style.display=getCookie('tabela_info');
	document.getElementById('tabela_paises').style.display=getCookie('tabela_paises');
	document.getElementById('tabela_players').style.display=getCookie('tabela_players');
	document.getElementById('tabela_espectadores_conectados').style.display=getCookie('tabela_espectadores_conectados');
	document.getElementById('tabela_mapa_espectadores_conectados').style.display=getCookie('tabela_mapa_espectadores_conectados');
	document.getElementById('tabela_grafico_espectadores_conectados').style.display=getCookie('tabela_grafico_espectadores_conectados');
   };
</script>
<style>
#container_espectador {
	width:255px;
	border:#D5D5D5 1px solid;
	float:left;
	margin-bottom:5px;
	margin-right:5px;
}
#container_espectador_pais {
	background-color:#F8F8F8;
	width:250px;
	height:20px;
	margin:0px auto;
	text-align:left;
	padding-left:5px;
	padding-top:10px;
	padding-bottom:10px;
}
#container_espectador_dados {
	width:255px;
	margin:0px auto;
	padding-left:5px;
}
#container_espectador_separador {
	width:100%;
	height:5px;
	margin:0px auto;
	border-bottom:#D5D5D5 1px solid;
}
</style>
</head>

<body>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;">
	  <tr>
        <td height="30" align="left" class="texto_padrao_destaque"><div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_espectadores_conectados_trans_atual_tab_titulo']; ?></strong>
          <span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" align="absmiddle" onclick="hide_show('tabela_info');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span></div>
          <div class="texto_medio" id="quadro-conteudo">
              <table width="788" border="0" cellspacing="0" cellpadding="0" align="center" id="tabela_info" style="display:block">
                <tr>
                  <td width="394" height="70" align="center" class="texto_padrao_pequeno"><img src="/img/icones/img-icone-espectadores.png" width="48" height="48" /></td>
                  <td width="394" align="center" class="texto_padrao_pequeno"><img src="/img/icones/img-icone-agendamento.png" width="48" height="48" /></td>
                </tr>
                <tr>
                  <td height="30" align="center" class="texto_padrao_pequeno"><?php echo $total_espectadores; ?><br /><?php echo $lang['lang_info_espectadores_conectados_espectadores_total']; ?></td>
                  <td align="center" class="texto_padrao_pequeno"><?php echo seconds2time($get_Avg["tempo"]);?><br />                    
                  <?php echo $lang['lang_info_espectadores_conectados_espectadores_average']; ?></td>
                </tr>
              </table>
          </div>
        </div></td>
      </tr>
    </table>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;">
	<tr>
        <td width="395" valign="top"><div id="quadro">
          <div id="quadro-topo"><strong><?php echo $lang['lang_info_espectadores_conectados_paises_tab_titulo']; ?></strong><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_paises');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span></div>
          <div class="texto_padrao_pequeno" id="quadro-conteudo">
            <table width="380" border="0" align="center" cellpadding="0" cellspacing="0" id="tabela_paises" style="display:block">
<tr>
<?php
if(count($estatisticas) > 0) {
$count = 0;

$sql_paises = mysqli_query($conexao,"SELECT *, count(pais_sigla) as total FROM espectadores_conectados WHERE codigo_stm = '".$dados_stm["codigo"]."' AND pais_sigla != 'Not found' GROUP by pais_sigla ORDER by total DESC LIMIT 6");
while ($dados_pais = mysqli_fetch_array($sql_paises)) {

if(isset($dados_pais["pais_nome"])) {

if(!($count % 2)){ echo "<tr></tr>"; }

echo '<td width="190" height="25" align="left" class="texto_padrao_pequeno" scope="col">&nbsp;<img src="/img/icones/paises/'.strtolower($dados_pais["pais_sigla"]).'.png" border="0" width="16" height="11" align="absmiddle" />&nbsp;&nbsp;'.$dados_pais["pais_nome"].'&nbsp;&nbsp;('.$dados_pais["total"].')</td>';

$count++;
}

}
} else {
echo '<td height="25" align="center" class="texto_padrao_pequeno" scope="col" style="color: #AB1C10;	font-family: Geneva, Arial, Helvetica, sans-serif; font-size:10px; font-weight:bold;">'.$lang['lang_info_espectadores_conectados_alerta_streaming_desligado'].'</td>';
}
?>
</tr>
            </table>
          </div>
        </div></td>
	    <td width="10">&nbsp;</td>
	    <td width="395" valign="top"><div id="quadro">
                <div id="quadro-topo"> <strong><?php echo $lang['lang_info_espectadores_conectados_players_tab_titulo']; ?></strong><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_players');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span></div>
        <div class="texto_padrao_pequeno" id="quadro-conteudo">
          <table width="380" border="0" align="center" cellpadding="0" cellspacing="0" id="tabela_players" style="display:block">
<tr>
<?php
if(count($estatisticas) > 0) {
$count = 0;

$sql_player = mysqli_query($conexao,"SELECT *, count(player) as total FROM espectadores_conectados WHERE codigo_stm = '".$dados_stm["codigo"]."' AND pais_sigla != 'Not found' GROUP by player ORDER by total DESC LIMIT 6");
while ($dados_player = mysqli_fetch_array($sql_player)) {

$player_icone = (file_exists("img/icones/players/".str_replace(" ","",$dados_player["player"]).".png")) ? str_replace(" ","",$dados_player["player"]) : "Outro";

if(!($count % 2)){ echo "<tr></tr>"; }

echo '<td width="190" height="25" align="left" class="texto_padrao_pequeno" scope="col">&nbsp;<img src="/img/icones/players/'.$player_icone.'.png" border="0" width="16" height="16" align="absmiddle" />&nbsp;&nbsp;'.$dados_player["player"].'&nbsp;&nbsp;('.$dados_player["total"].')</td>';

$count++;
}

} else {
echo '<td height="25" align="center" class="texto_padrao_pequeno" scope="col" style="color: #AB1C10;	font-family: Geneva, Arial, Helvetica, sans-serif; font-size:10px; font-weight:bold;">'.$lang['lang_info_espectadores_conectados_alerta_streaming_desligado'].'</td>';
}
?>
</tr>
            </table>
        </div>
        </div>
        </td>
	</tr>
</table>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;">
	  <tr>
        <td height="30" align="left" class="texto_padrao_destaque"><div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_espectadores_conectados_tab_titulo']; ?></strong><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" align="absmiddle" onclick="hide_show('tabela_espectadores_conectados');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span></div>
          <div class="texto_medio" id="quadro-conteudo">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" id="tabela_espectadores_conectados" style="display:block">
                <tr>
                  <td>
                  <table width="788" border="0" align="center" cellpadding="0" cellspacing="0" style="border-top:#D5D5D5 1px solid; border-left:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid; border-bottom:#D5D5D5 1px solid;" id="tab" class="sortable">
  					<tr style="background:url(/img/img-fundo-titulo-tabela.png) repeat-x; cursor:pointer">
  					<td width="170" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;<?php echo $lang['lang_info_espectadores_conectados_ip']; ?></td>
  					<td width="375" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;<?php echo $lang['lang_info_espectadores_conectados_pais']; ?></td>
  					<td width="119" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;<?php echo $lang['lang_info_espectadores_conectados_player']; ?></td>
  					<td width="122" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid;">&nbsp;<?php echo $lang['lang_info_espectadores_conectados_tempo_conectado']; ?></td>
  				</tr>
<?php
if(count($estatisticas) > 0) {

$sql_espectadores = mysqli_query($conexao,"SELECT * FROM espectadores_conectados WHERE codigo_stm = '".$dados_stm["codigo"]."' ORDER by ip ASC");
while ($dados_espectador = mysqli_fetch_array($sql_espectadores)) {

$localidade .= ($dados_espectador["estado"]) ? " - ".$dados_espectador["estado"]."" : "";
$localidade .= ($dados_espectador["cidade"]) ? " - ".$dados_espectador["cidade"]."" : "";

$player_icone = (file_exists("img/icones/players/".str_replace(" ","",$dados_espectador["player"]).".png")) ? str_replace(" ","",$dados_espectador["player"]) : "Outro";

echo "
  <tr>
    <td height='23' class='texto_padrao'>&nbsp;".$dados_espectador["ip"]."</td>
    <td height='23' class='texto_padrao'>&nbsp;<img src='/img/icones/paises/".strtolower($dados_espectador["pais_sigla"]).".png' border='0' align='absmiddle' />&nbsp;".$dados_espectador["pais_nome"]."".$localidade."</td>
    <td height='23' class='texto_padrao'>&nbsp;<img src='/img/icones/players/".$player_icone.".png' border='0' width='16' height='16' align='absmiddle' />&nbsp;".$dados_espectador["player"]."</td>
	<td height='23' class='texto_padrao'>&nbsp;".seconds2time($dados_espectador["tempo_conectado"])."</td>
  </tr>
";
unset($localidade);
}

} else {

echo "
  <tr>
    <td height='30' colspan='4' align='center' class='texto_status_erro'>".$lang['lang_info_espectadores_conectados_info_sem_ouvintes']."</td>
  </tr>
";

}
?>
			</table>
                  </td>
                </tr>
            </table>
          </div>
        </div></td>
      </tr>
</table>
    <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;">
	  <tr>
        <td height="30" align="left" class="texto_padrao_destaque"><div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_espectadores_conectados_mapa_tab_titulo']; ?></strong>
          <span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" align="absmiddle" onclick="hide_show('tabela_mapa_espectadores_conectados');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span></div>
          <div class="texto_medio" id="quadro-conteudo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" id="tabela_mapa_espectadores_conectados" style="display:block">
                <tr>
                  <td height="25" class="texto_padrao_pequeno" align="center">
                  <div id="mapa_espectadores" style="width:790px; height: 400px; margin: 0 auto"></div>
                  </td>
                </tr>
                <tr>
                  <td height="25" class="texto_padrao_pequeno" align="left">
                  <select class="input" style="width:50%;height:40px" onchange="filtrar_mapa(this.value);">
                    <option hidden selected ><?php echo $lang['lang_info_mapa_filtrar_pais']; ?></option>
                    <option value="0,0,2"><?php echo $lang['lang_info_mapa_geral']; ?></option>
                    <?php
                      if(count($estatisticas) > 0) {

                        $sql_mapa_pais = mysqli_query($conexao,"SELECT *, count(pais_sigla) as total FROM espectadores_conectados WHERE codigo_stm = '".$dados_stm["codigo"]."' GROUP by pais_sigla ORDER by total DESC");
                        while ($dados_mapa_pais = mysqli_fetch_array($sql_mapa_pais)) {

                          if($COUNTRY_LAT_LANG["".$dados_mapa_pais["pais_sigla"].""][0] && $COUNTRY_LAT_LANG["".$dados_mapa_pais["pais_sigla"].""][1]) {
                            echo '<option value="'.$COUNTRY_LAT_LANG["".$dados_mapa_pais["pais_sigla"].""][0].','.$COUNTRY_LAT_LANG["".$dados_mapa_pais["pais_sigla"].""][1].',4">'.$dados_mapa_pais["pais_nome"].' ('.$dados_mapa_pais["total"].')</option>';
                          }
                        }

                      }
                      ?>
                  </select>&nbsp;
                  <select class="input" style="width:49%;height:40px" onchange="filtrar_mapa(this.value);">
                    <option hidden selected value="0,0,2"><?php echo $lang['lang_info_mapa_filtrar_estado']; ?></option>
                    <option value="0,0,2"><?php echo $lang['lang_info_mapa_geral']; ?></option>
                    <?php
                      if(count($estatisticas) > 0) {

                        $sql_mapa_estado = mysqli_query($conexao,"SELECT *, count(estado) as total FROM espectadores_conectados WHERE codigo_stm = '".$dados_stm["codigo"]."' GROUP by estado ORDER by total DESC");
                        while ($dados_mapa_estado = mysqli_fetch_array($sql_mapa_estado)) {

                          if($dados_mapa_estado["latitude"] && $dados_mapa_estado["longitude"] && $dados_mapa_estado["estado"]) {
                            echo '<option value="'.$dados_mapa_estado["latitude"].','.$dados_mapa_estado["longitude"].',6">'.$dados_mapa_estado["estado"].' ('.$dados_mapa_estado["total"].')</option>';
                          }
                        }

                      }
                      ?>
                  </select>
                  </td>
                </tr>
              </table>
          </div>
        </div></td>
      </tr>
    </table>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;">
	  <tr>
        <td height="30" align="left" class="texto_padrao_destaque"><div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_espectadores_conectados_estatisticas_tab_titulo']; ?></strong>
          <span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" align="absmiddle" onclick="hide_show('tabela_grafico_espectadores_conectados');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span></div>
          <div class="texto_medio" id="quadro-conteudo">
              <table width="788" border="0" cellspacing="0" cellpadding="0" align="center" id="tabela_grafico_espectadores_conectados" style="display:block">
                <tr>
                  <td height="25" class="texto_padrao_pequeno" align="center">
                  <div id="container" style="width:780px; height: 350px; margin: 0 auto"></div>
                  </td>
                </tr>
              </table>
          </div>
        </div></td>
      </tr>
    </table>
<br />
<script type="text/javascript">
var locations = [
<?php
if(count($estatisticas) > 0) {

$sql_espectadors = mysqli_query($conexao,"SELECT * FROM espectadores_conectados WHERE codigo_stm = '".$dados_stm["codigo"]."' ORDER by ip ASC");
while ($dados_espectador = mysqli_fetch_array($sql_espectadors)) {

$latitude = $dados_espectador["latitude"];
$longitude = $dados_espectador["longitude"];

$player_icone = (file_exists("img/icones/players/".str_replace(" ","",$dados_espectador["player"]).".png")) ? str_replace(" ","",$dados_espectador["player"]) : "Outro";

echo "['<strong>".$lang['lang_info_espectadores_conectados_espectador'].":</strong> ".$dados_espectador["ip"]."<br><strong>".$lang['lang_info_espectadores_conectados_localidade'].":</strong> ".addslashes($dados_espectador["estado"])." - ".addslashes($dados_espectador["cidade"])."<br><strong>Player:</strong> <img src=\"/img/icones/players/".$player_icone.".png\" border=\"0\" width=\"16\" height=\"16\" align=\"absmiddle\" />&nbsp;".$dados_espectador["player"]."', ".$latitude.", ".$longitude."],";

}

}
?>];

  const map = L.map('mapa_espectadores').setView([0, 0], 2);

  const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(map);

  for (var i = 0; i < locations.length; i++) {
    marker = new L.marker([locations[i][1], locations[i][2]])
      .bindPopup(locations[i][0])
      .addTo(map);
  }

// Graficos
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'area'
            },
            title: {
                text: '<?php echo $lang['lang_info_espectadores_conectados_estatisticas_titulo']; ?>'
            },
			subtitle: {
                text: '<?php echo formatar_data($dados_stm["formato_data"], date("Y-m-d",mktime (0, 0, 0, date("m")  , date("d")-1, date("Y"))), $dados_stm["timezone"]). " - " . formatar_data($dados_stm["formato_data"], date("Y-m-d"), $dados_stm["timezone"]);?>'
            },
            xAxis: {
                categories: ['00:00-00:59','01:00-01:59','02:00-02:59','03:00-03:59','04:00-04:59','05:00-05:59','06:00-06:59','07:00-07:59','08:00-08:59','09:00-09:59','10:00-10:59','11:00-11:59','12:00-12:59','13:00-13:59','14:00-14:59','15:00-15:59','16:00-16:59','17:00-17:59','18:00-18:59','19:00-19:59','20:00-20:59','21:00-21:59','22:00-22:59','23:00-23:59'],
                tickmarkPlacement: 'on',
                title: {
                    enabled: true,
					text: '<?php echo $lang['lang_info_espectadores_conectados_estatisticas_info_hora']; ?>'
                }
            },
            yAxis: {
                title: {
                    text: '<?php echo $lang['lang_info_espectadores_conectados_estatisticas_total_espectadores']; ?>'
                },
                labels: {
                    formatter: function() {
                        return this.value;
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    return ''+this.x+': '+ Highcharts.numberFormat(this.y, 0, ',') +' <?php echo $lang['lang_info_espectadores_conectados_estatisticas_balao_espectadores']; ?>';
                }
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
					cursor: 'pointer',
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666',
						enabled: false,
                    	symbol: 'circle',
                    	radius: 2,
                    	states: {
                        	hover: {
                            enabled: true
                        	}
						}
                    }
                }
            },
            series: [{
                name: '<?php echo $lang['lang_info_espectadores_conectados_estatisticas_total_espectadores_ontem']; ?>',
                data: [<?php
				
				$data_ontem = date("Y-m-d",mktime (0, 0, 0, date("m")  , date("d")-1, date("Y")));
				
				for($i=0;$i<=23;$i++){
				
				$hora = sprintf("%02s",$i);
				
				$total_espectadores = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM estatisticas where codigo_stm = '".$dados_stm["codigo"]."' AND data = '".$data_ontem."' AND HOUR(hora) = '".$hora."'"));
				
				$array_total_espectadores .= $total_espectadores.",";
				
				}
				echo substr($array_total_espectadores, 0, -1);	
				
				unset($array_total_espectadores);
				unset($total_espectadores);
				?>]
				}, {
				name: '<?php echo $lang['lang_info_espectadores_conectados_estatisticas_total_espectadores_hoje']; ?>',
                data: [<?php
				
				for($i=0;$i<=23;$i++){
				
				$hora = sprintf("%02s",$i);
				
				$total_espectadores = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM estatisticas where codigo_stm = '".$dados_stm["codigo"]."' AND data = '".date("Y-m-d")."' AND HOUR(hora) = '".$hora."'"));
				
				$array_total_espectadores .= $total_espectadores.",";
				
				}
				echo substr($array_total_espectadores, 0, -1);	
				
				unset($array_total_espectadores);
				unset($total_espectadores);
				?>]
            }]
        });
    });
    
});

function filtrar_mapa(coord) {

const coords = coord.split(',');
map.flyTo([coords[0], coords[1]], coords[2]);

}
</script>
<!-- In�cio div log do sistema -->
<div id="log-sistema-fundo"></div>
<div id="log-sistema">
<div id="log-sistema-botao"><img src="/img/icones/img-icone-fechar.png" onclick="document.getElementById('log-sistema-fundo').style.display = 'none';document.getElementById('log-sistema').style.display = 'none';" style="cursor:pointer" title="<?php echo $lang['lang_titulo_fechar']; ?>" /></div>
<div id="log-sistema-conteudo"><img src="/img/ajax-loader.gif" /></div>
</div>
<!-- Fim div log do sistema -->
</body>
</html>
<?php
// Apaga os espectadores antigos
mysqli_query($conexao,"Delete From espectadores_conectados where atualizacao < (NOW() - INTERVAL 2 MINUTE)");
?>