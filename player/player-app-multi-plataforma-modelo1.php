<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title><?php echo $dados_app_multi_plataforma["nome"];?></title>
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<meta name="Description" content="WebTV App">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="<?php echo $dados_app_multi_plataforma["nome"];?>">
<meta name="msapplication-TileColor" content="<?php echo $dados_app_multi_plataforma["cor_splash"]; ?>">
<meta name="theme-color" content="<?php echo $dados_app_multi_plataforma["cor_splash"]; ?>">
<link rel="icon" type="image/png" href="<?php echo $dados_app_multi_plataforma["url_logo"];?>" sizes="300x300" />
<link rel="apple-touch-icon" sizes="300x300" href="<?php echo $dados_app_multi_plataforma["url_logo"];?>">
<link rel="manifest" href="/player-app-multi-plataforma/<?php echo $dados_stm["login"];?>/manifest.webmanifest?<?php echo time();?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.6.1/sweetalert2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.6.1/sweetalert2.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/jquery.cookie@1.4.1/jquery.cookie.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>html { height: 100%; }body { margin:0; padding:0;font-family:Poppins,sans-serif;<?php if(!empty($_GET["app-multi"])) {echo $background_webtv;} ?>}#player-app-volumewrapper{display: none;}#player-app-buttonvolumeoff{display: none;}#player-app-buttonvolumeon{display: none;}#player-app-textvolumeend{display: none;}<?php if($status_conexao_transmissao != "aovivo") { echo '#player-app-iconlive{display: none;}'; } else { echo '#player-app-iconlive{fill: rgb(255 0 0 / 100%)!important;}'; } ?>#player-app-buttonanalyzer{display: none;}.icone-menu{position:absolute;left:0;top:0;width: 32px;height: 32px;padding-left: 5px;padding-bottom: 10px; margin: 10px; border-radius: 3px;color: #FFFFFF;font-size: 32px;text-align: center;z-index: 500;}@import url(https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700);p{font-family:Poppins,sans-serif;font-size:1.1em;font-weight:300;line-height:1.7em;color:#999}a,a:focus,a:hover{color:inherit;text-decoration:none;transition:all .3s}.navbar{padding:15px 10px;background:#fff;border:none;border-radius:0;margin-bottom:40px;box-shadow:1px 1px 3px rgba(0,0,0,.1)}.navbar-btn{box-shadow:none;outline:0!important;border:none}.line{width:100%;height:1px;border-bottom:1px dashed #ddd;margin:40px 0}#sidebar{width:250px;position:fixed;top:0;left:-250px;height:100vh;z-index:999;background:<?php echo $dados_app_multi_plataforma["cor_menu_claro"]; ?>;color:#fff;transition:all .3s;overflow-y:scroll;box-shadow:3px 3px 3px rgba(0,0,0,.2);overflow: hidden}#sidebar.active{left:0}#dismiss{width:30px;height:30px;line-height:30px;text-align:center;background:<?php echo $dados_app_multi_plataforma["cor_menu_claro"]; ?>;position:absolute;top:10px;right:10px;cursor:pointer;-webkit-transition:all .3s;-o-transition:all .3s;transition:all .3s}#dismiss2{width:70px;height:30px;line-height:30px;text-align:center;background:<?php echo $dados_app_multi_plataforma["cor_menu_claro"]; ?>;position:absolute;top:5px;left:5px;cursor:pointer;color: #FFFFFF;}#dismiss:hover{background:#fff;color:<?php echo $dados_app_multi_plataforma["cor_menu_claro"]; ?>}.overlay{display:none;position:fixed;width:100vw;height:100vh;background:rgba(0,0,0,.7);z-index:998;opacity:0;transition:all .5s ease-in-out}.overlay.active{display:block;opacity:1}#sidebar .sidebar-header{padding:20px;background:<?php echo $dados_app_multi_plataforma["cor_menu_escuro"]; ?>}#sidebar ul.components{padding:20px 0;}#sidebar ul p{color:#fff;padding:10px}#sidebar ul li a{padding:10px;font-size:1.1em;display:block}#sidebar ul li a:hover{color:<?php echo $dados_app_multi_plataforma["cor_menu_claro"]; ?>;background:#fff}#sidebar ul li.active>a,a[aria-expanded=true]{color:#fff;background:<?php echo $dados_app_multi_plataforma["cor_menu_escuro"]; ?>}a[data-bs-toggle=collapse]{position:relative}.dropdown-toggle::after{display:block;position:absolute;top:50%;right:20px;transform:translateY(-50%)}ul ul a{font-size:.9em!important;padding-left:30px!important;background:<?php echo $dados_app_multi_plataforma["cor_menu_escuro"]; ?>}ul.CTAs{padding:20px}ul.CTAs a{text-align:center;font-size:.9em!important;display:block;border-radius:5px;margin-bottom:5px}a.download{background:#fff;color:<?php echo $dados_app_multi_plataforma["cor_menu_claro"]; ?>}a.article,a.article:hover{background:<?php echo $dados_app_multi_plataforma["cor_menu_escuro"]; ?>!important;color:#fff!important}#content{width:100%;padding:20px;min-height:100vh;transition:all .3s;position:absolute;top:0;right:0}.chromeframe{margin:.2em 0;background:#ccc;color:#000;padding:.2em 0}#loader-wrapper{position:fixed;top:0;left:0;width:100%;height:100%;z-index:1000}#loader{display:block;position:relative;left:50%;top:50%;width:150px;height:150px;margin:-75px 0 0 -75px;border-radius:50%;border:3px solid transparent;border-top-color:#3498db;-webkit-animation:spin 2s linear infinite;animation:spin 2s linear infinite;z-index:1001}#loader:before{content:"";position:absolute;top:5px;left:5px;right:5px;bottom:5px;border-radius:50%;border:3px solid transparent;border-top-color:#e74c3c;-webkit-animation:spin 3s linear infinite;animation:spin 3s linear infinite}#loader:after{content:"";position:absolute;top:15px;left:15px;right:15px;bottom:15px;border-radius:50%;border:3px solid transparent;border-top-color:#f9c922;-webkit-animation:spin 1.5s linear infinite;animation:spin 1.5s linear infinite}@-webkit-keyframes spin{0%{-webkit-transform:rotate(0);-ms-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);-ms-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes spin{0%{-webkit-transform:rotate(0);-ms-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);-ms-transform:rotate(360deg);transform:rotate(360deg)}}#loader-wrapper .loader-section{position:fixed;top:0;width:51%;height:100%;background:#222;z-index:1000}#loader-wrapper .loader-section.section-left{left:0}#loader-wrapper .loader-section.section-right{right:0}.loaded #loader-wrapper .loader-section.section-left{-webkit-transform:translateX(-100%);-ms-transform:translateX(-100%);transform:translateX(-100%);-webkit-transition:all .7s .3s cubic-bezier(.645,.045,.355,1);transition:all .7s .3s cubic-bezier(.645,.045,.355,1)}.loaded #loader-wrapper .loader-section.section-right{-webkit-transform:translateX(100%);-ms-transform:translateX(100%);transform:translateX(100%);-webkit-transition:all .7s .3s cubic-bezier(.645,.045,.355,1);transition:all .7s .3s cubic-bezier(.645,.045,.355,1)}.loaded #loader{opacity:0;-webkit-transition:all .3s ease-out;transition:all .3s ease-out}.loaded #loader-wrapper{visibility:hidden;-webkit-transform:translateY(-100%);-ms-transform:translateY(-100%);transform:translateY(-100%);-webkit-transition:all .3s 1s ease-out;transition:all .3s 1s ease-out}.ir{background-color:transparent;border:0;overflow:hidden}.ir:before{content:"";display:block;width:0;height:150%}.hidden{display:none!important;visibility:hidden}.visuallyhidden{border:0;clip:rect(0 0 0 0);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px}.visuallyhidden.focusable:active,.visuallyhidden.focusable:focus{clip:auto;height:auto;margin:0;overflow:visible;position:static;width:auto}.invisible{visibility:hidden}.clearfix:after,.clearfix:before{content:" ";display:table}.clearfix:after{clear:both}.icone-contador{position:absolute;right:0;top:0;background:rgba(255,0,0, 1.0); min-width: 50px;height: 20px;padding-left: 5px;padding-bottom: 10px; margin: 10px; border-radius: 3px;color: #FFFFFF;font-size: 14px;text-align: center;z-index: 10000;}#anuncios {width: 100%; z-index: 999; position: absolute; bottom: 0;}</style>
</head>
<body oncontextmenu="return false">
<?php if(empty($_GET["app-multi"])) {?>

<?php  $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android"); $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone"); $Windows  = stripos($_SERVER['HTTP_USER_AGENT'],"Windows"); ?>

