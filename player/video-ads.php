<?php
$login = query_string('1');
$aspectratio = query_string('4');
$capa = code_decode(query_string('5'),"D");
$ativar_contador = (query_string('6') == "sim") ? "sim" : "nao";
$ativar_compartilhamento = (query_string('7') == "sim") ? "sim" : "nao";

$verifica_stm = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$login."'"));

if($verifica_stm == 0) {
die ("Error! Missing data.");
}

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$login."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

if($dados_servidor["nome_principal"]) {
$servidor = $dados_servidor["nome_principal"].".".$dados_config["dominio_padrao"];
} else {
$servidor = $dados_servidor["nome"].".".$dados_config["dominio_padrao"];
}

$url_source = "https://".$servidor."/".$login."/".$login."/playlist.m3u8";

$dados_anuncio = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM anuncios_videos WHERE codigo_stm = '".$dados_stm["codigo"]."' ORDER BY RAND() LIMIT 1"));

$anuncio_tempo = $dados_anuncio["tempo"];
$anuncio_video = "https://".$servidor.":1443/play.php?login=".$login."&video=".$dados_anuncio["video"]."&ads=sim";

mysqli_query($conexao,"UPDATE anuncios_videos SET exibicoes = exibicoes+1 WHERE codigo = '".$dados_anuncio["codigo"]."'");

if(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == "pt") {
$lang_aguarde = "Aguarde";
$lang_botao = "Pular >";
$lang_segundos = "segundos";
} elseif(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == "en") {
$lang_aguarde = "Wait";
$lang_botao = "Skip >";
$lang_segundos = "seconds";
} elseif(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == "es") {
$lang_aguarde = "Espere";
$lang_botao = "Saltar >";
$lang_segundos = "segundos";
} else {
$lang_aguarde = "Aguarde";
$lang_botao = "Pular >";
$lang_segundos = "segundos";
}

// Verifica se tem anuncios cadastrados e se nao tiver exibe aviso de sem sinal
$total_anuncios = mysqli_fetch_array(mysqli_query($conexao,"SELECT COUNT(*) as total FROM anuncios_videos WHERE codigo_stm = '".$dados_stm["codigo"]."'"));

