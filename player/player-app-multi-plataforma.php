<?php
$login = query_string('1');

$verifica_stm = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT COUNT(*) as total FROM streamings where login = '".$login."'"));

if($verifica_stm["total"] == 0) {
	die('<style> body { margin:0; padding:0; background-color:#000000}</style><div style="width:100%;text-align:center;margin-top:30%;font-size: 14px;font-family: Geneva, Arial, Helvetica, sans-serif;color:#FFFFFF;padding-left: 10px; padding-right: 10px"><img width="256" height="256" src="/app-multi-plataforma/img-app-offline.png" alt="Radio Offline" title="Radio Offline"><br><br><br><br><br>Estamos atualizando nosso App, por favor volte em alguns minutos.<br><br>We are updating our App, please come back in a few minutes.<br><br>Estamos actualizando nuestro App, por favor regrese en unos pocos minutos.</div>');
}

$dados_config = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT * FROM streamings where login = '".$login."'"));
$dados_servidor = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));
$dados_app_multi_plataforma = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT * FROM app_multi_plataforma where codigo_stm = '".$dados_stm["codigo"]."'"));

if($_SERVER["HTTPS"] != "on") {
  header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
  exit();
}


if(query_string('2') == "anuncio") {

$dados_anuncio = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM app_multi_plataforma_anuncios WHERE codigo_app = '".$dados_app_multi_plataforma["codigo"]."' ORDER BY RAND() LIMIT 1"));

$anuncio_link = (empty($dados_anuncio["link"])) ? "#" : "https://".$_SERVER["HTTP_HOST"]."/player-app-multi-plataforma/".$dados_stm["login"]."/abrir-anuncio/".$dados_anuncio["codigo"]."";

if($dados_anuncio["codigo"] > 0) {

mysqli_query($conexao,"UPDATE app_multi_plataforma_anuncios SET exibicoes = exibicoes+1 WHERE codigo = '".$dados_anuncio["codigo"]."'");

echo $dados_anuncio["banner"]."|".$anuncio_link;
}

exit();
}

if(query_string('2') == "abrir-anuncio") {

$dados_anuncio = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM app_multi_plataforma_anuncios WHERE codigo = '".query_string('3')."'"));

mysqli_query($conexao,"UPDATE app_multi_plataforma_anuncios SET cliques = cliques+1 WHERE codigo = '".$dados_anuncio["codigo"]."'");

header("Location: ".$dados_anuncio["link"]."");
exit();
}

if(query_string('2') == "carregar-notificacao") {

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');

  $array_dados_notificacao = array();
  $sql_notificacoes = mysqli_query($conexao,"SELECT * FROM app_multi_plataforma_notificacoes WHERE codigo_app = '".$dados_app_multi_plataforma["codigo"]."'");
  while ($dados_notificacao = mysqli_fetch_assoc($sql_notificacoes)) {

  $cookie = "app_".$dados_stm["login"]."_notify_".$dados_notificacao["codigo"]."";

    if(empty($_COOKIE[$cookie])) {
      mysqli_query($conexao,"UPDATE app_multi_plataforma_notificacoes SET vizualizacoes = vizualizacoes+1 WHERE codigo = '".$dados_notificacao["codigo"]."'");
      $dados_notificacao = array_map('utf8_decode', $dados_notificacao);
      array_push($array_dados_notificacao,$dados_notificacao);
      echo json_encode($array_dados_notificacao);
      exit();
    }

  }
  
exit();
}

if(query_string('2') == "manifest.webmanifest") {
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/manifest+json');
echo '{
  "short_name": "'.utf8_encode($dados_app_multi_plataforma["nome"]).'",
  "name": "'.utf8_encode($dados_app_multi_plataforma["nome"]).'",
  "icons": [
    {
      "src": "'.$dados_app_multi_plataforma["url_logo"].'",
      "type": "image/png",
      "sizes": "300x300",
      "purpose": "any"
    }
  ],
  "description": "WebTV App",
  "start_url": "https://'.$_SERVER["HTTP_HOST"].'/player-app-multi-plataforma/'.$dados_stm["login"].'?app-multi='.time().'",
  "scope": "/player-app-multi-plataforma/'.$dados_stm["login"].'",
  "display": "standalone",
  "orientation": "landscape",
  "theme_color": "'.$dados_app_multi_plataforma["cor_splash"].'",
  "background_color": "'.$dados_app_multi_plataforma["cor_splash"].'"
}';