<?php if($Android) { ?>

<div style="width:100%;text-align:center;margin-top:20px;font-size: 18px;font-family: Geneva, Arial, Helvetica, sans-serif;color:#333333;padding-left: 10px; padding-right: 10px"><img width="256" height="256" src="<?php echo $dados_app_multi_plataforma["url_logo"]; ?>" alt="<?php echo $dados_app_multi_plataforma["nome"]; ?>" title="<?php echo $dados_app_multi_plataforma["nome"]; ?>"><br><br><br><?php echo $array_lang[$dados_stm["idioma_painel"]]["aviso_instalar"]; ?><br><br><br><button id="instalar" type="button" class="btn btn-success" style="font-size: inherit;"><i class='fa fa-android'></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["btn_instalar"]; ?></button></div>

<?php } ?>

<?php if($iPhone) { ?>

<div style="width:100%;text-align:center;margin-top:20px;font-size: 18px;font-family: Geneva, Arial, Helvetica, sans-serif;color:#333333;padding-left: 10px; padding-right: 10px"><img width="256" height="256" src="<?php echo $dados_app_multi_plataforma["url_logo"]; ?>" alt="<?php echo $dados_app_multi_plataforma["nome"]; ?>" title="<?php echo $dados_app_multi_plataforma["nome"]; ?>"><br><br><br><?php echo $array_lang[$dados_stm["idioma_painel"]]["aviso_instalar"]; ?><br><br><br><button type="button" class="btn btn-success" style="font-size: inherit;" onClick="instalar_app_iphone();"><i class='fa fa-apple'></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["btn_instalar"]; ?></button></div>

<?php } ?>

