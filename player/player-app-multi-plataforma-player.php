<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />  
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://vjs.zencdn.net/7.8.3/video.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@videojs/http-streaming@1.10.3/dist/videojs-http-streaming.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/videojs-watermark@2.0.0/dist/videojs-watermark.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
<link href="//vjs.zencdn.net/7.8.3/video-js.css" rel="stylesheet">
  <title>Player</title>
<style>*{margin:0}body,html{height:100%}.video-js .vjs-time-control{display:none}.video-js .vjs-progress-control{display:none}.video-js .vjs-menu-button-inline.vjs-slider-active,.video-js .vjs-menu-button-inline:focus,.video-js .vjs-menu-button-inline:hover,.video-js.vjs-no-flex .vjs-menu-button-inline{width:10em}.video-js .vjs-controls-disabled .vjs-big-play-button{display:none!important}.video-js .vjs-control{width:3em}.video-js .vjs-menu-button-inline:before{width:1.5em}.vjs-menu-button-inline .vjs-menu{left:3em}.video-js.vjs-ended .vjs-big-play-button,.video-js.vjs-paused .vjs-big-play-button,.vjs-paused.vjs-has-started.video-js .vjs-big-play-button{display:block}.video-js .vjs-load-progress div,.vjs-seeking .vjs-big-play-button,.vjs-waiting .vjs-big-play-button{display:none!important}.video-js .vjs-mouse-display:after,.video-js .vjs-play-progress:after{padding:0 .4em .3em}.video-js.vjs-ended .vjs-loading-spinner{display:none}.video-js.vjs-ended .vjs-big-play-button{display:block!important}.video-js.vjs-paused .vjs-big-play-button,.vjs-paused.vjs-has-started.video-js .vjs-big-play-button,video-js.vjs-ended .vjs-big-play-button{display:block}.video-js .vjs-big-play-button{top:50%;left:50%;margin-left:-1.5em;margin-top:-1em}.video-js .vjs-big-play-button{background-color:rgba(14,34,61,.7);font-size:3.5em;border-radius:12%;height:1.4em!important;line-height:1.4em!important;margin-top:-.7em!important}.video-js .vjs-big-play-button:active,.video-js .vjs-big-play-button:focus,.video-js:hover .vjs-big-play-button{background-color:#0e223d}.video-js .vjs-loading-spinner{border-color:rgba(14,34,61,.84)}.video-js .vjs-control-bar2{background-color:#0e223d}.video-js .vjs-control-bar{background-color:#0e223d!important;color:#fff;font-size:14px}.video-js .vjs-play-progress,.video-js .vjs-volume-level{background-color:rgba(14,34,61,.8)}.video-js.vjs-watermark{display:block}.video-js .vjs-watermark-content{opacity:0.99;position:absolute;padding:5px;-webkit-transition:visibility 1s,opacity 1s;-moz-transition:visibility 1s,opacity 1s;-ms-transition:visibility 1s,opacity 1s;-o-transition:visibility 1s,opacity 1s;transition:visibility 1s,opacity 1s}.video-js .vjs-watermark-top-right{right:0;top:0}.video-js .vjs-watermark-top-left{left:0;top:0}.video-js .vjs-watermark-bottom-right{right:0;bottom:30px}.video-js .vjs-watermark-bottom-left{left:0;bottom:30px}.video-js.vjs-user-inactive.vjs-playing .vjs-watermark-fade{opacity:0}.player_webtv-dimensions .vjs-fluid{padding-top:0!important}.icone-menu{position:absolute;left:0;top:0;width: 32px;height: 32px;color: #FFFFFF;font-size: 32px;text-align: center;z-index: 5000;}.video-js .vjs-control-bar {background-color: <?php echo $dados_app_multi_plataforma["cor_menu_escuro"]; ?>!important;color: <?php echo $dados_app_multi_plataforma["cor_texto"]; ?>;font-size: 14px;}.icone-contador{position:absolute;right:0;top:0;background:rgba(255,0,0, 1.0); min-width: 50px;height: 20px;padding-left: 5px;padding-bottom: 10px; margin: 10px; border-radius: 3px;color: #FFFFFF;font-size: 14px;text-align: center;z-index: 10000;}</style>
</head>
<body>
<?php if($dados_app_multi_plataforma["contador"] == "sim") {?><div class="icone-contador"><i class="fa fa-users"></i> <strong><span id="contador_online"></span></strong></div><?php } ?>
<div class="icone-menu"><button type="button" class="btn btn-sm" style="background-color: transparent; color: #FFFFFF" onclick="window.location = 'javascript:history.back(1)';"><i class="fa fa-arrow-left"> <?php echo $array_lang[$dados_stm["idioma_painel"]]["voltar"]; ?></i></button></div>
<video id="player_webtv" class="video-js vjs-big-play-centered vjs-fluid" controls preload="auto" width="100%" height="300" style="padding-top: 0!important">
  <source src="<?php echo $url_source; ?>" type="application/x-mpegURL">
</video>
<script type="text/javascript" charset="utf-8">
window.onload = function() {
        var altura = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
        $("#player_webtv").height(altura+'px');
      var player = videojs('player_webtv');
      <?php if($dados_stm["watermark_posicao"]) { ?>
      player.watermark({
        image: 'https://<?php echo $servidor;?>:1443/watermark.php?login=<?php echo $login;?>',
        position: '<?php echo $watermark_posicao;?>'
      });
      <?php } ?>
      player.play();
      }
<?php if($dados_app_multi_plataforma["contador"] == "sim") {?>
function contador(){
    $.ajax({
    url: "/contador/<?php echo $login; ?>",
    success:
      function(total_online){
      $("#contador_online").html(total_online);
      }
    })
}
contador();
setInterval (contador,30000);
<?php } ?>
</script>
</body>
</html>