exit();
}

$logo_webtv = ($dados_app_multi_plataforma["url_logo"]) ? $dados_app_multi_plataforma["url_logo"] : "/app-multi-plataforma/img-app-logo.png";
$background_webtv = ($dados_app_multi_plataforma["url_background"]) ? "background: url('".$dados_app_multi_plataforma["url_background"]."') bottom / cover no-repeat;" : "background: #FFFFFF";

$array_lang = array(
	"pt-br" => array("redes_sociais" => "Redes Sociais", "politica" => "Pol&iacute;tica & Privacidade", "pedir_musica" => "Pedir M&uacute;sica", "voltar" => "Voltar", "prog" => "Nossa Programa&ccedil;&atilde;o", "hist" => "Nossa Hist&oacute;ria", "aviso_instalar" => "Clique no bot&atilde;o abaixo para instalar o App com seguran&ccedil;a em seu aparelho.", "aviso_instalar_iphone" => "Para instalar o App, aperte o botão <img width='23' height='32' src='/app-multi-plataforma/img-icone-share-ios.png'> de compartilhar em seu iPhone e escolha a opção &quot;Adicionar a Tela de Inicio&quot;", "aviso_instalar_windows" => "Para usar este App Windows, voc&ecirc; precisa clicar no bot&atilde;o de instala&ccedil;&atilde;o que ser&aacute; exibido pelo navegador.<br>Se ja estiver instalado, abra-o pelo menu iniciar <i class='fa fa-windows'></i> do seu windows.<br><br>&Eacute; necessario uso do navegador Chrome<i class='fa fa-chrome'></i> ou Edge<i class='fa fa-edge'></i> para a instala&ccedil;&atilde;o.", "aviso_atualizar" => "Uma nova vers&atilde;o do App esta dispon&iacute;vel, clique para atualizar.", "btn_instalar" => "Instalar App", "notificacao_permissao_titulo" => "Deseja receber notifica&ccedil;&otilde;es de nossa radio?", "notificacao_permissao_sim" => "Permitir", "notificacao_permissao_nao" => "N&atilde;o, obrigado.", "notificacao_ativada" => "Notificações ativadas, obrigado!"),

	"en" => array("redes_sociais" => "Social Networks", "politica" => "Privacy Policy.", "pedir_musica" => "Request Song", "voltar" => "Back", "prog" => "Our Schedule", "hist" => "Our Story", "aviso_instalar" => "Click the button below to install the App safely on your device.", "aviso_instalar_iphone" => "To install the App, press the share button <img width='23' height='32' src='/app-multi-plataforma/img-icone-share-ios.png'> on your iPhone and choose the option &quot;Add to Home Screen&quot;", "aviso_instalar_windows" => "To use this Windows App, you need to click on the installation button that will be displayed by the browser.<br>If is already installed, then open the app by the start menu <i class='fa fa-windows'></i> on your windows.<br><br>Chrome<i class='fa fa-chrome'></i> or Edge<i class='fa fa-edge'></i> browser is required for installation.", "aviso_atualizar" => "A new version of the App is available, click to update.", "btn_instalar" => "Install App", "push_permissao_titulo" => "Do you want to receive notifications of our radio?", "push_permissao_sim" => "Allow", "push_permissao_nao" => "No, thanks.", "notificacao_permissao_titulo" => "Do you want to receive notifications of our radio?", "notificacao_permissao_sim" => "Allow", "notificacao_permissao_nao" => "No, thanks.", "notificacao_ativada" => "Notifications activated, thanks!"),

	"es" => array("redes_sociais" => "Redes Sociales", "politica" => "Pol&iacute;tica de Privacidad", "pedir_musica" => "Pedir Canci&oacute;n", "voltar" => "Volver", "prog" => "Nuestro Horario", "hist" => "Nuestra Historia", "aviso_instalar" => "Haga clic en el bot&oacute;n de abajo para instalar la aplicaci&oacute;n de forma segura en su dispositivo.", "aviso_instalar_iphone" => "Para instalar la aplicaci&oacute;n, presione el bot&oacute;n compartir <img width='23' height='32' src='/app-multi-plataforma/img-icone-share-ios.png'> en su iPhone y elija la opci&oacute;n &quot;A&ntilde;adir a la pantalla de inicio&quot;", "aviso_instalar_windows" => "Para utilizar esta aplicaci&oacute;n de Windows, debe hacer clic en el bot&oacute;n de instalaci&oacute;n que se mostrar&aacute; en el navegador.<br>Si ya est&aacute; instalado, &aacute;bralo desde el men&uacute; de inicio <i class='fa fa-windows'></i> en su windows.<br><br>Se requiere el navegador Chrome<i class='fa fa-chrome'></i> o Edge<i class='fa fa-edge'></i> para la instalaci&oacute;n.", "aviso_atualizar" => "Hay una nueva versi&oacute;n de la aplicaci&oacute;n disponible, haga clic para actualizar.", "btn_instalar" => "Instalar App", "push_permissao_titulo" => "Quieres recibir notificaciones de nuestra radio?", "push_permissao_sim" => "Permitir", "push_permissao_nao" => "No, gracias.", "notificacao_permissao_titulo" => "Quieres recibir notificaciones de nuestra radio?", "notificacao_permissao_sim" => "Permitir", "notificacao_permissao_nao" => "No, gracias.", "notificacao_ativada" => "Notificaciones habilitadas, gracias!")
);