<?php if($Windows) { ?>

<div style="width:100%;text-align:center;margin-top:20px;font-size: 18px;font-family: Geneva, Arial, Helvetica, sans-serif;color:#333333;padding-left: 10px; padding-right: 10px"><img width="256" height="256" src="<?php echo $dados_app_multi_plataforma["url_logo"]; ?>" alt="<?php echo $dados_app_multi_plataforma["nome"]; ?>" title="<?php echo $dados_app_multi_plataforma["nome"]; ?>"><br><br><br><?php echo $array_lang[$dados_stm["idioma_painel"]]["aviso_instalar_windows"]; ?><br><br><br><button id="instalar" type="button" class="btn btn-success" style="font-size: inherit;"><i class='fa fa-windows'></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["btn_instalar"]; ?></button></div>

<?php } ?>

<?php } else {?>
<div id="loader-wrapper"><div id="loader"></div><div class="loader-section section-left"></div><div class="loader-section section-right"></div></div>
<div class="icone-menu"><button type="button" id="sidebarCollapse" class="btn btn-sm btn-light"><i class="fa fa-bars"></i></button></div>
<nav id="sidebar">
   <div id="dismiss">
      <i class="fa fa-arrow-left"></i>
   </div>
   <div class="sidebar-header">
      <h4><?php echo $dados_app_multi_plataforma["nome"]; ?></h3>
   </div>
   <ul class="list-unstyled components">
    <?php if($dados_app_multi_plataforma["url_facebook"] || $dados_app_multi_plataforma["url_instagram"] || $dados_app_multi_plataforma["url_twitter"] || $dados_app_multi_plataforma["url_site"] || $dados_app_multi_plataforma["url_youtube"]) { ?>
      <li>
        <a href="#redessociais" data-bs-toggle="collapse" aria-expanded="false"><i class="fa fa-heart"></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["redes_sociais"]; ?></a>
        <ul class="collapse list-unstyled" id="redessociais">
          <?php if($dados_app_multi_plataforma["url_facebook"]) { ?>
           <li>
              <a href="<?php echo $dados_app_multi_plataforma["url_facebook"]; ?>" ><i class="fa fa-facebook-official"></i> Facebook</a>
           </li>
          <?php } ?>
          <?php if($dados_app_multi_plataforma["url_instagram"]) { ?>
           <li>
              <a href="<?php echo $dados_app_multi_plataforma["url_instagram"]; ?>"><i class="fa fa-instagram"></i> Instagram</a>
           </li>
          <?php } ?>
          <?php if($dados_app_multi_plataforma["url_twitter"]) { ?>
           <li>
              <a href="<?php echo $dados_app_multi_plataforma["url_twitter"]; ?>"><i class="fa fa-twitter"></i> Twitter</a>
           </li>
          <?php } ?>
          <?php if($dados_app_multi_plataforma["url_site"]) { ?>
           <li>
              <a href="<?php echo $dados_app_multi_plataforma["url_site"]; ?>"><i class="fa fa-globe"></i> Site</a>
           </li>
          <?php } ?>
          <?php if($dados_app_multi_plataforma["url_youtube"]) { ?>
           <li>
              <a href="<?php echo $dados_app_multi_plataforma["url_youtube"]; ?>"><i class="fa fa-youtube"></i> YouTube</a>
           </li>
          <?php } ?>
        </ul>
      </li>
    <?php } ?>
     <?php if($dados_app_multi_plataforma["url_chat"]) { ?>
      <li>
         <a href="#" onclick="abrir_link('<?php echo $dados_app_multi_plataforma["url_chat"]; ?>')"><i class="fa fa-comments"></i> Chat</a>
      </li>
     <?php } ?>
     <?php if($dados_app_multi_plataforma["whatsapp"]) { ?>
      <li>
         <a href="https://api.whatsapp.com/send/?phone=<?php echo $dados_app_multi_plataforma["whatsapp"]; ?>&text&app_win_absent=0"><i class="fa fa-whatsapp"></i> Whatsapp</a>
      </li>
     <?php } ?>
     <?php if($dados_app_multi_plataforma["text_prog"]) { ?>
      <li>
         <a href="#" onClick="abrir_link('/app-multi-plataforma/texto/<?php echo $dados_stm["login"]; ?>/programacao')"><i class="fa fa-calendar-check-o"></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["prog"]; ?></a>
      </li>
     <?php } ?>
     <?php if($dados_app_multi_plataforma["text_hist"]) { ?>
      <li>
         <a href="#" onClick="abrir_link('/app-multi-plataforma/texto/<?php echo $dados_stm["login"]; ?>/historia')"><i class="fa fa-info-circle"></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["hist"]; ?></a>
      </li>
     <?php } ?>
      <li>
         <a href="#app" data-bs-toggle="collapse" aria-expanded="false"><i class="fa fa-tablet"></i> App</a>
         <ul class="collapse list-unstyled" id="app">
            <li>
               <a href="#" onclick="abrir_link('/app-multi-plataforma/politica.html')"><i class="fa fa-gavel"></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["politica"]; ?></a>
            </li>
            <?php if($dados_app_multi_plataforma["email"]) { ?>
            <li>
               <a href="mailto:<?php echo $dados_app_multi_plataforma["email"]; ?>"><i class="fa fa-envelope"></i> E-mail</a>
            </li>
        <?php } ?>
         </ul>
      </li>
   </ul>
</nav>
<div id="conteudo_externo" style="position: absolute;left:0px;top:0px;width:100%;height:100%;background: #FFFFFF;margin:0;padding:0;z-index: 99999;display: none;">
<div style="width: 100%;height: 40px;margin:0;padding:0px 10px;background:<?php echo $dados_app_multi_plataforma["cor_menu_escuro"]; ?>"><div id="dismiss2"><i class="fa fa-arrow-left" onclick="$('#conteudo_externo').hide();$('#iframe_conteudo_externo').attr('src', '');"> <?php echo $array_lang[$dados_stm["idioma_painel"]]["voltar"]; ?></i></div></div>
<iframe target="_parent" id="iframe_conteudo_externo" frameborder="0" src="" style="position: absolute;width:100%;height:100%;z-index: 99999"></iframe>
</div>
<div class="overlay"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>   
<div id="player-app-logo-conteudo" style="overflow: hidden; background: rgba(255, 255, 255, 0.1); user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); width: 220px; height: 220px; border: 10px solid rgba(255, 255, 255, 0.2); border-radius: 50%;margin: 3% auto">
  <div id="player-app-logo" style="height: 100%; width: 100%; overflow: hidden; transition: opacity 2s ease 0s; background: url('<?php echo $logo_webtv ?>') 0% 0% / cover no-repeat; border-radius: 50%;"></div>
</div>
<div id="nome_webtv" style="margin-top:60px; margin-left: auto; margin-right: auto; width: 95%; height: 40px; font-size: 35px; line-height: 40px; color: <?php echo $dados_app_multi_plataforma["cor_texto"]; ?>;text-align: center; z-index: 9999"></div>
<a href="https://<?php echo $_SERVER["HTTP_HOST"]; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>/player?<?php echo time();?>">
<div id="player-app-play" style="cursor: pointer; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); width: 112px; height: 112px;margin-top:200px; margin-left: auto; margin-right: auto;" onclick="$('#player-app-play').width('100px');$('#player-app-play').height('100px');">
  <div id="player-app-buttonplay" style="width: 100%; height: 100%; transition: fill 0.5s ease 0s; fill: rgb(255, 255, 255); user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><svg x="0px" y="0px" viewBox="0 0 800 800"><path d="M713.9,400.5c1.4,171.2-137.8,314.4-313.9,314.3c-175.6,0-314.2-143-314-315c0.2-171.3,140.6-313.9,315-313.4 C574,87,715.4,228.9,713.9,400.5z M279.5,400.3c0,23.1,0,46.2,0,69.3c0,20.8-0.2,41.7,0.1,62.5c0.1,12.2,6,21.1,17,26.6 c11,5.5,21.2,3,31.2-2.9c23.3-13.6,46.8-27,70.2-40.5c49.8-28.6,99.6-57.1,149.3-85.8c18.1-10.4,18.7-38.7,1.1-49.4 c-74.5-45.4-149-90.8-223.5-136.1c-6-3.7-12.6-5.5-19.8-4.2c-15.7,2.9-25.5,14.4-25.5,30.5C279.4,313.6,279.5,357,279.5,400.3z"></path></svg></div></div></a>
<div id="anuncios"><a href="#" id="anuncio_link" target="_blank"><img id="anuncio" src="" border="0" style="width:100%; height: 100%; max-height: 60px; display: none;"></a></div>
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

var nome = "<?php echo $dados_app_multi_plataforma["nome"]; ?>";

if(nome.length > 22) {
$('#nome_webtv').hide().html('<marquee behavior="scroll" direction="left">'+nome+'</marquee>').fadeIn('slow');
} else {
$('#nome_webtv').hide().html(nome).fadeIn('slow');
}

}
function load_anuncio() {

$.ajax({
url:"/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>/anuncio",
type:"GET",
success: function(dados_anuncio, statusText, errorThrown){
  if(dados_anuncio) {
    var anuncio = dados_anuncio.split('|');
    $("#anuncio").fadeOut(500, function() {
          $("#anuncio").attr("src",anuncio[0]);
          $("#anuncio_link").attr("href",anuncio[1]);
      }).fadeIn(500);
  } else {
    $("#anuncio").hide();
  }
}
});

}
$(document).ready(function () { 
    $('#anuncio_link').on('click', function () {
        if ($('#anuncio_link').attr('disabled') == 'disabled') {
        return false;
      }
    });
var altura_botao_play = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight; 
$('#player-app-play').css('margin-top',(altura_botao_play-540)+"px");
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
setTimeout(ajustes_navegador,3000);
setTimeout(marquee_nome_webtv,2000);
setTimeout(function(){$('body').addClass('loaded');}, 1000);
load_anuncio();
setInterval(load_anuncio,60000);
});

