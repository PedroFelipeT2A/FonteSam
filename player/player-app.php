<?php

$login = query_string('1');

$verifica_stm = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT COUNT(*) as total FROM streamings where login = '".$login."'"));

if($verifica_stm["total"] == 0) {
  die('<style> body { margin:0; padding:0; background-color:#000000}</style><div style="width:100%;text-align:center;margin-top:30%;font-size: 14px;font-family: Geneva, Arial, Helvetica, sans-serif;color:#FFFFFF;padding-left: 10px; padding-right: 10px"><img width="256" height="256" src="/app/img-app-offline.png" alt="Radio Offline" title="Radio Offline"><br><br><br><br><br>Estamos atualizando nosso App, por favor volte em alguns minutos.<br><br>We are updating our App, please come back in a few minutes.<br><br>Estamos actualizando nuestro App, por favor regrese en unos pocos minutos.</div>');
}

$dados_config = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT * FROM streamings where login = '".$login."'"));
$dados_servidor = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

//if($dados_stm["app_tela_inicial"] == "2" && empty(query_string('2'))) {
//  header("Location: https://" . $_SERVER["HTTP_HOST"]."/player-app/".$dados_stm["login"]."/player");
//  exit();
//}

$logo_webtv = ($dados_stm["app_url_logo"]) ? $dados_stm["app_url_logo"] : "/app/img-app-logo.png";
$background_webtv = ($dados_stm["app_url_background"]) ? "background: url('".$dados_stm["app_url_background"]."') bottom / cover no-repeat;" : "background: #000000";

