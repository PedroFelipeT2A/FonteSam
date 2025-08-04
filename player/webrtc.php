<?php
session_start();

$login = query_string('1');
$autoplay = query_string('2');
$mudo = query_string('3');
$aspectratio = query_string('4');
$capa_vodthumb = code_decode(query_string('5'),"D");
$ativar_compartilhamento = (query_string('6') == "sim") ? "sim" : "nao";
$ativar_contador = (query_string('7') == "sim") ? "sim" : "nao";

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

if(query_string('2') == "checar-chave") {

$webrtc_chave = query_string('3');

if($dados_stm["webrtc_chave"] != $webrtc_chave || empty($webrtc_chave)){
echo "reload";
exit();
}

exit();
}

?>
<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
      <title>Player</title>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/js-cookie@2.2.1/src/js.cookie.js" integrity="sha256-P8jY+MCe6X2cjNSmF4rQvZIanL5VwUUT4MBnOMncjRU=" crossorigin="anonymous"></script>
      <script type="text/javascript" src="/inc-webrtc/adapter-latest.js"></script>
      <link  href="/inc-webrtc/plyr.css" rel="stylesheet" />
    <script src="/inc-webrtc/plyr.polyfilled.js"></script>
  <title>Player</title>
<style>*{margin:0;}html,body{height:100%; background-color:#000000;overflow: hidden;}.icone-contador{position:absolute;left:0;top:0;background:rgba(255,0,0, 1.0); min-width: 50px;height: 20px;padding-left: 5px;padding-bottom: 10px; margin: 10px; border-radius: 3px;color: #FFFFFF;font-size: 14px;text-align: center;z-index: 10000;}.overlay{z-index:1;position:absolute;background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAACCAMAAABFaP0WAAAABlBMVEX///8AAABVwtN+AAAAAXRSTlMAQObYZgAAAAxJREFUCNdjYGQAAgAACwAC91XSmQAAAABJRU5ErkJggg==);background-repeat:repeat}ul{margin:0;padding:0}ul li{list-style-type:none}.circle-nav-wrapper{position:absolute;z-index:9999;right:0;top:0;width:50px;height:50pxoverflow:hidden}.circle-nav-wrapper .circle-nav-toggle{position:absolute;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;border-radius:50%;z-index:999999;width:30px;height:30px;border:2px solid #FFFFFF;transition:-webkit-transform .2s cubic-bezier(0,1.16,1,1);transition:transform .2s cubic-bezier(0,1.16,1,1);transition:transform .2s cubic-bezier(0,1.16,1,1),-webkit-transform .2s cubic-bezier(0,1.16,1,1);right:10px;top:10px}.circle-nav-wrapper .circle-nav-toggle i.material-icons{color:#FFFFFF}.circle-nav-wrapper .circle-nav-toggle:focus,.circle-nav-wrapper .circle-nav-toggle:hover{opacity:.8;cursor:pointer}.circle-nav-wrapper .circle-nav-toggle.circle-nav-open{border:2px solid #fff;-webkit-transform:rotate(135deg);transform:rotate(135deg)}.circle-nav-wrapper .circle-nav-toggle.circle-nav-open i.material-icons{color:#fff}.circle-nav-wrapper .circle-nav-panel{background:#ffc371;background:linear-gradient(to right,#ff5f6d,#ffc371);width:0;height:0;border-radius:50%;-webkit-transform:translate(-50%,-52.5%);transform:translate(-50%,-52.5%);transition:width .2s cubic-bezier(0,1.16,1,1),height .2s cubic-bezier(0,1.16,1,1);margin-left:261px}.circle-nav-wrapper .circle-nav-panel.circle-nav-open{width:500px;height:500px;opacity:.7;box-shadow:-5px 6px 0 6px rgba(255,95,109,.33)}.circle-nav-wrapper .circle-nav-menu{width:250px;height:250px}.circle-nav-wrapper .circle-nav-menu .circle-nav-item{position:absolute;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-ms-flex-align:center;align-items:center;background-color:#fff;border-radius:50%;width:15px;height:15px;visibility:hidden;transition:bottom .5s cubic-bezier(0,1.16,1,1),left .5s cubic-bezier(0,1.16,1,1),width .3s cubic-bezier(0,1.16,1,1),height .3s cubic-bezier(0,1.16,1,1)}.circle-nav-wrapper .circle-nav-menu .circle-nav-item-1,.circle-nav-wrapper .circle-nav-menu .circle-nav-item-2,.circle-nav-wrapper .circle-nav-menu .circle-nav-item-3,.circle-nav-wrapper .circle-nav-menu .circle-nav-item-4,.circle-nav-wrapper .circle-nav-menu .circle-nav-item-5{left:250px;bottom:250px}.circle-nav-wrapper .circle-nav-menu .circle-nav-item i{color:#ff5f6d;font-size:.6em;transition:font .3s cubic-bezier(0,1.16,1,1)}.circle-nav-wrapper .circle-nav-menu .circle-nav-item i{display:block}.circle-nav-wrapper .circle-nav-menu .circle-nav-item span{display:none}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item{width:40px;height:40px;visibility:visible;transition:bottom .3s cubic-bezier(0,1.16,1,1),left .3s cubic-bezier(0,1.16,1,1),width .2s cubic-bezier(0,1.16,1,1),height .2s cubic-bezier(0,1.16,1,1)}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item:focus,.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item:hover{cursor:pointer;opacity:.8}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item i{font-size:1.4em;transition:font .1s cubic-bezier(0,1.16,1,1)}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item.circle-nav-item-1{bottom:200px;left:30px;transition-delay:.2s}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item.circle-nav-item-2{bottom:140px;left:50px;transition-delay:.4s}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item.circle-nav-item-3{bottom:90px;left:85px;transition-delay:.6s}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item.circle-nav-item-4{bottom:52px;left:132px;transition-delay:.8s}.circle-nav-wrapper .circle-nav-menu.circle-nav-open .circle-nav-item.circle-nav-item-5{bottom:28px;left:187px;transition-delay:1s}#play-video-container{z-index: 999;position: absolute;display:flex;justify-content:center;align-items:center;}#play-video-container, #player-video { height: 100vh; width: 100%;}.plyr{display: none;} .plyr__time, .plyr__progress, .plyr__control--overlaid{display: none!important;}</style>
</head>
<body>
<?php if($ativar_contador == "sim") { ?><div class="icone-contador"><i class="fa fa-users"></i> <strong><span id="contador_online"></span></strong></div><?php } ?>
<?php if($ativar_compartilhamento == "sim") { ?><nav id="circle-nav-wrapper" class="circle-nav-wrapper" data-status-botao="fechado"> <div class="circle-nav-toggle"><i class="fa fa-plus" style="color: #FFFFFF"></i></div><div class="circle-nav-panel"></div><ul class="circle-nav-menu"> <a href="https://facebook.com/sharer/sharer.php?u=https://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank"> <li class="circle-nav-item circle-nav-item-1"><i class="fa fa-facebook fa-2x"></i></li></a> <a href="https://twitter.com/share?url=https://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank"> <li class="circle-nav-item circle-nav-item-2"><i class="fa fa-twitter fa-2x"></i></li></a> <a href="https://pinterest.com/pin/create/bookmarklet/?&url=https://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank"> <li class="circle-nav-item circle-nav-item-3"><i class="fa fa-pinterest fa-2x"></i></li></a> <a href="tg://msg_url?url=https://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank"> <li class="circle-nav-item circle-nav-item-4"><i class="fa fa-telegram fa-2x"></i></li></a> <a href="whatsapp://send?text=WebTV https://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" target="_blank"> <li class="circle-nav-item circle-nav-item-5"><i class="fa fa-whatsapp fa-2x"></i></li></a> </ul> </nav><?php } ?>
<div id="play-video-container">
  <button id="player-btn" type="button" class="btn" style="z-index: 1000;"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill:#ffffff; width: 72px; height: 72px; "><path d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM188.3 147.1c-7.6 4.2-12.3 12.3-12.3 20.9V344c0 8.7 4.7 16.7 12.3 20.9s16.8 4.1 24.3-.5l144-88c7.1-4.4 11.5-12.1 11.5-20.5s-4.4-16.1-11.5-20.5l-144-88c-7.4-4.5-16.7-4.7-24.3-.5z"/></svg></button>
<div class="alert alert-danger text-center" id="error-panel" style="display: none"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill:#721c24; width: 32px; height: 32px; "><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg><br>Transmiss&atilde;o n&atilde;o dispon&iacute;vel no momento, tente novamente mais tarde.</div>
</div>
<video id="player-video" playsinline controls <?php echo $mudo; ?> <?php echo $autoplay; ?>></video>
<script type="text/javascript" charset="utf-8">
let config_stm = {
    playSdpURL: "wss://<?php echo $servidor; ?>/webrtc-session.json",
    playApplicationName: "<?php echo $login; ?>",
    playStreamName: "<?php echo $dados_stm["webrtc_chave"]; ?>"
};
const player = new Plyr("#player-video", {disableContextMenu: true});
</script>
<script type="module" crossorigin="use-credentials" src="/inc-webrtc/play.js"></script>
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
function checar_chave(){
    $.ajax({
    url: "/webrtc/<?php echo $login; ?>/checar-chave/<?php echo $dados_stm["webrtc_chave"]; ?>",
    success:
      function(resposta){
        if(resposta == "reload") {
          window.location.reload();
        }
      }
    })
}
window.onload = function() {
  contador();
  setInterval (contador,30000);
  checar_chave();
  setInterval (checar_chave,20000);
}
!function(e,o,l,c){e.fn.circleNav=function(o){var l=e.extend({},e.fn.circleNav.settings,o);return this.each(function(){var o=e(this),c=e(".circle-nav-toggle"),a=e(".circle-nav-panel"),n=e(".circle-nav-menu");l.hasOverlay&&0==e(".circle-nav-overlay").length&&(e("body").append("<div class='circle-nav-overlay'></div>"),e(".circle-nav-overlay").css({top:"0",right:"0",bottom:"0",left:"0",position:"fixed","background-color":l.overlayColor,opacity:l.overlayOpacity,"z-index":"-1",display:"none"})),e(".circle-nav-toggle, .circle-nav-overlay").on("click",function(){o.stop().toggleClass("circle-nav-open"),c.stop().toggleClass("circle-nav-open"),a.stop().toggleClass("circle-nav-open"),n.stop().toggleClass("circle-nav-open"),e(".circle-nav-overlay").fadeToggle(),e("body").css("overflow")?e("body, html").css("overflow",""):e("body, html").css("overflow","hidden")})})},e.fn.circleNav.settings={hasOverlay:!0,overlayColor:"#fff",overlayOpacity:".7"}}(jQuery,window,document);
$(function(){$("#circle-nav-wrapper").circleNav()});
  </script>
</body>
</html>