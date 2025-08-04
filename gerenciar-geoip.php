<?php
require_once("admin/inc/protecao-final.php");
require_once("admin/inc/classe.ssh.php");

@mysqli_query($conexao,"ALTER TABLE `streamings` ADD `geoip_ativar` CHAR(3) NOT NULL DEFAULT 'nao'");
@mysqli_query($conexao,"ALTER TABLE `streamings` ADD `geoip_paises_bloqueados` TEXT NOT NULL AFTER `geoip_ativar`");
@mysqli_query($conexao,"CREATE TABLE IF NOT EXISTS `geoip_paises`( `codigo` int(10) NOT NULL AUTO_INCREMENT, `sigla` char(2) NOT NULL, `nome` varchar(255) NOT NULL, PRIMARY KEY (`codigo`)) ENGINE=MyISAM");
@mysqli_query($conexao,"INSERT INTO `geoip_paises` (`codigo`, `sigla`, `nome`) VALUES (1, 'AF', 'Afghanistan'), (2, 'AX', 'Aland Islands'), (3, 'AL', 'Albania'), (4, 'DZ', 'Algeria'), (5, 'AS', 'American Samoa'), (6, 'AD', 'Andorra'), (7, 'AO', 'Angola'), (8, 'AI', 'Anguilla'), (9, 'AQ', 'Antarctica'), (10, 'AG', 'Antigua and Barbuda'), (11, 'AR', 'Argentina'), (12, 'AM', 'Armenia'), (13, 'AW', 'Aruba'), (14, 'AU', 'Australia'), (15, 'AT', 'Austria'), (16, 'AZ', 'Azerbaijan'), (17, 'BS', 'Bahamas'), (18, 'BH', 'Bahrain'), (19, 'BD', 'Bangladesh'), (20, 'BB', 'Barbados'), (21, 'BY', 'Belarus'), (22, 'BE', 'Belgium'), (23, 'BZ', 'Belize'), (24, 'BJ', 'Benin'), (25, 'BM', 'Bermuda'), (26, 'BT', 'Bhutan'), (27, 'BO', 'Bolivia'), (28, 'BQ', 'Bonaire, Sint Eustatius and Saba'), (29, 'BA', 'Bosnia and Herzegovina'), (30, 'BW', 'Botswana'), (31, 'BV', 'Bouvet Island'), (32, 'BR', 'Brazil'), (33, 'IO', 'British Indian Ocean Territory'), (34, 'BN', 'Brunei Darussalam'), (35, 'BG', 'Bulgaria'), (36, 'BF', 'Burkina Faso'), (37, 'BI', 'Burundi'), (38, 'KH', 'Cambodia'), (39, 'CM', 'Cameroon'), (40, 'CA', 'Canada'), (41, 'CV', 'Cape Verde'), (42, 'KY', 'Cayman Islands'), (43, 'CF', 'Central African Republic'), (44, 'TD', 'Chad'), (45, 'CL', 'Chile'), (46, 'CN', 'China'), (47, 'CX', 'Christmas Island'), (48, 'CC', 'Cocos (Keeling) Islands'), (49, 'CO', 'Colombia'), (50, 'KM', 'Comoros'), (51, 'CG', 'Congo'), (52, 'CD', 'Congo, Democratic Republic of the Congo'), (53, 'CK', 'Cook Islands'), (54, 'CR', 'Costa Rica'), (55, 'CI', 'Cote D\'Ivoire'), (56, 'HR', 'Croatia'), (57, 'CU', 'Cuba'), (58, 'CW', 'Curacao'), (59, 'CY', 'Cyprus'), (60, 'CZ', 'Czech Republic'), (61, 'DK', 'Denmark'), (62, 'DJ', 'Djibouti'), (63, 'DM', 'Dominica'), (64, 'DO', 'Dominican Republic'), (65, 'EC', 'Ecuador'), (66, 'EG', 'Egypt'), (67, 'SV', 'El Salvador'), (68, 'GQ', 'Equatorial Guinea'), (69, 'ER', 'Eritrea'), (70, 'EE', 'Estonia'), (71, 'ET', 'Ethiopia'), (72, 'FK', 'Falkland Islands (Malvinas)'), (73, 'FO', 'Faroe Islands'), (74, 'FJ', 'Fiji'), (75, 'FI', 'Finland'), (76, 'FR', 'France'), (77, 'GF', 'French Guiana'), (78, 'PF', 'French Polynesia'), (79, 'TF', 'French Southern Territories'), (80, 'GA', 'Gabon'), (81, 'GM', 'Gambia'), (82, 'GE', 'Georgia'), (83, 'DE', 'Germany'), (84, 'GH', 'Ghana'), (85, 'GI', 'Gibraltar'), (86, 'GR', 'Greece'), (87, 'GL', 'Greenland'), (88, 'GD', 'Grenada'), (89, 'GP', 'Guadeloupe'), (90, 'GU', 'Guam'), (91, 'GT', 'Guatemala'), (92, 'GG', 'Guernsey'), (93, 'GN', 'Guinea'), (94, 'GW', 'Guinea-Bissau'), (95, 'GY', 'Guyana'), (96, 'HT', 'Haiti'), (97, 'HM', 'Heard Island and Mcdonald Islands'), (98, 'VA', 'Holy See (Vatican City State)'), (99, 'HN', 'Honduras'), (100, 'HK', 'Hong Kong'), (101, 'HU', 'Hungary'), (102, 'IS', 'Iceland'), (103, 'IN', 'India'), (104, 'ID', 'Indonesia'), (105, 'IR', 'Iran, Islamic Republic of'), (106, 'IQ', 'Iraq'), (107, 'IE', 'Ireland'), (108, 'IM', 'Isle of Man'), (109, 'IL', 'Israel'), (110, 'IT', 'Italy'), (111, 'JM', 'Jamaica'), (112, 'JP', 'Japan'), (113, 'JE', 'Jersey'), (114, 'JO', 'Jordan'), (115, 'KZ', 'Kazakhstan'), (116, 'KE', 'Kenya'), (117, 'KI', 'Kiribati'), (118, 'KP', 'Korea, Democratic People\'s Republic of'), (119, 'KR', 'Korea, Republic of'), (120, 'XK', 'Kosovo'), (121, 'KW', 'Kuwait'), (122, 'KG', 'Kyrgyzstan'), (123, 'LA', 'Lao People\'s Democratic Republic'), (124, 'LV', 'Latvia'), (125, 'LB', 'Lebanon'), (126, 'LS', 'Lesotho'), (127, 'LR', 'Liberia'), (128, 'LY', 'Libyan Arab Jamahiriya'), (129, 'LI', 'Liechtenstein'), (130, 'LT', 'Lithuania'), (131, 'LU', 'Luxembourg'), (132, 'MO', 'Macao'), (133, 'MK', 'Macedonia, the Former Yugoslav Republic of'), (134, 'MG', 'Madagascar'), (135, 'MW', 'Malawi'), (136, 'MY', 'Malaysia'), (137, 'MV', 'Maldives'), (138, 'ML', 'Mali'), (139, 'MT', 'Malta'), (140, 'MH', 'Marshall Islands'), (141, 'MQ', 'Martinique'), (142, 'MR', 'Mauritania'), (143, 'MU', 'Mauritius'), (144, 'YT', 'Mayotte'), (145, 'MX', 'Mexico'), (146, 'FM', 'Micronesia, Federated States of'), (147, 'MD', 'Moldova, Republic of'), (148, 'MC', 'Monaco'), (149, 'MN', 'Mongolia'), (150, 'ME', 'Montenegro'), (151, 'MS', 'Montserrat'), (152, 'MA', 'Morocco'), (153, 'MZ', 'Mozambique'), (154, 'MM', 'Myanmar'), (155, 'NA', 'Namibia'), (156, 'NR', 'Nauru'), (157, 'NP', 'Nepal'), (158, 'NL', 'Netherlands'), (159, 'AN', 'Netherlands Antilles'), (160, 'NC', 'New Caledonia'), (161, 'NZ', 'New Zealand'), (162, 'NI', 'Nicaragua'), (163, 'NE', 'Niger'), (164, 'NG', 'Nigeria'), (165, 'NU', 'Niue'), (166, 'NF', 'Norfolk Island'), (167, 'MP', 'Northern Mariana Islands'), (168, 'NO', 'Norway'), (169, 'OM', 'Oman'), (170, 'PK', 'Pakistan'), (171, 'PW', 'Palau'), (172, 'PS', 'Palestinian Territory, Occupied'), (173, 'PA', 'Panama'), (174, 'PG', 'Papua New Guinea'), (175, 'PY', 'Paraguay'), (176, 'PE', 'Peru'), (177, 'PH', 'Philippines'), (178, 'PN', 'Pitcairn'), (179, 'PL', 'Poland'), (180, 'PT', 'Portugal'), (181, 'PR', 'Puerto Rico'), (182, 'QA', 'Qatar'), (183, 'RE', 'Reunion'), (184, 'RO', 'Romania'), (185, 'RU', 'Russian Federation'), (186, 'RW', 'Rwanda'), (187, 'BL', 'Saint Barthelemy'), (188, 'SH', 'Saint Helena'), (189, 'KN', 'Saint Kitts and Nevis'), (190, 'LC', 'Saint Lucia'), (191, 'MF', 'Saint Martin'), (192, 'PM', 'Saint Pierre and Miquelon'), (193, 'VC', 'Saint Vincent and the Grenadines'), (194, 'WS', 'Samoa'), (195, 'SM', 'San Marino'), (196, 'ST', 'Sao Tome and Principe'), (197, 'SA', 'Saudi Arabia'), (198, 'SN', 'Senegal'), (199, 'RS', 'Serbia'), (200, 'CS', 'Serbia and Montenegro'), (201, 'SC', 'Seychelles'), (202, 'SL', 'Sierra Leone'), (203, 'SG', 'Singapore'), (204, 'SX', 'Sint Maarten'), (205, 'SK', 'Slovakia'), (206, 'SI', 'Slovenia'), (207, 'SB', 'Solomon Islands'), (208, 'SO', 'Somalia'), (209, 'ZA', 'South Africa'), (210, 'GS', 'South Georgia and the South Sandwich Islands'), (211, 'SS', 'South Sudan'), (212, 'ES', 'Spain'), (213, 'LK', 'Sri Lanka'), (214, 'SD', 'Sudan'), (215, 'SR', 'Suriname'), (216, 'SJ', 'Svalbard and Jan Mayen'), (217, 'SZ', 'Swaziland'), (218, 'SE', 'Sweden'), (219, 'CH', 'Switzerland'), (220, 'SY', 'Syrian Arab Republic'), (221, 'TW', 'Taiwan, Province of China'), (222, 'TJ', 'Tajikistan'), (223, 'TZ', 'Tanzania, United Republic of'), (224, 'TH', 'Thailand'), (225, 'TL', 'Timor-Leste'), (226, 'TG', 'Togo'), (227, 'TK', 'Tokelau'), (228, 'TO', 'Tonga'), (229, 'TT', 'Trinidad and Tobago'), (230, 'TN', 'Tunisia'), (231, 'TR', 'Turkey'), (232, 'TM', 'Turkmenistan'), (233, 'TC', 'Turks and Caicos Islands'), (234, 'TV', 'Tuvalu'), (235, 'UG', 'Uganda'), (236, 'UA', 'Ukraine'), (237, 'AE', 'United Arab Emirates'), (238, 'GB', 'United Kingdom'), (239, 'US', 'United States'), (240, 'UM', 'United States Minor Outlying Islands'), (241, 'UY', 'Uruguay'), (242, 'UZ', 'Uzbekistan'), (243, 'VU', 'Vanuatu'), (244, 'VE', 'Venezuela'), (245, 'VN', 'Viet Nam'), (246, 'VG', 'Virgin Islands, British'), (247, 'VI', 'Virgin Islands, U.s.'), (248, 'WF', 'Wallis and Futuna'), (249, 'EH', 'Western Sahara'), (250, 'YE', 'Yemen'), (251, 'ZM', 'Zambia'), (252, 'ZW', 'Zimbabwe')");