setTimeout(function(){ 

if (Notification.permission !== 'granted') {

  if (!$.cookie('permissao_<?php echo $dados_stm["login"]; ?>')) {
    Swal.fire({
      title: '<?php echo $array_lang[$dados_stm["idioma_painel"]]["notificacao_permissao_titulo"]; ?>',
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText: '<?php echo $array_lang[$dados_stm["idioma_painel"]]["notificacao_permissao_sim"]; ?>',
      denyButtonText: '<?php echo $array_lang[$dados_stm["idioma_painel"]]["notificacao_permissao_nao"]; ?>',
    }).then((result) => {
      if (result.isConfirmed) {

        Notification.requestPermission().then(function (permission) { 
          if (permission === "granted") {
            <?php if($Windows) { ?>
            var notification = new Notification("<?php echo $array_lang[$dados_stm["idioma_painel"]]["notificacao_ativada"]; ?>");
            <?php } else { ?>
              var opcoes = {
                icon: 'https://<?php echo $_SERVER["HTTP_HOST"]; ?><?php echo $dados_app_multi_plataforma["url_logo"];?>',
                body: '<?php echo $array_lang[$dados_stm["idioma_painel"]]["notificacao_ativada"]; ?>'
              }
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
              registrations[0].showNotification('<?php echo $dados_app_multi_plataforma["nome"];?>', opcoes);
            });
            <?php } ?>
          }
        });
      } else if (result.isDenied) {
        $.cookie('permissao_<?php echo $dados_stm["login"]; ?>', 'negada', { expires: 15, path: '/' });
      }
    })
  }
}

}, 5000);