if($total_anuncios["total"] == 0) {
die('<!DOCTYPE HTML><html><head><title>Sem sinal | No signal</title><style>body {background-image:url("/img/nosignal.gif");background-repeat: no-repeat;background-size: 100% 100%;}html {height: 100%}</style></head><body><script>setTimeout(function() { location.reload(); }, 60000);</script></body></html>');
}
// Verifica se streaming esta funcionando, se nao estiver exibe aviso de sem sinal
$file_headers = @get_headers($url_source);
if($file_headers[0] == 'HTTP/1.0 404 Not Found') {
die('<!DOCTYPE HTML><html><head><title>Sem sinal | No signal</title><style>body {background-image:url("/img/nosignal.gif");background-repeat: no-repeat;background-size: 100% 100%;}html {height: 100%}</style></head><body><script>setTimeout(function() { location.reload(); }, 60000);</script></body></html>');
}

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
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript" charset="utf-8" src="//cdn.jsdelivr.net/npm/clappr@latest/dist/clappr.min.js"></script>
  <script type="text/javascript" charset="utf-8" src="//cdn.jsdelivr.net/gh/clappr/clappr-level-selector-plugin@latest/dist/level-selector.min.js"></script>
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <title>Player</title>
<style>*{margin:0;}html,body{height:100%;}.seek-disabled{display:none !important;}.icone-contador{position:absolute;left:0;top:0;background:rgba(255,0,0, 1.0); min-width: 50px;height: 20px;padding-left: 5px;padding-bottom: 10px; margin: 10px; border-radius: 3px;color: #FFFFFF;font-size: 14px;text-align: center;z-index: 10000;}.overlay{z-index:1;position:absolute;background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAACCAMAAABFaP0WAAAABlBMVEX///8AAABVwtN+AAAAAXRSTlMAQObYZgAAAAxJREFUCNdjYGQAAgAACwAC91XSmQAAAABJRU5ErkJggg==);background-repeat:repeat}ul{margin:0;padding:0}ul li{list-style-type:none}.circle-nav-wrapper{position:absolute;z-index:9999;right:0;top:0;width:50px;height:50pxoverflow:hidden}.circle-nav-wrapper .circle-nav-toggle{position:absolute;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;border-radius:50%;z-index:999999;width:30px;height:30px;border:2px solid #FFFFFF;transition:-webkit-transform .2s cubic-bezier(0,1.16,1,1);transition:transform .2s cubic-bezier(0,1.16,1,1);transition:transform .2s cubic-bezier(0,1.16,1,1),-webkit-transform .2s cubic-bezier(0,1.16,1,1);right:10px;top:10px}.circle-nav-wrapper .circle-nav-toggle i.material-icons{color:#FFFFFF}.circle-nav-wrapper .circle-nav-toggle:focus,.circle-nav-wrapper .circle-nav-toggle:hover{opacity:.8;cursor:pointer}.circle-nav-wrapper .circle-nav-toggle.circle-nav-open{border:2px solid #fff;-webkit-transform:rotate(135deg);transform:rotate(135deg)}.circle-nav-wrapper .circle-nav-toggle.circle-nav-open i.material-icons{color:#fff}.circle-nav-wrapper .circle-nav-panel{background:#ffc371;background:linear-gradient(to right,#ff5f6d,#ffc371);width:0;height:0;border-radius:50%;-webkit-transform:translate(-50%,-52.5%);transform:translate(-50%,-52.5%);transition:width .2s cubic-bezier(0,1.16,1,1),height .2s cubic-bezier(0,1.16,1,1);margin-left:261px}.circle-nav-wrapper .circle-nav-panel.circle-nav-open{width:500px;height:500px;opacity:.7;box-shadow:-5px 6px 0 6px rgba(255,95,109,.33)}.circle-nav-wrapper .circle-nav-menu{width:250px;height:250px}.circle-nav-wrapper .circle-nav-menu .circle-nav-item{position:absolute;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-ms-flex-align:center;align-items:center;background-color:#fff;border-radius:50%;width:15px;height:15px;visibility:hidden;transition:bottom .5s cubic-bezier(0,1.16,1,1),left .5s cubic-bezier(0,1.16,1,1),width .3s cubic-bezier(0,1.16,1,1),height .3s cubic-bezier(0,1.16,1,1)}.circle-nav-wrapper .circle-nav-menu .circle-nav-item-1,.circle-nav-wrapper .circle-nav-menu .circle-nav-item-2,.circle-nav-wrapper .circle-nav-menu .circle-nav-item-3,.circle-nav-wrapper .circle-nav-menu .circle-nav-item-4,.circle-nav-wrapper .circle-nav-menu .circle-nav-item-5{left:250px;bottom:250px}.circle-nav-wrapper .circle-nav-menu .circle-nav-item i{color:#ff5f6d;font-size:.6em;transition:font .3s cubic-bezier(0,1.16,1,1)}.circle-nav-wrapper .circle-nav-menu .circle-nav-item i{display:block}.circle-nav-wrapper .circle-nav-menu .circle-nav-item span{display:none}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item{width:40px;height:40px;visibility:visible;transition:bottom .3s cubic-bezier(0,1.16,1,1),left .3s cubic-bezier(0,1.16,1,1),width .2s cubic-bezier(0,1.16,1,1),height .2s cubic-bezier(0,1.16,1,1)}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item:focus,.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item:hover{cursor:pointer;opacity:.8}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item i{font-size:1.4em;transition:font .1s cubic-bezier(0,1.16,1,1)}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item.circle-nav-item-1{bottom:200px;left:30px;transition-delay:.2s}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item.circle-nav-item-2{bottom:140px;left:50px;transition-delay:.4s}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item.circle-nav-item-3{bottom:90px;left:85px;transition-delay:.6s}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item.circle-nav-item-4{bottom:52px;left:132px;transition-delay:.8s}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item.circle-nav-item-5{bottom:28px;left:187px;transition-delay:1s}</style>
</head>
<body>
<?php if($ativar_contador == "sim") { ?><div class="icone-contador"><i class="fa fa-users"></i> <strong><span id="contador_online"></span></strong></div><?php } ?>
<?php if($ativar_compartilhamento == "sim") { ?><nav id="circle-nav-wrapper" class="circle-nav-wrapper" data-status-botao="fechado"> <div class="circle-nav-toggle"><i class="fa fa-plus" style="color: #FFFFFF"></i></div><div class="circle-nav-panel"></div><ul class="circle-nav-menu"> <a href="https://facebook.com/sharer/sharer.php?u=https://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank"> <li class="circle-nav-item circle-nav-item-1"><i class="fa fa-facebook fa-2x"></i></li></a> <a href="https://twitter.com/share?url=https://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank"> <li class="circle-nav-item circle-nav-item-2"><i class="fa fa-twitter fa-2x"></i></li></a> <a href="https://pinterest.com/pin/create/bookmarklet/?&url=https://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank"> <li class="circle-nav-item circle-nav-item-3"><i class="fa fa-pinterest fa-2x"></i></li></a> <a href="tg://msg_url?url=https://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank"> <li class="circle-nav-item circle-nav-item-4"><i class="fa fa-telegram fa-2x"></i></li></a> <a href="whatsapp://send?text=WebTV https://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank"> <li class="circle-nav-item circle-nav-item-5"><i class="fa fa-whatsapp fa-2x"></i></li></a> </ul> </nav><?php } ?>
<div  class="container-fluid">
    <div class="row">
    	<?php if($aspectratio == "16:9") { ?>
        <div class="embed-responsive embed-responsive-16by9">
        <?php } ?>
    	<?php if($aspectratio == "4:3") { ?>
        <div class="embed-responsive embed-responsive-4by3">
        <?php } ?>
        <div id="player_webtv" class="embed-responsive-item"></div>
        </div>
</div>
</div>
    <script type="text/javascript" charset="utf-8">
$(".circle-nav-toggle").on("click",function(){"fechado"==$("#circle-nav-wrapper").data("status-botao")?($("#circle-nav-wrapper").css("width","250px"),$("#circle-nav-wrapper").css("height","250px"),$(".circle-nav-menu").css("width","250px"),$("#circle-nav-wrapper").css("height","250px"),$("#circle-nav-wrapper").data("status-botao","aberto")):($("#circle-nav-wrapper").css("width","50px"),$("#circle-nav-wrapper").css("height","50px"),$(".circle-nav-menu").css("width","50px"),$("#circle-nav-wrapper").css("height","50px"),$("#circle-nav-wrapper").data("status-botao","fechado"))});
  function contador(){
    $.ajax({
    url: "/contador/<?php echo $login; ?>",
    success:
      function(total_online){
      $("#contador_online").html(total_online);
      }
    })
}
  window.onload = function() {
  var player = new Clappr.Player({
    <?php if($dados_stm["transcoder_instalado"] == "sim") { ?>
  plugins: [LevelSelector],
  levelSelectorConfig: {
      labelCallback: function(playbackLevel, customLabel) {
          return playbackLevel.level.height+'p'; // High 720p
      }
  },
  <?php } ?>
    source: '<?php echo $url_source; ?>',
    parentId: '#player_webtv',
	width: '100%',
    height: '100%',
    mute: <?php echo query_string('3'); ?>,
    hideMediaControl: true,
    poster: '<?php echo $capa; ?>',
	autoPlay: <?php echo query_string('2'); ?>,
  <?php if($dados_stm["watermark_posicao"]) { ?>
    position: '<?php echo $clappr_watermark; ?> ',
    watermark: 'https://<?php echo $servidor;?>:1443/watermark.php?login=<?php echo $login;?>',
  <?php } ?>
	plugins: [ClapprAds],
	ads: {
		midRoll: {
            at: <?php echo $anuncio_tempo; ?>,
            src: '<?php echo $anuncio_video; ?>',
            skip: true,
            timeout: 5
        }
	}
  });
contador();
setInterval (contador,30000);
}
!function(t){var i=function(t,i,o){this.text={wait:"<?php echo $lang_aguarde; ?> % <?php echo $lang_segundos; ?>...",skip:"<?php echo $lang_botao; ?>"},this.onEnd=!1,this.wrapper=this._initWrapper(),this.video=this._initVideo(t),this.wrapper.appendChild(this.video),this.muteButton=this._initMuteButton(),i&&(this.skipButton=this._initSkipButton(o),this.wrapper.appendChild(this.skipButton))};i.prototype._initWrapper=function(){var t=document.createElement("div");return t.style.display="block",t.style.position="absolute",t.style.width="100%",t.style.height="100%",t.style.top="0px",t.style.left="0px",t.style.zIndex=1e4,t},i.prototype._initVideo=function(t){var i=document.createElement("video");return i.style.display="block",i.style.position="absolute",i.style.width="100%",i.style.height="100%",i.controls=!1,i.src=t,i.addEventListener("ended",this._end.bind(this)),i},i.prototype._initSkipButton=function(t){var i=document.createElement("button");return i.style.display="none",i.style.position="absolute",i.style.bottom="45px",i.style.right="0px",i.style.padding="15px",i.style.backgroundColor="#000",i.style.border="solid thin #000",i.style.fontSize="12px",i.style.color="#FFF",i.style.right="-1px",i.disabled=!0,i.addEventListener("click",this._end.bind(this)),this._skipButtonCountdown(i,t),i},i.prototype._initMuteButton=function(){var t=document.createElement("div");return t.style.position="absolute",t.style.bottom="145px",t.style.right="100px",t.style.padding="15px",t.style.backgroundColor="#000",t.style.border="solid thin #000",t.style.fontSize="12px",t.style.color="#FFF",t.innerText="Volume",t.addEventListener("click",function(){this.video.muted=!this.video.muted}.bind(this)),t},i.prototype._skipButtonCountdown=function(t,i){var o=setInterval(function(){t.style.display="block",i>0?(t.innerHTML=this.text.wait.replace("%",i),i--):(t.innerHTML=this.text.skip,t.disabled=!1,clearInterval(o))}.bind(this),1e3)},i.prototype._end=function(t){t&&t.preventDefault(),this.wrapper.parentNode.removeChild(this.wrapper),"function"==typeof this.onEnd&&this.onEnd()},i.prototype.play=function(){this.video.play()},i.prototype.pause=function(){this.video.pause()},i.prototype.attachMuteButton=function(){this.wrapper.appendChild(this.muteButton)};var o=Clappr.UICorePlugin.extend({_isAdPlaying:!1,_hasPreRollPlayed:!1,_hasPostRollPlayed:!1,_preRoll:!1,_midRoll:!1,_postRoll:!1,_videoText:{},_rand:function(t,i){return Math.floor(Math.random()*(i-t+1))+t},name:"clappr_ads",initialize:function(){if("ads"in this._options){if("preRoll"in this._options.ads){if(!("src"in this._options.ads.preRoll))throw"No source";this._preRoll=this._options.ads.preRoll}if("midRoll"in this._options.ads){if(!("src"in this._options.ads.midRoll))throw"No source";this._midRoll=this._options.ads.midRoll,"string"==typeof this._midRoll.src&&(this._midRoll.src=[this._midRoll.src]),"at"in this._midRoll&&"object"!=typeof this._midRoll.at&&(this._midRoll.at=[this._midRoll.at])}if("postRoll"in this._options.ads){if(!("src"in this._options.ads.postRoll))throw"No source";this._postRoll=this._options.ads.postRoll}if("text"in this._options.ads){var t=this._options.ads.text;"wait"in t&&(this._videoText.wait=t.wait),"skip"in t&&(this._videoText.skip=t.skip)}}this.bindEvents()},bindEvents:function(){this.listenTo(this.core,Clappr.Events.CORE_READY,function(){var t=this.core.getCurrentContainer();t.listenTo(t.playback,Clappr.Events.PLAYBACK_PLAY,this._onPlaybackPlay.bind(this,t)),t.listenTo(t.playback,Clappr.Events.PLAYBACK_TIMEUPDATE,this._onPlaybackTimeUpdate.bind(this,t)),t.listenTo(t.playback,Clappr.Events.PLAYBACK_ENDED,this._onPlaybackEnd.bind(this))}.bind(this))},_onPlaybackPlay:function(t){if(this._isAdPlaying)t.playback.pause();else{if(!this._preRoll||this._hasPreRollPlayed)return;this.playPreRoll(t)}},_onPlaybackTimeUpdate:function(t){var i=t.currentTime,o=t.getDuration();if(this._midRoll){var s;s="at"in this._midRoll?this._midRoll.at:[Math.floor(o/2)];for(var l,e,n=!1,a=0;a<s.length;a++)l=s[a],Math.floor(i)==l&&(e=a,n=!0);n&&(this._midRoll.at.length===this._midRoll.src.length?this.playMidRoll(t,e):this.playMidRoll(t))}this._postRoll&&!this._hasPostRollPlayed&&i&&Math.round(1e3*i)==Math.round(1e3*o)&&this.playPostRoll(t)},_onPlaybackEnd:function(){this._isAdPlaying=!1,this._hasPreRollPlayed=!1},playPreRoll:function(t){var o;this._isAdPlaying||(o="object"==typeof this._preRoll.src?this._preRoll.src[this._rand(0,this._preRoll.src.length-1)]:this._preRoll.src,t.playback.pause(),video=new i(o,this._preRoll.skip,this._preRoll.timeout),video.onEnd=this._onPreRollEnd.bind(this,video,t.playback),"wait"in this._videoText&&(video.text.wait=this._videoText.wait),"skip"in this._videoText&&(video.text.skip=this._videoText.skip),this._preRoll.muteButton&&video.attachMuteButton(),t.$el.append(video.wrapper),video.play(),"onPlay"in this._preRoll&&this._preRoll.onPlay(this._preRoll,{position:"preroll"}),this._hasPreRollPlayed=!0)},playMidRoll:function(t,o){var s;this._isAdPlaying||(this._isAdPlaying=!0,t.playback.pause(),t.playback.seek(parseInt(Math.floor(t.currentTime+1))),s=void 0===o?this._midRoll.src[this._rand(0,this._midRoll.src.length-1)]:this._midRoll.src[o],video=new i(s,this._midRoll.skip,this._midRoll.timeout),this._midRoll.muteButton&&video.attachMuteButton(),t.$el.append(video.wrapper),video.play(),"onPlay"in this._midRoll&&this._midRoll.onPlay(this._midRoll,{position:"preroll"}),video.onEnd=function(){this._isAdPlaying=!1,t.playback.play()}.bind(this))},playPostRoll:function(t){var o;this._isAdPlaying||(this._isAdPlaying=!0,this._hasPostRollPlayed=!0,t.playback.pause(),o="object"==typeof this._postRoll.src?this._postRoll.src[this._rand(0,this._postRoll.src.length-1)]:this._postRoll.src,video=new i(o),this._postRoll.muteButton&&video.attachMuteButton(),t.$el.append(video.wrapper),video.play(),"onPlay"in this._postRoll&&this._postRoll.onPlay(this._postRoll,{position:"preroll"}),video.onEnd=function(){this._isAdPlaying=!1,t.playback.play()}.bind(this))},_onPreRollEnd:function(t,i){this._isAdPlaying=!1,setTimeout(function(){i.play()},100)}});t.ClapprAds=o}(window);
!function(e,o,l,c){e.fn.circleNav=function(o){var l=e.extend({},e.fn.circleNav.settings,o);return this.each(function(){var o=e(this),c=e(".circle-nav-toggle"),a=e(".circle-nav-panel"),n=e(".circle-nav-menu");l.hasOverlay&&0==e(".circle-nav-overlay").length&&(e("body").append("<div class='circle-nav-overlay'></div>"),e(".circle-nav-overlay").css({top:"0",right:"0",bottom:"0",left:"0",position:"fixed","background-color":l.overlayColor,opacity:l.overlayOpacity,"z-index":"-1",display:"none"})),e(".circle-nav-toggle, .circle-nav-overlay").on("click",function(){o.stop().toggleClass("circle-nav-open"),c.stop().toggleClass("circle-nav-open"),a.stop().toggleClass("circle-nav-open"),n.stop().toggleClass("circle-nav-open"),e(".circle-nav-overlay").fadeToggle(),e("body").css("overflow")?e("body, html").css("overflow",""):e("body, html").css("overflow","hidden")})})},e.fn.circleNav.settings={hasOverlay:!0,overlayColor:"#fff",overlayOpacity:".7"}}(jQuery,window,document);
$(function(){$("#circle-nav-wrapper").circleNav()});
  </script>
</body>
</html>