function replace_template($path, $oldContent, $newContent) {
    $str = @file_get_contents($path);
    $str = str_replace($oldContent, $newContent, $str);
    @file_put_contents($path, $str);
}

$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

if(isset($_POST["configurar"])) {

$dados_stm_config_anterior = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));

if(count($_POST["paises_bloqueados"]) > 0){
	$geoip_paises_bloqueados = implode(",",$_POST["paises_bloqueados"]);
}

mysqli_query($conexao,"Update streamings set geoip_ativar = '".$_POST["geoip_ativar"]."', geoip_paises_bloqueados = '".$geoip_paises_bloqueados."' where codigo = '".$dados_stm["codigo"]."'") or die(mysqli_error($conexao));

// Desativa ou ativa o bloqueio de geoip no wowza
// Conexão SSH
$ssh = new SSH();
$ssh->conectar($dados_servidor["ip"],$dados_servidor["porta_ssh"]);
$ssh->autenticar("root",code_decode($dados_servidor["senha"],"D"));

$aplicacao = ($dados_stm["aplicacao"]) ? $dados_stm["aplicacao"] : "tvstation"; $aplicacao_xml = $aplicacao; if($dados_stm["autenticar_live"] == "nao") { if($aplicacao == "tvstation" || $aplicacao == "live") { $aplicacao_xml = $aplicacao.'-sem-login'; } }

