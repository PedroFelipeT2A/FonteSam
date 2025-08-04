<?php
session_start();
if(query_string('2') != "lista") {
unset($_SESSION["last"]);
}

function formatar_data_msg($formato,$data,$timezone){$formato=(preg_match('/:/i',$data))?$formato:str_replace("H:i:s","",$formato);$offset=get_tz_offset('America/Sao_Paulo',$timezone);if(preg_match('/:/i',$data)){$nova_data=strtotime(''.$offset.' hour',strtotime($data));$nova_data=date($formato,$nova_data);}else{$nova_data=date($formato,strtotime($data));}return $nova_data;}

function get_tz_offset($remote_tz, $origin_tz = null) { if($origin_tz === null) { if(!is_string($origin_tz = date_default_timezone_get())) { return false; } } $origin_dtz = new DateTimeZone($origin_tz); $remote_dtz = new DateTimeZone($remote_tz); $origin_dt = new DateTime("now", $origin_dtz); $remote_dt = new DateTime("now", $remote_dtz); $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt); $offset = $offset/3600; return $offset; }

$login = query_string('1');
$servidor = code_decode(query_string('2'),"D");
$autoplay = query_string('3');
$capa = code_decode(query_string('4'),"D");
$ativar_contador = (query_string('5') == "sim" || query_string('5') == "") ? "sim" : "nao";
$mudo = (query_string('6') == "true") ? "true" : "false";

$verifica_stm = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$login."'"));

if($verifica_stm == 0) {
die ("Error! Missing data.");
}

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$login."'"));
$dados_servidor = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

if($dados_servidor["nome_principal"]) {
$servidor = $dados_servidor["nome_principal"].".".$dados_config["dominio_padrao"];
} else {
$servidor = $dados_servidor["nome"].".".$dados_config["dominio_padrao"];
}