if($dados_servidor["nome_principal"]) {
$servidor = $dados_servidor["nome_principal"].".".$dados_config["dominio_padrao"];
} else {
$servidor = $dados_servidor["nome"].".".$dados_config["dominio_padrao"];
}

$url_source = "https://".$servidor."/".$login."/".$login."/playlist.m3u8";

if(query_string('2') == "player") {

if($dados_stm["transcoder_instalado"] == "sim") {
  $url_source = "https://".$servidor."/".$login."/smil:transcoder.smil/playlist.m3u8";
} else {
  $url_source = "https://".$servidor."/".$login."/".$login."/playlist.m3u8";
}

switch ($dados_stm['watermark_posicao']) {
  case 'left,top':
  $watermark_posicao = 'top-left';
  break;
  case 'right,top':
  $watermark_posicao = 'top-right';
  break;
  case 'left,bottom':
  $watermark_posicao = 'bottom-left';
  break;
  case 'right,bottom':
  $watermark_posicao = 'bottom-right';
  break; 
  default:
  $watermark_posicao = 'top-left';
  break;
}

// Verifica se streaming esta funcionando, se nao estiver exibe aviso de sem sinal
$file_headers = @get_headers($url_source);
if($file_headers[0] == 'HTTP/1.0 404 Not Found') {
die('<!DOCTYPE HTML><html><head><title>Sem sinal | No signal</title><style>body {background-image:url("/img/nosignal.gif");background-repeat: no-repeat;background-size: 100% 100%;}html {height: 100%}</style></head><body><script>setTimeout(function() { location.reload(); }, 20000);</script></body></html>');
}

require_once("player-app-multi-plataforma-player.php");

exit();
}

if($dados_app_multi_plataforma["modelo"] == 1) {
require_once("player-app-multi-plataforma-modelo1.php");
} else if($dados_app_multi_plataforma["modelo"] == 2) {
require_once("player-app-multi-plataforma-modelo2.php");
} else {
require_once("player-app-multi-plataforma-modelo1.php");
}

for($i=0;$i<=1000;$i++){echo "\n\n";}for($i=0;$i<=100;$i++){echo "        ";}

?>