if($_POST["geoip_ativar"] == "sim") {

copy("/home/painelvideo/public_html/geoip_tpl/Application-".$aplicacao_xml.".xml","/home/painelvideo/public_html/temp/Application-".$aplicacao_xml."-".$dados_stm["login"].".xml");
chmod("/home/painelvideo/public_html/temp/Application-".$aplicacao_xml."-".$dados_stm["login"].".xml", 0777);

replace_template("/home/painelvideo/public_html/temp/Application-".$aplicacao_xml."-".$dados_stm["login"].".xml", "LOGIN", $dados_stm["login"]);
replace_template("/home/painelvideo/public_html/temp/Application-".$aplicacao_xml."-".$dados_stm["login"].".xml", "BITRATE", $dados_stm["bitrate"]);
replace_template("/home/painelvideo/public_html/temp/Application-".$aplicacao_xml."-".$dados_stm["login"].".xml", "ESPECTADORES", $dados_stm["espectadores"]);
replace_template("/home/painelvideo/public_html/temp/Application-".$aplicacao_xml."-".$dados_stm["login"].".xml", "IPPAINEL", @file_get_contents('http://ipecho.net/plain'));
replace_template("/home/painelvideo/public_html/temp/Application-".$aplicacao_xml."-".$dados_stm["login"].".xml", "PAISES", $geoip_paises_bloqueados);

$ssh->enviar_arquivo("/home/painelvideo/public_html/temp/Application-".$aplicacao_xml."-".$dados_stm["login"].".xml","/usr/local/WowzaStreamingEngine/conf/".$dados_stm["login"]."/Application.xml",0777);

$ssh->executar("/usr/bin/java -cp /usr/local/WowzaMediaServer JMXCommandLine -jmx service:jmx:rmi://localhost:8084/jndi/rmi://localhost:8085/jmxrmi -user admin -pass admin shutdownAppInstance ".$dados_stm["login"]."");

$ssh->executar("/usr/bin/java -cp /usr/local/WowzaMediaServer JMXCommandLine -jmx service:jmx:rmi://localhost:8084/jndi/rmi://localhost:8085/jmxrmi -user admin -pass admin startAppInstance  ".$dados_stm["login"]."");

unlink("/home/painelvideo/public_html/temp/Application-".$aplicacao_xml."-".$dados_stm["login"].".xml");

} else {
  
$ssh->executar("/usr/local/WowzaMediaServer/sincronizar ".$dados_stm["login"]." '".$dados_stm["senha_transmissao"]."' ".$dados_stm["bitrate"]." ".$dados_stm["espectadores"]." ".$aplicacao_xml."");

}

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] .= status_acao("Configura&ccedil;&atilde;o atualizada com sucesso!","ok");
$_SESSION["status_acao"] .= status_acao("Streaming reiniciado para aplicar nova configura&ccedil;&atilde;o.","alerta");