if($dados_stm["transcoder_instalado"] == "sim") {
$url_source = "https://".$servidor."/".$login."/smil:transcoder.smil/playlist.m3u8";
} else {
$url_source = "https://".$servidor."/".$login."/".$login."/playlist.m3u8";
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
//////////////////////////////////////////////////////////////////
////////////////////////////    Chat    ////////////////////////// 
//////////////////////////////////////////////////////////////////
if(query_string('4') == "sair" || query_string('5') == "sair" || query_string('6') == "sair" || query_string('7') == "sair") {
  mysqli_query($conexao,"DELETE FROM chat_usuarios WHERE login = '".$dados_stm["login"]."' AND hash = '".$_SESSION["hash_usuario"]."'");
  unset($_SESSION["nome_usuario"]);
  unset($_SESSION["hash_usuario"]);
  unset($_SESSION["last"]);
}

if($_POST["text"]) {
  if(strlen($_POST["text"]) < 300) {

    $array_bad_words = array("cú", "cu", "c u", "puta", "gay", "viado", "bicha", "bixa", "veado", "lesbica", "lésbica", "fuck", "bunda", "v i a d o", "puto", "fuck", "culo", "ass");

    $msg = str_replace($array_bad_words, "🤬", $_POST["text"]);

    mysqli_query($conexao,"INSERT INTO chat (login, nome, hash, ip, msg, data) VALUES ('".$dados_stm["login"]."', '".strip_tags($_SESSION["nome_usuario"])."', '".$_SESSION["hash_usuario"]."', '".$_SERVER["REMOTE_ADDR"]."', '".addslashes($msg)."', NOW())");
  }
exit();
}

if($_POST["nome"] && empty($_SESSION["hash_usuario"])) {
$_SESSION["nome_usuario"] = $_POST["nome"];
$_SESSION["hash_usuario"] = md5(time());
$_SESSION["last"] = 0;
}

if(query_string('2') == "limpar") {
mysqli_query($conexao,"DELETE FROM chat WHERE login = '".$dados_stm["login"]."' AND hash = '".$_SESSION["hash_usuario"]."'");
exit();
}

if(query_string('2') == "lista") {

$dados_ulm_msg = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM chat WHERE login = '".$dados_stm["login"]."' ORDER BY codigo DESC LIMIT 1"));

if(!isset($_SESSION["last"]) || $_SESSION["last"] == 0) {
  $_SESSION["last"] = $dados_ulm_msg["codigo"];
}

$query = mysqli_query($conexao,"SELECT * FROM chat WHERE login = '".$dados_stm["login"]."' AND codigo > ".$_SESSION["last"]." ORDER BY codigo ASC");
while ($lista = mysqli_fetch_array($query)) {

  $data = formatar_data_msg($dados_stm["formato_data"], $lista["data"], $dados_stm["timezone"]);

  $msg_usuario = ($lista["hash"] == $_SESSION["hash_usuario"]) ? 'style="background-color: #f7f5f5;padding: 5px;"' : '';
  
  echo '<li class="left clearfix" '.$msg_usuario.'><span class="chat-img pull-left"><i class="fa fa-commenting-o"></i> </span><div class="chat-body clearfix"><div class="header">&nbsp;&nbsp;<small><strong>'.$lista["nome"].'</strong></small><small class="pull-right text-muted text-right"><small>'.str_replace(" ", "<br><i class='fa fa-clock-o'></i> ", $data).'</small></small></div> <span class="message">'.strip_tags($lista["msg"]).'</span></div></li>';

  $_SESSION["last"] = $lista["codigo"];

}

exit();
}

if(query_string('2') == "online") {

// remove antigos
mysqli_query($conexao,"DELETE FROM chat_usuarios WHERE login = '".$dados_stm["login"]."' AND timestamp < (NOW() - INTERVAL 3600 SECOND)");

$verifica = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM chat_usuarios WHERE hash = '".$_SESSION["hash_usuario"]."' AND login = '".$dados_stm["login"]."'"));

if($verifica == 0 && isset($_SESSION["nome_usuario"])) {
  mysqli_query($conexao,"INSERT INTO chat_usuarios (login, nome, hash, ip) VALUES ('".$dados_stm["login"]."', '".$_SESSION["nome_usuario"]."', '".$_SESSION["hash_usuario"]."', '".$_SERVER["REMOTE_ADDR"]."')");
}

$query = mysqli_query($conexao,"SELECT * FROM chat_usuarios WHERE login = '".$dados_stm["login"]."'");
while ($lista = mysqli_fetch_array($query)) {
  
  if($lista["hash"] == $_SESSION["hash_usuario"]) {
    echo "<span class='message' style='color: SteelBlue'><i class='fa fa-user'></i> ".strip_tags($lista["nome"])."</span><br>";
  } else {
    echo "<span class='message'><i class='fa fa-user'></i> ".strip_tags($lista["nome"])."</span><br>";
  }
}
exit();
}

// Idioma chat
$array_lang = array(
  "pt-br" => array("btn_entrar" => "Entrar", "btn_enviar" => "Enviar!",  "btn_sair" => "Sair do Chat", "msg_nome" => "Digite seu nome...", "msg_msg" => "Digite sua mensagem..."),
  "en" => array("btn_entrar" => "Enter", "btn_enviar" => "Send!", "btn_sair" => "Logout",  "msg_nome" => "Type your name...", "msg_msg" => "Type your message..."),
  "es" => array("btn_entrar" => "Entrar", "btn_enviar" => "Enviar!", "btn_sair" => "Salir del Chat",   "msg_nome" => "Ingrese su nombre...", "msg_msg" => "Ingrese su mensaje...")
);
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">   
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript" charset="utf-8" src="//cdn.jsdelivr.net/npm/clappr@latest/dist/clappr.min.js"></script>
  <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials.min.js"></script>
  <script type="text/javascript" charset="utf-8" src="//cdn.jsdelivr.net/gh/clappr/clappr-level-selector-plugin@latest/dist/level-selector.min.js"></script>
  <link type="text/css" rel="stylesheet" href="//cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials.css" />
  <link type="text/css" rel="stylesheet" href="//cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials-theme-flat.css" />
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css" rel="stylesheet" crossorigin="anonymous" />
  <title>Player</title>
<style>
*{margin:0}html,body{height:100%;background-color:#cecece;overflow-x:hidden;overflow-y:hidden}#corpo{width:100%;height:100%;text-align:left}#player{width:100%;text-align:center;margin:0 auto}#chat{width:100%;height:200px;text-align:left;margin:0 auto}@media only screen and (min-width: 768px){#player{width:58%;height:100%;text-align:left;float:left}#chat{width:40%;height:100%;text-align:left;float:right}}#footer-emojis-share{width:100%;height:25px;text-align:left;margin-top:5px}#emojis{width:57%;height:25px;text-align:left;float:left}#emojis img{padding-right:5px;cursor:pointer}#shareIcons{width:43%;height:25px;text-align:right;float:right}.seek-disabled{display:none!important}.botao{background-color:#09C;border-color:#039;color:#FFF}.botao:hover{color:#CCC}.panel-success > .panel-heading{color:#FFF;background-color:#09C;border-radius:0}.panel{background-color:#fff;border-top:0;border-right:0;border-left:0;border-bottom:1px solid #DADADA;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(0,0,0,.05)}@media only screen and (min-width: 768px){.panel-success > .panel-heading{color:#FFF;background-color:#09C;border-color:#039}.panel{background-color:#fff;border-top:0;border-right:0;border-left:1px solid #DADADA;border-bottom:1px solid #DADADA;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(0,0,0,.05)}}.texto-padrao{color:#333;font-family:Geneva,Arial,Helvetica,sans-serif;font-size:12px;font-weight:400}.avatar{border-radius:50%;padding-right:5px}.chatpluginchat{list-style:none;margin:0;padding:0}.chatpluginchat li{margin-bottom:10px;padding-bottom:5px;border-bottom:1px dotted #B3A9A9}.chatpluginchat li.left .chat-body{margin-left:0}.chatpluginchat li.right .chat-body{margin-right:0}.chatpluginchat li .chat-body p{margin:0;color:#777}.jssocials-share-link{border-radius:50%;margin:0 5px 0 0 !important}.jssocials-share-logo{font-size:14px!important}.jssocials-shares{margin:0!important}.jssocials-share{margin:0!important}::-webkit-scrollbar-track{-webkit-box-shadow:inset 0 0 6px rgba(0,0,0,0.3);background-color:#F5F5F5}::-webkit-scrollbar{width:12px;background-color:#F5F5F5}::-webkit-scrollbar-thumb{-webkit-box-shadow:inset 0 0 6px rgba(0,0,0,.3);background-color:#555}.icone-contador{position:absolute;left:0;top:0;background:rgba(255,0,0, 1.0); min-width: 50px;height: 20px;padding-left: 5px;padding-bottom: 10px; margin: 10px; border-radius: 3px;color: #FFFFFF;font-size: 14px;text-align: center;z-index: 10000;}.icone-mudo{position:absolute;left:55px;top:0;background:rgba(255,165,0, 1.0); min-width: 50px;height: 20px;padding-left: 5px;padding-bottom: 10px; margin: 10px; border-radius: 3px;color: #FFFFFF;font-size: 14px;text-align: center;z-index: 10000;}.container {width:100%;padding: 0!important;}
.wrapper {
  -webkit-box-shadow: 0px 5px 30px 5px rgba(210,210,210,1);
  -moz-box-shadow: 0px 5px 30px 5px rgba(210,210,210,1);
  box-shadow: 0px 5px 30px 5px rgba(210,210,210,1);
}
#msg {
  height:400px;
  overflow-y:scroll; 
  list-style: none;
  padding-left: 10px;
  padding-right: 10px;
}
#online span {
  font-size:9pt;
  font-weight:bold;
}
#text {
  outline: none;
  border: 1px solid rgb(204, 204, 204);
  -webkit-box-shadow: none !important;
  -moz-box-shadow: none !important;
  box-shadow: none !important;
}
.message {
  display:block;

  padding-left: 10px;
  margin-bottom:10px;
}
.col-md-4, .col-md-8 {
  padding: 0;
  margin: 0 auto ;
}
@media screen and (min-width: 0px) and (max-width: 501px) {
html,body{overflow-y:scroll}
.col-md-4 {
  padding-left: 0px!important;
  padding-top: 2px!important;
}
}
small {
  font-size:12px;
}
small small {
  font-size:10px;
}
.blink {
  animation: blinker 2s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>
</head>
<body>
<?php if($ativar_contador == "sim") { ?><div class="icone-contador"><i class="fa fa-users"></i> <strong><span id="contador_online"></span></strong></div><?php } ?>
<div id="mudo" class="icone-mudo"><i class="fa fa-microphone-slash"></i> Mudo&nbsp;</div>
<div class="container">
<div class="col-md-8"><div id="player_webtv"></div></div>
<div class="col-md-4" id="quadro_msgs" style="padding-left: 5px;<?php if(!isset($_SESSION["hash_usuario"])) { echo "display: none;"; }?>">
        <div class="panel panel-default">
          <div class="panel-heading">
            <i class="fa fa-comments"></i> Chat
            <div class="dropdown pull-right">
            <button class="btn btn-xs btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
              <li class="droptitle message" style="cursor: pointer; padding-left: 10px;margin-bottom: 0px;" onClick="window.location = '<?php echo $_SERVER['REQUEST_URI'];?>/sair'"><i class="fa fa-sign-out"></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["btn_sair"]; ?></li>
            </ul>
          </div>
          </div>
          <div class="panel-body" style="padding: 0px">
            <ul id="msg"></ul>
          </div>
          
          <div class="panel-footer">
            <form id="sendform" name="sendform" method="POST" class="input-group">
              <input id="text" type="text" name="text" autocomplete="off" class="form-control" placeholder="<?php echo $array_lang[$dados_stm["idioma_painel"]]["msg_msg"]; ?>" value="" />
              <span class="input-group-btn">
                <input id="send" type="submit" value="<?php echo $array_lang[$dados_stm["idioma_painel"]]["btn_enviar"]; ?>" class="btn btn-default" />
              </span>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-4" id="quadro_login" style="padding-left: 5px;<?php if(isset($_SESSION["hash_usuario"])) { echo "display: none;"; }?>">
        <div class="panel panel-default">
          <div class="panel-heading">
            <i class="fa fa-comments"></i> Chat
          </div>
          
          <div class="panel-footer">
            <form method="POST" class="input-group">
              <input id="nome" type="text" name="nome" autocomplete="off" class="form-control" placeholder="<?php echo $array_lang[$dados_stm["idioma_painel"]]["msg_nome"]; ?>" value="" />
              <span class="input-group-btn">
                <input id="send" type="submit" value="<?php echo $array_lang[$dados_stm["idioma_painel"]]["btn_entrar"]; ?>" class="btn btn-default" />
              </span>
            </form>
          </div>
        </div>
      </div>
</div>
<div id="online" style="display:none"></div> 
<script type="text/javascript">
jQuery(document).ready(function() {
var largura_tela = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
var altura_tela = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

if(largura_tela > 768){var altura_player=altura_tela;}else{var altura_player="300px";}

var player = new Clappr.Player({
  <?php if($dados_stm["transcoder_instalado"] == "sim") { ?>
  plugins: [LevelSelector],
  levelSelectorConfig: {
      labelCallback: function(playbackLevel, customLabel) {
          return playbackLevel.level.height+'p'; // High 720p
      }
  },
  <?php } ?>source: '<?php echo $url_source; ?>',parentId: '#player_webtv',width: '100%',height: altura_player,hideMediaControl: true,poster: '<?php echo $capa; ?>',<?php if($dados_stm["watermark_posicao"]) { ?>position: '<?php echo $clappr_watermark; ?> ',watermark: 'https://<?php echo $servidor;?>:1443/watermark.php?login=<?php echo $login;?>',<?php } ?>autoPlay: <?php echo $autoplay; ?>,mute: <?php echo $mudo; ?>});
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

var video = $('video');

video.on('volumechange', (e) => {
  if ($('video').prop('muted')) {
    $( "#mudo" ).show();
    $( "#mudo" ).toggleClass( "blink" );
  } else {
    $( "#mudo" ).hide();
    $( "#mudo" ).toggleClass( "blink" );
  }
});
/*
$('#mudo').on('click', function(evt) {
  if ($('video').prop('muted')) {
    $( "#mudo" ).hide();
    $("video").prop('muted', false);
  } else {
    $( "#mudo" ).show();
    $("video").prop('muted', true);
  }
});
*/
//////////////////////////////////////////////////////////////////
////////////////////////////    Chat    ////////////////////////// 
//////////////////////////////////////////////////////////////////
var bottom = true;
 
  $("#msg").height(altura_tela-110);
  
  $("#text").focus();
  
  listChat();
  setInterval(function() {
    listChat();
  }, 2000);
  
 
  $( "#sendform" ).submit(function(e) {
    if($('#text').val() != "") {
      $.post("/video-chat/<?php echo $dados_stm["login"];?>/lista", {text: $('#text').val()});
      $('#text').val("");
      $('.emojionearea-editor').html("");
    }
    e.preventDefault();
  });
  
  $("#msg").scroll(function() {
    if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
      bottom = true;
      //console.log("bottom");
    }
    else {
      bottom = false;
      //console.log("not bottom");
    }
  });
  
  $("#clickClear").click(function(e) {
    $.post("/video-chat/<?php echo $dados_stm["login"];?>/limpar");
    $("#msg").text("Cleared chat");
  });
});

function listChat() 
{
  $.get("/video-chat/<?php echo $dados_stm["login"];?>/lista", function (data) {
    $("#msg").append(data);
    if(window.bottom) {
      //console.log("scrolled down");
      $('#msg').scrollTop($('#msg')[0].scrollHeight);
    }
  });
  //console.log("Updated chat");
}

function listOnline()
{
  $("#online").load("/video-chat/<?php echo $dados_stm["login"];?>/online");
  //console.log("Updated online");
}
$("#text").emojioneArea({
  pickerPosition: "top",
  tonesStyle: "bullet"
});
</script>
</body>
</html>