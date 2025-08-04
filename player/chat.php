<?php
session_start();
if(query_string('2') == "") {
unset($_SESSION["last"]);
}

@mysqli_query($conexao,"CREATE TABLE IF NOT EXISTS `chat` ( `codigo` INT(10) NOT NULL AUTO_INCREMENT , `login` VARCHAR(255) NOT NULL , `nome` VARCHAR(255) NOT NULL , `hash` VARCHAR(255) NOT NULL , `ip` VARCHAR(255) NOT NULL , `msg` LONGTEXT NOT NULL , `data` DATETIME NOT NULL , PRIMARY KEY (`codigo`)) ENGINE = MyISAM;");
@mysqli_query($conexao,"CREATE TABLE IF NOT EXISTS `chat_usuarios` ( `codigo` INT NOT NULL AUTO_INCREMENT , `login` VARCHAR(255) NOT NULL , `nome` VARCHAR(255) NOT NULL , `hash` VARCHAR(255) NOT NULL , `ip` VARCHAR(255) NOT NULL , `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`codigo`)) ENGINE = MyISAM;");

function formatar_data_msg($formato,$data,$timezone){$formato=(preg_match('/:/i',$data))?$formato:str_replace("H:i:s","",$formato);$offset=get_tz_offset('America/Sao_Paulo',$timezone);if(preg_match('/:/i',$data)){$nova_data=strtotime(''.$offset.' hour',strtotime($data));$nova_data=date($formato,$nova_data);}else{$nova_data=date($formato,strtotime($data));}return $nova_data;}

function get_tz_offset($remote_tz, $origin_tz = null) { if($origin_tz === null) { if(!is_string($origin_tz = date_default_timezone_get())) { return false; } } $origin_dtz = new DateTimeZone($origin_tz); $remote_dtz = new DateTimeZone($remote_tz); $origin_dt = new DateTime("now", $origin_dtz); $remote_dt = new DateTime("now", $remote_dtz); $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt); $offset = $offset/3600; return $offset; }

$login = query_string('1');

$dados_config = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$login."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));
$dados_servidor_aacplus = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor_aacplus"]."'"));

if(query_string('2') == "sair") {
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

$total_msgs = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM chat WHERE login = '".$dados_stm["login"]."'"));

if(!isset($_SESSION["last"])) {
	$_SESSION["last"] = 0;
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

$array_lang = array(
	"pt-br" => array("btn_entrar" => "Entrar", "btn_enviar" => "Enviar!",  "btn_sair" => "Sair do Chat", "msg_nome" => "Digite seu nome...", "msg_msg" => "Digite sua mensagem..."),
	"en" => array("btn_entrar" => "Enter", "btn_enviar" => "Send!", "btn_sair" => "Logout",  "msg_nome" => "Type your name...", "msg_msg" => "Type your message..."),
	"es" => array("btn_entrar" => "Entrar", "btn_enviar" => "Enviar!", "btn_sair" => "Salir del Chat",   "msg_nome" => "Ingrese su nombre...", "msg_msg" => "Ingrese su mensaje...")
);

?>
<!doctype html>
<!-- 
 * Chat VoxPanel
 * @author Cesar Fernandes
 * @date 15-12-2020
 * @contact cesarlwh@gmail.com
 * @Copyright: Se voce comprou este modulo com outra pessoa que nao tenha sido o informado acima, esta correndo serios riscos de seguranca e expondo seus dados.
-->
<html lang="en">
	<head>
		<title>Chat</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css" rel="stylesheet" crossorigin="anonymous" />
		<style type="text/css">
body {
	background: rgb(240,240,240);
}
.container {
	width:100%;
	padding: 0!imloginnt;
}
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
	-webkit-box-shadow: none !imloginnt;
	-moz-box-shadow: none !imloginnt;
	box-shadow: none !imloginnt;
}
.message {
	display:block;
	padding-left: 10px;
	margin-bottom:10px;
}
.col-md-3, .col-md-9 {
	padding-left: 5px;
	padding-right: 5px;
}
@media screen and (min-width: 0px) and (max-width: 501px) {
	#online_quadro {
		display: none;
	}
}
small {
	font-size:12px;
}
small small {
	font-size:10px;
}
</style>
	</head>
	
	<body>
		<div class="container">
			<div class="col-md-3" id="online_quadro">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-users"></i> Online
					</div>
					<div id="online" class="panel-body"></div>				
				</div>
			</div>

			<div class="col-md-9" id="quadro_msgs" style="<?php if(!isset($_SESSION["hash_usuario"])) { echo "display: none;"; }?>">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-comments"></i> Chat
						<div class="dropdown pull-right">
						<button class="btn btn-xs btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
							<li class="droptitle message" style="cursor: pointer; padding-left: 10px;margin-bottom: 0px;" onClick="window.location = '/<?php echo query_string('0');?>/<?php echo $dados_stm["login"];?>/sair'"><i class="fa fa-sign-out"></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["btn_sair"]; ?></li>
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

			<div class="col-md-9" id="quadro_login" style="<?php if(isset($_SESSION["hash_usuario"])) { echo "display: none;"; }?>">
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
		<script>
var bottom = true;

jQuery(document).ready(function() {
	
	$("#text").focus();
	
	listChat();
	setInterval(function() {
		listChat();
	}, 2000);
	
	listOnline();
	setInterval(function() {
		listOnline();
	}, 10000);
	
	$( "#sendform" ).submit(function(e) {
		if($('#text').val() != "") {
			$.post("/<?php echo query_string('0');?>/<?php echo $dados_stm["login"];?>", {text: $('#text').val()});
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
		$.post("/<?php echo query_string('0');?>/<?php echo $dados_stm["login"];?>/limpar");
		$("#msg").text("Cleared chat");
	});
});

function listChat() 
{
	$.get("/<?php echo query_string('0');?>/<?php echo $dados_stm["login"];?>/lista", function (data) {
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
	$("#online").load("/<?php echo query_string('0');?>/<?php echo $dados_stm["login"];?>/online");
	//console.log("Updated online");
}
$(document).ready(function() {
	$("#text").emojioneArea({
  		pickerPosition: "top",
    	tonesStyle: "bullet"
  	});
});
		</script>		
	</body>
</html>