$array_lang = array(
  "pt-br" => array("redes_sociais" => "Redes Sociais", "avaliar" => "Avaliar", "politica" => "Pol&iacute;tica & Privacidade", "compartilhar" => "Compartilhar", "pedir_musica" => "Pedir M&uacute;sica", "voltar" => "Voltar", "prog" => "Nossa Programa&ccedil;&atilde;o", "hist" => "Nossa Hist&oacute;ria", "aviso_bg" => "Nosso app n&atilde;o executa o audio quando a tela esta desligada em alguns modelos de celular.<br><br>Para escutar nossa webtv seu celular deve estar com a tela ativa."),
  "en" => array("redes_sociais" => "Social Networks", "avaliar" => "Rate", "politica" => "Privacy Policy.", "compartilhar" => "Share", "pedir_musica" => "Request Song", "voltar" => "Back", "prog" => "Our Schedule", "hist" => "Our Story", "aviso_bg" => "Our app does not play audio when the screen is off on some cell phone models.<br><br>To listen to our webtv, your cell phone must have the screen active."),
  "es" => array("redes_sociais" => "Redes Sociales", "avaliar" => "Calificar", "politica" => "Pol&iacute;tica de Privacidad", "compartilhar" => "Compartir", "pedir_musica" => "Pedir Canci&oacute;n", "voltar" => "Volver", "prog" => "Nuestro Horario", "hist" => "Nuestra Historia", "aviso_bg" => "Nuestra aplicaci&oacute;n no reproduce audio cuando la pantalla est&aacute; apagada en algunos modelos de tel&eacute;fonos celulares.<br><br>Para escuchar nuestra webtv, su tel&eacute;fono celular debe tener la pantalla activa.")
);
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
?>
<?php if(empty(query_string('2'))) { ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<title>Player</title>
<meta name=apple-touch-fullscreen content=yes>
<meta name=apple-mobile-web-app-capable content=yes>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1">
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.6.1/sweetalert2.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.6.1/sweetalert2.css">
<style>html { height: 100%; }body { margin:0; padding:0;font-family:Poppins,sans-serif;min-height: 100%;<?php echo $background_webtv ?>}#player-app-volumewrapper{display: none;}#player-app-buttonvolumeoff{display: none;}#player-app-buttonvolumeon{display: none;}#player-app-textvolumeend{display: none;}<?php if($status_conexao_transmissao != "aovivo") { echo '#player-app-iconlive{display: none;}'; } else { echo '#player-app-iconlive{fill: rgb(255 0 0 / 100%)!important;}'; } ?>#player-app-buttonanalyzer{display: none;}.icone-menu{position:absolute;left:0;top:0;width: 32px;height: 32px;padding-left: 5px;padding-bottom: 10px; margin: 10px; border-radius: 3px;color: #FFFFFF;font-size: 32px;text-align: center;z-index: 500;}@import url(https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700);p{font-family:Poppins,sans-serif;font-size:1.1em;font-weight:300;line-height:1.7em;color:#999}a,a:focus,a:hover{color:inherit;text-decoration:none;transition:all .3s}.navbar{padding:15px 10px;background:#fff;border:none;border-radius:0;margin-bottom:40px;box-shadow:1px 1px 3px rgba(0,0,0,.1)}.navbar-btn{box-shadow:none;outline:0!important;border:none}.line{width:100%;height:1px;border-bottom:1px dashed #ddd;margin:40px 0}#sidebar{width:250px;position:fixed;top:0;left:-250px;height:100vh;z-index:999;background:<?php echo $dados_stm["app_cor_menu_claro"]; ?>;color:#fff;transition:all .3s;overflow-y:scroll;box-shadow:3px 3px 3px rgba(0,0,0,.2);overflow: hidden}#sidebar.active{left:0}#dismiss{width:30px;height:30px;line-height:30px;text-align:center;background:<?php echo $dados_stm["app_cor_menu_claro"]; ?>;position:absolute;top:10px;right:10px;cursor:pointer;-webkit-transition:all .3s;-o-transition:all .3s;transition:all .3s}#dismiss2{width:70px;height:30px;line-height:30px;text-align:center;background:<?php echo $dados_stm["app_cor_menu_claro"]; ?>;position:absolute;top:5px;left:5px;cursor:pointer;color: #FFFFFF;}#dismiss:hover{background:#fff;color:<?php echo $dados_stm["app_cor_menu_claro"]; ?>}.overlay{display:none;position:fixed;width:100vw;height:100vh;background:rgba(0,0,0,.7);z-index:998;opacity:0;transition:all .5s ease-in-out}.overlay.active{display:block;opacity:1}#sidebar .sidebar-header{padding:20px;background:<?php echo $dados_stm["app_cor_menu_escuro"]; ?>}#sidebar ul.components{padding:20px 0;}#sidebar ul p{color:#fff;padding:10px}#sidebar ul li a{padding:10px;font-size:1.1em;display:block}#sidebar ul li a:hover{color:<?php echo $dados_stm["app_cor_menu_claro"]; ?>;background:#fff}#sidebar ul li.active>a,a[aria-expanded=true]{color:#fff;background:<?php echo $dados_stm["app_cor_menu_escuro"]; ?>}a[data-toggle=collapse]{position:relative}.dropdown-toggle::after{display:block;position:absolute;top:50%;right:20px;transform:translateY(-50%)}ul ul a{font-size:.9em!important;padding-left:30px!important;background:<?php echo $dados_stm["app_cor_menu_escuro"]; ?>}ul.CTAs{padding:20px}ul.CTAs a{text-align:center;font-size:.9em!important;display:block;border-radius:5px;margin-bottom:5px}a.download{background:#fff;color:<?php echo $dados_stm["app_cor_menu_claro"]; ?>}a.article,a.article:hover{background:<?php echo $dados_stm["app_cor_menu_escuro"]; ?>!important;color:#fff!important}#content{width:100%;padding:20px;min-height:100vh;transition:all .3s;position:absolute;top:0;right:0}.chromeframe{margin:.2em 0;background:#ccc;color:#000;padding:.2em 0}#loader-wrapper{position:fixed;top:0;left:0;width:100%;height:100%;z-index:1000}#loader{display:block;position:relative;left:50%;top:50%;width:150px;height:150px;margin:-75px 0 0 -75px;border-radius:50%;border:3px solid transparent;border-top-color:#3498db;-webkit-animation:spin 2s linear infinite;animation:spin 2s linear infinite;z-index:1001}#loader:before{content:"";position:absolute;top:5px;left:5px;right:5px;bottom:5px;border-radius:50%;border:3px solid transparent;border-top-color:#e74c3c;-webkit-animation:spin 3s linear infinite;animation:spin 3s linear infinite}#loader:after{content:"";position:absolute;top:15px;left:15px;right:15px;bottom:15px;border-radius:50%;border:3px solid transparent;border-top-color:#f9c922;-webkit-animation:spin 1.5s linear infinite;animation:spin 1.5s linear infinite}@-webkit-keyframes spin{0%{-webkit-transform:rotate(0);-ms-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);-ms-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes spin{0%{-webkit-transform:rotate(0);-ms-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);-ms-transform:rotate(360deg);transform:rotate(360deg)}}#loader-wrapper .loader-section{position:fixed;top:0;width:51%;height:100%;background:#222;z-index:1000}#loader-wrapper .loader-section.section-left{left:0}#loader-wrapper .loader-section.section-right{right:0}.loaded #loader-wrapper .loader-section.section-left{-webkit-transform:translateX(-100%);-ms-transform:translateX(-100%);transform:translateX(-100%);-webkit-transition:all .7s .3s cubic-bezier(.645,.045,.355,1);transition:all .7s .3s cubic-bezier(.645,.045,.355,1)}.loaded #loader-wrapper .loader-section.section-right{-webkit-transform:translateX(100%);-ms-transform:translateX(100%);transform:translateX(100%);-webkit-transition:all .7s .3s cubic-bezier(.645,.045,.355,1);transition:all .7s .3s cubic-bezier(.645,.045,.355,1)}.loaded #loader{opacity:0;-webkit-transition:all .3s ease-out;transition:all .3s ease-out}.loaded #loader-wrapper{visibility:hidden;-webkit-transform:translateY(-100%);-ms-transform:translateY(-100%);transform:translateY(-100%);-webkit-transition:all .3s 1s ease-out;transition:all .3s 1s ease-out}.ir{background-color:transparent;border:0;overflow:hidden}.ir:before{content:"";display:block;width:0;height:150%}.hidden{display:none!important;visibility:hidden}.visuallyhidden{border:0;clip:rect(0 0 0 0);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px}.visuallyhidden.focusable:active,.visuallyhidden.focusable:focus{clip:auto;height:auto;margin:0;overflow:visible;position:static;width:auto}.invisible{visibility:hidden}.clearfix:after,.clearfix:before{content:" ";display:table}.clearfix:after{clear:both}</style>
</head>
<body>
<div id="loader-wrapper"><div id="loader"></div><div class="loader-section section-left"></div><div class="loader-section section-right"></div></div>
<div class="icone-menu"><button type="button" id="sidebarCollapse" class="btn btn-sm"><i class="fa fa-bars"></i></button></div>
<nav id="sidebar">
   <div id="dismiss">
      <i class="fa fa-arrow-left"></i>
   </div>
   <div class="sidebar-header">
      <h4><?php echo $dados_stm["app_nome"]; ?></h3>
   </div>
   <ul class="list-unstyled components">
    <?php if($dados_stm["app_url_facebook"] || $dados_stm["app_url_instagram"] || $dados_stm["app_url_twitter"] || $dados_stm["app_url_site"]) { ?>
      <li>
        <a href="#redessociais" data-toggle="collapse" aria-expanded="false"><i class="fa fa-heart"></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["redes_sociais"]; ?></a>
        <ul class="collapse list-unstyled" id="redessociais">
          <?php if($dados_stm["app_url_facebook"]) { ?>
           <li>
              <a href="<?php echo $dados_stm["app_url_facebook"]; ?>" ><i class="fa fa-facebook-official"></i> Facebook</a>
           </li>
          <?php } ?>
          <?php if($dados_stm["app_url_instagram"]) { ?>
           <li>
              <a href="<?php echo $dados_stm["app_url_instagram"]; ?>"><i class="fa fa-instagram"></i> Instagram</a>
           </li>
          <?php } ?>
          <?php if($dados_stm["app_url_twitter"]) { ?>
           <li>
              <a href="<?php echo $dados_stm["app_url_twitter"]; ?>"><i class="fa fa-twitter"></i> Twitter</a>
           </li>
          <?php } ?>
          <?php if($dados_stm["app_url_youtube"]) { ?>
           <li>
              <a href="#" onClick="abrir_link('<?php echo $dados_stm["app_url_youtube"]; ?>')"><i class="fa fa-youtube"></i> YouTube</a>
           </li>
          <?php } ?>
          <?php if($dados_stm["app_telegram"]) { ?>
           <li>
              <a href="<?php echo $dados_stm["app_telegram"]; ?>"><i class="fa fa-telegram"></i> Telegram</a>
           </li>
          <?php } ?>
        </ul>
      </li>
    <?php } ?>
          <?php if($dados_stm["app_url_site"]) { ?>
           <li>
              <a href="#" onClick="abrir_link('<?php echo $dados_stm["app_url_site"]; ?>')"><i class="fa fa-globe"></i> Site</a>
           </li>
          <?php } ?>
     <?php if($dados_stm["app_url_player_radio"]) { ?>
      <li>
         <a href="#" onClick="abrir_link('<?php echo $dados_stm["app_url_player_radio"]; ?>')"><i class="fa fa-music"></i> Player Radio</a>
      </li>
     <?php } ?>
     <?php if($dados_stm["app_url_chat"]) { ?>
      <li>
         <a href="#" onClick="abrir_link('<?php echo $dados_stm["app_url_chat"]; ?>')"><i class="fa fa-comments"></i> Chat</a>
      </li>
     <?php } ?>
     <?php if($dados_stm["app_whatsapp"]) { ?>
      <li>
         <a href="whatsapp://send?phone=<?php echo $dados_stm["app_whatsapp"]; ?>"><i class="fa fa-whatsapp"></i> Whatsapp</a>
      </li>
     <?php } ?>
     <?php if($dados_stm["app_text_prog"]) { ?>
      <li>
         <a href="#" onClick="abrir_link('/app/texto/<?php echo $dados_stm["login"]; ?>/programacao')"><i class="fa fa-calendar-check-o"></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["prog"]; ?></a>
      </li>
     <?php } ?>
     <?php if($dados_stm["app_text_hist"]) { ?>
      <li>
         <a href="#" onClick="abrir_link('/app/texto/<?php echo $dados_stm["login"]; ?>/historia')"><i class="fa fa-info-circle"></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["hist"]; ?></a>
      </li>
     <?php } ?>
      <li>
         <a href="#app" data-toggle="collapse" aria-expanded="false"><i class="fa fa-tablet"></i> App</a>
         <ul class="collapse list-unstyled" id="app">
            <li>
               <a href="rateapp://"><i class="fa fa-thumbs-up"></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["avaliar"]; ?></a>
            </li>
            <li>
               <a href="shareapp://"><i class="fa fa-share-alt"></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["compartilhar"]; ?></a>
            </li>
            <li>
               <a href="#" onClick="abrir_link('/app/politica.html')"><i class="fa fa-gavel"></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["politica"]; ?></a>
            </li>
            <?php if($dados_stm["app_email"]) { ?>
            <li>
               <a href="mailto:<?php echo $dados_stm["app_email"]; ?>"><i class="fa fa-envelope"></i> E-mail</a>
            </li>
        <?php } ?>
         </ul>
      </li>
   </ul>
</nav>
<div id="conteudo_externo" style="position: absolute;left:0px;top:0px;width:100%;height:100%;background: #FFFFFF;margin:0;padding:0;z-index: 99999;display: none;">
<div style="width: 100%;height: 40px;margin:0;padding:0px 10px;background:<?php echo $dados_stm["app_cor_menu_escuro"]; ?>"><div id="dismiss2"><i class="fa fa-arrow-left" onClick="$('#conteudo_externo').hide();$('#iframe_conteudo_externo').attr('src', '');"> <?php echo $array_lang[$dados_stm["idioma_painel"]]["voltar"]; ?></i></div></div>
<iframe target="_parent" id="iframe_conteudo_externo" frameborder="0" src="" style="position: absolute;width:100%;height:100%;z-index: 99999"></iframe>
</div>
<div class="overlay"></div>
<div id="player-app-logo-conteudo" style="overflow: hidden; background: rgba(255, 255, 255, 0.1); user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); width: 256px; height: 256px; border: 10px solid rgba(255, 255, 255, 0.2); border-radius: 50%;margin: 15% auto">
  <div id="player-app-logo" style="height: 100%; width: 100%; overflow: hidden; transition: opacity 2s ease 0s; background: url('<?php echo $logo_webtv ?>') 0% 0% / cover no-repeat; border-radius: 50%;"></div>
</div>
<div id="nome_webtv" style="margin-top:60px; margin-left: auto; margin-right: auto; width: 95%; height: 40px; font-size: 35px; line-height: 40px; color: <?php echo $dados_stm["app_cor_texto"]; ?>;text-align: center; z-index: 9999"></div>
<a href="player://">
<div id="player-app-play" style="cursor: pointer; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); width: 112px; height: 112px;margin-top:60px; margin-left: auto; margin-right: auto;" onClick="$('#player-app-play').width('100px');$('#player-app-play').height('100px');">
  <div id="player-app-buttonplay" style="width: 100%; height: 100%; transition: fill 0.5s ease 0s; fill: rgb(255, 255, 255); user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><svg x="0px" y="0px" viewBox="0 0 800 800"><path d="M713.9,400.5c1.4,171.2-137.8,314.4-313.9,314.3c-175.6,0-314.2-143-314-315c0.2-171.3,140.6-313.9,315-313.4 C574,87,715.4,228.9,713.9,400.5z M279.5,400.3c0,23.1,0,46.2,0,69.3c0,20.8-0.2,41.7,0.1,62.5c0.1,12.2,6,21.1,17,26.6 c11,5.5,21.2,3,31.2-2.9c23.3-13.6,46.8-27,70.2-40.5c49.8-28.6,99.6-57.1,149.3-85.8c18.1-10.4,18.7-38.7,1.1-49.4 c-74.5-45.4-149-90.8-223.5-136.1c-6-3.7-12.6-5.5-19.8-4.2c-15.7,2.9-25.5,14.4-25.5,30.5C279.4,313.6,279.5,357,279.5,400.3z"></path></svg></div></div></a>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>    
<script>
function ajustes_navegador() {  
var altura_tela = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
$("#conteudo_externo").height(altura_tela);
$("#iframe_conteudo_externo").height(altura_tela-35);
$("#sidebarCollapse").delay(200).fadeOut(100);
$("#sidebarCollapse").delay(200).fadeIn(100);
}
function abrir_link(link) {
$("#conteudo_externo").show();
$("#iframe_conteudo_externo").attr("src", link);
$('#sidebar').removeClass('active');
$('.overlay').removeClass('active');
}
function marquee_nome_webtv() {

var nome = "<?php echo $dados_stm["app_nome"]; ?>";

if(nome.length > 22) {
$('#nome_webtv').hide().html('<marquee behavior="scroll" direction="left">'+nome+'</marquee>').fadeIn('slow');
} else {
$('#nome_webtv').hide().html(nome).fadeIn('slow');
}

}
$(document).ready(function () {
    $('#dismiss, .overlay').on('click', function () {
        $('#sidebar').removeClass('active');
        $('.overlay').removeClass('active');
    });
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });
  
<?php if($dados_stm["app_tela_inicial"] == "2" && empty(query_string('2'))) { ?>
window.location = "player://";
<?php } ?>
setTimeout(ajustes_navegador,3000);
setTimeout(marquee_nome_webtv,2000);
setTimeout(function(){$('body').addClass('loaded');}, 1000);

});