header("Location: /gerenciar-geoip");
exit();

}

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
<script type="text/javascript" src="inc/selectbox.js"></script>
<script type="text/javascript" src="inc/javascript-abas.js"></script>
<script type="text/javascript">
   window.onload = function() {
	fechar_log_sistema();
   };

function selectAll(campo) 
    { 
        selectBox = document.getElementById(campo);

        for (var i = 0; i < selectBox.options.length; i++) 
        { 
             selectBox.options[i].selected = true; 
        } 
}
</script>
</head>

<body>
<div id="sub-conteudo-pequeno">
<?php
if($_SESSION['status_acao']) {

$status_acao = stripslashes($_SESSION['status_acao']);

echo '<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px">'.$status_acao.'</table>';

unset($_SESSION['status_acao']);
}
?>
<form method="post" action="/gerenciar-geoip" style="padding:0px; margin:0px" name="config_painel" onsubmit="selectAll('paises_bloqueados');">
<div id="quadro">
<div id="quadro-topo"><strong>Gerenciar Restri&ccedil;&atilde;o GeoIP</strong></div>
<div class="texto_medio" id="quadro-conteudo">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="25" class="texto_padrao">
    <table width="690" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px; margin-left:0 auto; margin-right:0 auto; background-color: #C1E0FF; border: #006699 1px solid">
            <tr>
              <td width="30" height="25" align="center" scope="col"><img src="img/icones/ajuda.gif" width="16" height="16" /></td>
              <td align="left" class="texto_padrao_destaque" scope="col">Use esta ferramenta para bloquear o acesso aos players para determinados pa&iacute;ses. CTRL + A seleciona tudo.</td>
            </tr>
          </table>
    </td>
  </tr>
  <tr>
    <td height="25">
    <div class="tab-pane" id="tabPane1">
      <div class="tab-page" id="tabPage1">
       	<h2 class="tab">Bloquear Pa&iacute;ses</h2>
        <table width="690" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid; border-left:#CCCCCC 1px solid; border-right:#CCCCCC 1px solid;">
          <tr>
            <td width="150" height="45" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Ativar Bloqueio Pa&iacute;ses</td>
            <td width="540" align="left" class="texto_padrao"><input name="geoip_ativar" type="radio" value="sim" style="vertical-align:middle"<?php if($dados_stm["geoip_ativar"] == "sim") { echo ' checked="checked"'; } ?> />
              &nbsp;<?php echo $lang['lang_label_sim']; ?>&nbsp;
              <input name="geoip_ativar" type="radio" value="nao" style="vertical-align:middle"<?php if($dados_stm["geoip_ativar"] == "nao") { echo ' checked="checked"'; } ?> />
              &nbsp;<?php echo $lang['lang_label_nao']; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="40%" align="center" scope="col">
                  <select name="paises" id="paises" multiple="multiple" style="width:100%; height:150px" onDblClick="moveSelectedOptions(this.form['paises'],this.form['paises_bloqueados'],false)">
                	<?php
					$sql_paises = mysqli_query($conexao,"SELECT * FROM geoip_paises ORDER by nome ASC");
					while ($dados_pais = mysqli_fetch_array($sql_paises)) {
					
						if(strpos($dados_stm["geoip_paises_bloqueados"], $dados_pais["sigla"]) === false) {
						echo '<option value="' . $dados_pais["sigla"] . '">' . $dados_pais["nome"] . ' (' . $dados_pais["sigla"] . ')</option>';
						}
					}
					?>
           		  </select>
                  </td>
                  <td width="20%" align="center" scope="col">
                  <input type="Button" onClick="moveSelectedOptions(this.form['paises'],this.form['paises_bloqueados'],false)" value="&#8594;" style="background: #FFFFFF; border:solid 1px #CCCCCC; height:27px; padding:5px; cursor:pointer; color: #000000; font-family: Geneva, Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; width:30px"><br /><br />
                  <input type="Button" onClick="moveSelectedOptions(this.form['paises_bloqueados'],this.form['paises'],false)" value="&#8592;" style="background: #FFFFFF; border:solid 1px #CCCCCC; height:27px; padding:5px; cursor:pointer; color: #000000; font-family: Geneva, Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; width:30px">                  </td>
                  <td width="40%" align="center" scope="col">
                    <select name="paises_bloqueados[]" id="paises_bloqueados" size="10"  multiple="multiple" onDblClick="moveSelectedOptions(this.form['paises_bloqueados'],this.form['paises'],false)" style="width:100%; height:150px">
                    <?php
					$sql_paises_bloqueados = mysqli_query($conexao,"SELECT * FROM geoip_paises ORDER by nome ASC");
					while ($dados_pais_bloqueado = mysqli_fetch_array($sql_paises_bloqueados)) {
					
						if(strpos($dados_stm["geoip_paises_bloqueados"], $dados_pais_bloqueado["sigla"]) !== false) {
						echo '<option value="' . $dados_pais_bloqueado["sigla"] . '">' . $dados_pais_bloqueado["nome"] . ' (' . $dados_pais_bloqueado["sigla"] . ')</option>';
						}
					}
					?>
           		    </select>
                    </td>
                  </tr>
                <tr>
                  <td height="20" align="center" scope="col" class="texto_padrao_destaque">Pa&iacute;ses Liberados</td>
                  <td height="20" align="center" scope="col" class="texto_padrao_destaque">&nbsp;</td>
                  <td height="20" align="center" scope="col" class="texto_padrao_destaque">Pa&iacute;ses Bloqueados</td>
                </tr>
              </table></td>
            </tr>
        </table>
   	  </div>
      </div></td>
  </tr>
  <tr>
    <td height="40" align="center"><input type="submit" class="botao" value="<?php echo $lang['lang_botao_titulo_alterar_config']; ?>" />
      <input name="configurar" type="hidden" id="configurar" value="<?php echo time(); ?>" /></td>
  </tr>
</table>
    </div>
      </div>
</form>
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