setInterval(function(){ 
  $.get("/player-app-multi-plataforma/<?php echo $dados_stm["login"];?>/carregar-notificacao", function( resposta ) {
  var JSON_VAR = JSON.stringify(resposta, null, 2);
  var dados = JSON.parse(JSON_VAR);
  exibir_notificacao(dados[0].codigo, dados[0].titulo, dados[0].url_icone, dados[0].url_imagem, dados[0].url_link, dados[0].mensagem);
  }, 'json');
}, 10000);

function exibir_notificacao(codigo, titulo, icone, imagem, link, mensagem) {
  var opcoes = {
    <?php if($Android || $iPhone) { ?>
    vibrate: [300, 100, 400],
    tag: codigo,
    data: {
     url: link
    },
    <?php } ?>  
    body: mensagem,
    icon: icone,
    image: imagem
  }

  <?php if($Windows) { ?>
  var notification = new Notification(titulo, opcoes);
  if(link) {
    notification.onclick = function(event) {event.preventDefault();window.open(link, '_self');};
  }
  notification.onshow = function() {$.cookie('app_<?php echo $dados_stm["login"]; ?>_notify_'+codigo+'', 'vizualizada', { expires: 360, path: '/' });};
  <?php } else { ?>
  navigator.serviceWorker.getRegistrations().then(function(registrations) {
  registrations[0].showNotification(titulo, opcoes);
  $.cookie('app_<?php echo $dados_stm["login"]; ?>_notify_'+codigo+'', 'vizualizada', { expires: 360, path: '/' });
  });
  <?php } ?>  
}
</script>
<?php } ?>
<script type="module">!function(){"use strict";const i={isOpen:!1,orientation:void 0},e=(i,e)=>{window.dispatchEvent(new CustomEvent("devtoolschange",{detail:{isOpen:i,orientation:e}}))},n=({emitEvents:n=!0}={})=>{const o=window.outerWidth-window.innerWidth>160,t=window.outerHeight-window.innerHeight>160,d=o?"vertical":"horizontal";t&&o||!(window.Firebug&&window.Firebug.chrome&&window.Firebug.chrome.isInitialized||o||t)?(i.isOpen&&n&&e(!1,void 0),i.isOpen=!1,i.orientation=void 0):(i.isOpen&&i.orientation===d||!n||e(!0,d),i.isOpen=!0,i.orientation=d)};n({emitEvents:!1}),setInterval(n,500),"undefined"!=typeof module&&module.exports?module.exports=i:window.devtools=i}();window.addEventListener('devtoolschange', event => {if(event.detail.isOpen){window.location.replace("https://player.<?php echo $dados_config["dominio_padrao"]; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>");}});</script>
<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js', {useCache: false, scope: '/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>'})
      .then(function(registration) {

    registration.addEventListener('updatefound', () => {
      newWorker = registration.installing;
      <?php if(!empty($_GET["app-multi"])) {?>
      newWorker.addEventListener('statechange', () => {
        switch (newWorker.state) {
          case 'installed':
            if (navigator.serviceWorker.controller) {
              Swal.fire({title: '',icon: 'warning',html: "<?php echo $array_lang[$dados_stm["idioma_painel"]]["aviso_atualizar"]; ?>"}).then((result) => { if (result.isConfirmed) { newWorker.postMessage({ action: 'skipWaiting' }); window.location.reload(); }})
            }
            break;
        }
      });
      <?php } ?>
    });
  });
}
if (('standalone' in navigator) && (!navigator.standalone)) {
    import('https://unpkg.com/pwacompat');
}
$(window).on("keydown",function(e){return 123==e.keyCode?!1:e.ctrlKey&&e.shiftKey&&73==e.keyCode?!1:e.ctrlKey&&73==e.keyCode?!1:void 0}),$(document).on("contextmenu",function(e){e.preventDefault()});
$(document).keydown(function(e){var o=String.fromCharCode(e.keyCode).toLowerCase();return!e.ctrlKey||"c"!=o&&"u"!=o?void 0:!1});</script>
<?php if(empty($_GET["app-multi"])) {?>
<script>
window.onload=function(){

let installPromptEvent;
window.addEventListener('beforeinstallprompt', (event) => {
  event.preventDefault();
  installPromptEvent = event;
});
window.addEventListener('appinstalled', () => {
  installPromptEvent = null;
  window.location = 'https://<?php echo $_SERVER["HTTP_HOST"]; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>?app-multi=<?php echo time(); ?>';
});
var buttonInstall = document.getElementById("instalar");
buttonInstall.addEventListener('click', (e) => {
  installPromptEvent.prompt();    
  installPromptEvent.userChoice.then((choice) => {}); 
});
}
function instalar_app_iphone() {
  Swal.fire({title: '',icon: 'info',html: "<?php echo $array_lang[$dados_stm["idioma_painel"]]["aviso_instalar_iphone"]; ?>"})
}
</script>
<?php } ?>
</body>
</html>