</script>
</body>
</html>
<?php } else { ?>
<?php
if($dados_servidor["nome_principal"]) {
$servidor = $dados_servidor["nome_principal"].".".$dados_config["dominio_padrao"];
} else {
$servidor = $dados_servidor["nome"].".".$dados_config["dominio_padrao"];
}

$url_source = "https://".$servidor."/".$login."/".$login."/playlist.m3u8";

switch ($dados_stm['watermark_posicao']) {
  case 'left,top':
  $clappr_watermark ='top-left';
  break;
  case 'right,top':
  $clappr_watermark ='top-right';
  break;
  case 'left,bottom':
  $clappr_watermark ='bottom-left';
  break;
  case 'right,bottom':
  $clappr_watermark ='bottom-right';
  break; 
  default:
  $clappr_watermark ='bottom-right';
  break;
}

// Verifica se streaming esta funcionando, se nao estiver exibe aviso de sem sinal
$file_headers = @get_headers($url_source);
if($file_headers[0] == 'HTTP/1.0 404 Not Found') {
die('<!DOCTYPE HTML><html><head><title>Sem sinal | No signal</title><style>body {background-image:url("/img/nosignal.gif");background-repeat: no-repeat;background-size: 100% 100%;}html {height: 100%}</style></head><body><script>setTimeout(function() { location.reload(); }, 60000);</script></body></html>');
}
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://vjs.zencdn.net/7.8.3/video.min.js"></script>
    <script src="/videojs-http-streaming.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/videojs-watermark@2.0.0/dist/videojs-watermark.js"></script>
  <title>Player</title>
<link href="//vjs.zencdn.net/7.8.3/video-js.css" rel="stylesheet">
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <style>*{margin:0;}html,body{height:100%;}
    .video-js .vjs-time-control{display: none;}.video-js .vjs-progress-control{display: none;}.video-js .vjs-menu-button-inline.vjs-slider-active,.video-js .vjs-menu-button-inline:focus,.video-js .vjs-menu-button-inline:hover,.video-js.vjs-no-flex .vjs-menu-button-inline {
      width: 10em
    }
    .video-js .vjs-controls-disabled .vjs-big-play-button {
      display: none!important
    }
    .video-js .vjs-control {
      width: 3em
    }
    .video-js .vjs-menu-button-inline:before {
      width: 1.5em
    }
    .vjs-menu-button-inline .vjs-menu {
      left: 3em
    }
    .vjs-paused.vjs-has-started.video-js .vjs-big-play-button,.video-js.vjs-ended .vjs-big-play-button,.video-js.vjs-paused .vjs-big-play-button {
      display: block
    }
    .video-js .vjs-load-progress div,.vjs-seeking .vjs-big-play-button,.vjs-waiting .vjs-big-play-button {
      display: none!important
    }
    .video-js .vjs-mouse-display:after,.video-js .vjs-play-progress:after {
      padding: 0 .4em .3em
    }
    .video-js.vjs-ended .vjs-loading-spinner {
      display: none;
    }
    .video-js.vjs-ended .vjs-big-play-button {
      display: block !important;
    }
    video-js.vjs-ended .vjs-big-play-button,.video-js.vjs-paused .vjs-big-play-button,.vjs-paused.vjs-has-started.video-js .vjs-big-play-button {
      display: block
    }
    .video-js .vjs-big-play-button {
      top: 50%;
      left: 50%;
      margin-left: -1.5em;
      margin-top: -1em
    }
    .video-js .vjs-big-play-button {
      background-color: rgba(14,34,61,0.7);
      font-size: 3.5em;
      border-radius: 12%;
      height: 1.4em !important;
      line-height: 1.4em !important;
      margin-top: -0.7em !important
    }
    .video-js:hover .vjs-big-play-button,.video-js .vjs-big-play-button:focus,.video-js .vjs-big-play-button:active {
      background-color: #0e223d
    }
    .video-js .vjs-loading-spinner {
      border-color: rgba(14,34,61,0.84)
    }
    .video-js .vjs-control-bar2 {
      background-color: #0e223d
    }
    .video-js .vjs-control-bar {
      background-color: #0e223d !important;
      color: #ffffff;
      font-size: 14px
    }
    .video-js .vjs-fullscreen-control {
      display: none;
    }
    .video-js .vjs-play-progress,.video-js  .vjs-volume-level {
      background-color: rgba(14,34,61,0.8)
    }.video-js.vjs-watermark{display:block}.video-js .vjs-watermark-content{opacity:0.99;position:absolute;padding:5px;-webkit-transition:visibility 1s,opacity 1s;-moz-transition:visibility 1s,opacity 1s;-ms-transition:visibility 1s,opacity 1s;-o-transition:visibility 1s,opacity 1s;transition:visibility 1s,opacity 1s}.video-js .vjs-watermark-top-right{right:0;top:0}.video-js .vjs-watermark-top-left{left:0;top:0}.video-js .vjs-watermark-bottom-right{right:0;bottom:30px}.video-js .vjs-watermark-bottom-left{left:0;bottom:30px}.video-js.vjs-user-inactive.vjs-playing .vjs-watermark-fade{opacity:0}
      .player_webtv-dimensions .vjs-fluid {
    padding-top: 0!important;

    }</style></head>
<body>
<video id="player_webtv" class="video-js vjs-big-play-centered vjs-fluid" controls preload="auto" width="100%" autoplay="autoplay" height="300" style="padding-top: 0!important" poster="/img/pixel.png"> 
      <source
      src="<?php echo $url_source; ?>"
      type="application/x-mpegURL">
    </video>
    <script type="text/javascript" charset="utf-8">
      window.onload = function() {
        var altura = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
        $("#player_webtv").height(altura+'px');
      var player = videojs('player_webtv');
      <?php if($dados_stm["watermark_posicao"]) { ?>
      player.watermark({
        image: 'https://<?php echo $servidor;?>:1443/watermark.php?login=<?php echo $login;?>',
        position: '<?php echo $watermark_posicao;?>',
        fadeTime: null
      });
    <?php } ?>
      player.play();
      }
  </script>
</body>
</html>
<?php } ?>