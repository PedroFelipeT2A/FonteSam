<?php
require_once("admin/inc/protecao-final.php");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));
$dados_revenda = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM revendas WHERE codigo = '".$dados_stm["codigo_cliente"]."'"));
$dados_playlist = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM playlists where codigo = '".$dados_stm["ultima_playlist"]."'"));
$total_playlists = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM playlists where codigo_stm = '".$dados_stm["codigo"]."'"));
$total_agendamentos = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM playlists_agendamentos where codigo_stm = '".$dados_stm["codigo"]."'"));

$limite_espectadores = ($dados_stm["espectadores"] == 999999) ? '<span class="texto_ilimitado">'.$lang['lang_info_ilimitado'].'</span>' : $dados_stm["espectadores"];

$login_code = code_decode($dados_stm["login"],"E");

if($dados_servidor["nome_principal"]) {
$servidor = $dados_servidor["nome_principal"].".".$dados_config["dominio_padrao"];
$url_source_http = "https://".$dados_servidor["nome_principal"].".".$dados_config["dominio_padrao"]."/".$dados_stm["login"]."/".$dados_stm["login"]."/playlist.m3u8";
} else {
$servidor = $dados_servidor["nome"].".".$dados_config["dominio_padrao"];
$url_source_http = "https://".$dados_servidor["nome"].".".$dados_config["dominio_padrao"]."/".$dados_stm["login"]."/".$dados_stm["login"]."/playlist.m3u8";
}

if(query_string('1') == "checar-chave") {

$webrtc_chave = query_string('2');

if($dados_stm["webrtc_chave"] != $webrtc_chave || empty($webrtc_chave)){
echo "reload";
exit();
}

exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="cache-control" content="no-cache">
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
<link href="inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<link href="//vjs.zencdn.net/6.0.0/video-js.css" rel="stylesheet">
<script type="text/javascript" src="/inc/ajax-streaming.js"></script>
<script type="text/javascript" src="/inc/ajax-modulo-diagnosticar.js"></script>
<script type="text/javascript" src="/inc/ajax-modulo-pular-video.js"></script>
<script type="text/javascript" src="/inc/javascript.js"></script>
<script type="text/javascript" src="/inc/sorttable.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="/player/inc-webrtc/adapter-latest.js"></script>
<link  href="/player/inc-webrtc/plyr.css" rel="stylesheet" />
<script src="/player/inc-webrtc/plyr.polyfilled.js"></script>
<style type="text/css">#play-video-container{display:flex;justify-content:center;align-items:center;}#play-video-container, #player-video { height: 322px; width: 430px;}.plyr{display: none;} .plyr__time, .plyr__progress, .plyr__control--overlaid{display: none!important;}</style>
<script type="text/javascript">
   window.onload = function() {
	fechar_log_sistema();
	// Status de exibi  o dos quadros
	document.getElementById('tabela_info_stm').style.display=getCookie('tabela_info_stm');
	document.getElementById('tabela_info_plano').style.display=getCookie('tabela_info_plano');
	<?php if($dados_stm["aplicacao"] == 'live' || $dados_stm["aplicacao"] == 'tvstation') { ?>
	document.getElementById('tabela_player').style.display=getCookie('tabela_player');
	<?php } ?>
	document.getElementById('tabela_gerenciamento_streaming').style.display=getCookie('tabela_gerenciamento_streaming');
	document.getElementById('tabela_gerenciamento_ondemand').style.display=getCookie('tabela_gerenciamento_ondemand');
	document.getElementById('tabela_painel').style.display=getCookie('tabela_painel');
  <?php if($dados_stm["aplicacao"] == 'webrtc') { ?>
  setInterval (checar_chave,15000);
  <?php } ?>
   };
   window.onkeydown = function (event) {
		if (event.keyCode == 27) {
			document.getElementById('log-sistema-fundo').style.display = 'none';
			document.getElementById('log-sistema').style.display = 'none';
		}
	}
  <?php if($dados_stm["aplicacao"] == 'webrtc') { ?>
  function checar_chave(){
    $.ajax({
    url: "/informacoes/checar-chave/<?php echo $dados_stm["webrtc_chave"]; ?>",
    success:
      function(resposta){
        if(resposta == "reload") {
          window.location.reload();
        }
      }
    })
}
<?php } ?>
// Fun  o para reiniciar o SRT do streaming
function reiniciar_srt( login ) {

  if(login == "") {
  alert("Error!\n\nPortugu s: Dados faltando, tente novamente ou contate o suporte.\n\nEnglish: Missing data try again or contact support.\n\nEspa ol: Los datos que faltaban int ntelo de nuevo o contacte con Atenci n.");
  } else {
  
  document.getElementById("log-sistema-conteudo").innerHTML = "<img src='/img/ajax-loader.gif' />";
  document.getElementById('log-sistema-fundo').style.display = "block";
  document.getElementById('log-sistema').style.display = "block";
  
  var http = new Ajax();
  http.open("GET", "/funcoes-ajax/reiniciar_srt/"+login , true);
  http.onreadystatechange = function() {
	
  if(http.readyState == 4) {
  
	resultado = http.responseText;

	document.getElementById("log-sistema-conteudo").innerHTML = resultado;
	document.getElementById("log-sistema-conteudo").style.fontSize = "25px";
	
  }
  
  }
  http.send(null);
  delete http;
  }
}
</script>
</head>

<body>
<div id="sub-conteudo">
<?php if($dados_servidor["status"] == "on") { ?>
<?php
if($_SESSION['status_acao']) {

$status_acao = stripslashes($_SESSION['status_acao']);

echo '<table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px">'.$status_acao.'</table>';

unset($_SESSION['status_acao']);
}
?>
<?php 
$total_dicas_rapidas = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM dicas_rapidas where exibir = 'sim'"));

if($total_dicas_rapidas > 0) {

$dados_dica_rapida = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM dicas_rapidas where exibir = 'sim' ORDER BY RAND() LIMIT 1"));

$dados_dicas_rapidas_acesso = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM dicas_rapidas_acessos where codigo_stm = '".$dados_stm["codigo"]."' AND codigo_dica = '".$dados_dica_rapida["codigo"]."'"));

if($dados_dicas_rapidas_acesso["total"] < 10) {

if($dados_dicas_rapidas_acesso["total"] == 0) {
mysqli_query($conexao,"INSERT INTO dicas_rapidas_acessos (codigo_stm,codigo_dica,total) VALUES (".$dados_stm["codigo"].",'".$dados_dica_rapida["codigo"]."','1')");
} else {
mysqli_query($conexao,"Update dicas_rapidas_acessos set total = total+1 where codigo = '".$dados_dicas_rapidas_acesso["codigo"]."'");
}

$dica_rapida = str_replace("PAINEL","http://".$_SERVER['HTTP_HOST']."",$dados_dica_rapida["mensagem"]);
$dica_rapida = str_replace("LOGIN","".$dados_stm["login"]."",$dica_rapida);
?>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px; margin-bottom:10px; margin-left:0 auto; margin-right:0 auto; background-color: #C1E0FF; border: #006699 1px solid">
<tr>
            <td width="30" height="25" align="center" scope="col"><img src="img/icones/ajuda.gif" width="16" height="16" /></td>
            <td width="870" align="left" class="texto_padrao_destaque" scope="col"><?php echo $dica_rapida; ?></td>
    </tr>
</table>
<?php
}
}
?>
<?php if($dados_stm["status"] == 1) { ?>
<?php if(carregar_avisos_streaming($conexao,$dados_stm["login"],$dados_servidor["codigo"])) { ?>
<table width="900" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
      <td width="885" height="50" align="center" valign="top">
      <div id="quadro">
            	<div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_avisos');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong><?php echo $lang['lang_info_pagina_informacoes_tab_avisos']; ?></strong></div>
            		<div class="texto_medio" id="quadro-conteudo">
            		  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="display:block" id="tabela_avisos">
                        <tr>
                          <td height="25" class="texto_padrao">
						  <?php
							echo carregar_avisos_streaming($conexao,$dados_stm["login"],$dados_servidor["codigo"]);
						  ?>
                          </td>
                        </tr>
                      </table>
            		</div>
      </div>      </td>
    </tr>
  </table>
<?php } ?>
<?php if($dados_stm["aplicacao"] == 'live' || $dados_stm["aplicacao"] == 'tvstation') { ?>
  <table width="900" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
      <td width="350" align="center" valign="top" style="padding-right:5px"><div id="quadro">
          <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_info_stm');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong><?php echo $lang['lang_info_pagina_informacoes_tab_streaming']; ?></strong></div>
        <div class="texto_medio" id="quadro-conteudo">
            <table width="335" border="0" cellpadding="0" cellspacing="0" style="display:block" id="tabela_info_stm">
              <tr>
                <td width="167" height="25" align="center" bgcolor="#F8F8F8" class="texto_padrao_destaque"><?php echo $lang['lang_info_login']; ?>&nbsp;</td>
                <td width="167" align="center" bgcolor="#F8F8F8" class="texto_padrao_destaque"><?php echo $lang['lang_info_ip_conexao']; ?></td>
              </tr>
              <tr>
                <td height="40" align="center" bgcolor="#F8F8F8" class="texto_padrao"><?php echo $dados_stm["login"]; ?></td>
                <td height="40" align="center" bgcolor="#F8F8F8" class="texto_padrao"><?php echo ucfirst($dados_servidor["nome"]); ?></td>
              </tr>
              <tr>
                <td height="25" align="center" bgcolor="#F8F8F8" class="texto_padrao_destaque"><?php echo $lang['lang_info_espectadores']; ?></td>
                <td align="center" bgcolor="#F8F8F8" class="texto_padrao_destaque"><?php echo $lang['lang_info_bitrate']; ?></td>
              </tr>
              <tr>
                <td height="40" align="center" bgcolor="#F8F8F8" class="texto_padrao"><?php echo $limite_espectadores; ?></td>
                <td height="40" align="center" bgcolor="#F8F8F8" class="texto_padrao"><?php echo $dados_stm["bitrate"]; ?> Kbps</td>
              </tr>
              <?php if($dados_stm["aplicacao"] == 'tvstation') { ?>
              <tr>
                <td height="25" align="center" bgcolor="#F8F8F8" class="texto_padrao_destaque"><?php echo $lang['lang_info_espaco_ftp']; ?></td>
                <td align="center" bgcolor="#F8F8F8" class="texto_padrao_destaque">&nbsp;</td>
              </tr>
              <tr>
                <td height="40" align="center" bgcolor="#F8F8F8" class="texto_padrao"><?php echo tamanho($dados_stm["espaco"]); ?></td>
                <td height="40" align="center" bgcolor="#F8F8F8" class="texto_padrao">&nbsp;</td>
              </tr> 
              <?php } ?> 
              <?php if($dados_stm["aplicacao"] == 'live') { ?>
              <tr>
                <td height="25" align="center" bgcolor="#F8F8F8" class="texto_padrao_destaque">&nbsp;</td>
                <td align="center" bgcolor="#F8F8F8" class="texto_padrao_destaque">&nbsp;</td>
              </tr>
              <tr>
                <td height="40" align="center" bgcolor="#F8F8F8" class="texto_padrao">&nbsp;</td>
                <td height="40" align="center" bgcolor="#F8F8F8" class="texto_padrao">&nbsp;</td>
              </tr>
              <?php } ?>
              <tr>
                <td height="105" align="center" bgcolor="#F8F8F8" class="texto_padrao">&nbsp;</td>
                <td height="105" align="center" bgcolor="#F8F8F8" class="texto_padrao">&nbsp;</td>
              </tr>
            </table>
        </div>
      </div></td>
      <td width="550" rowspan="2" align="center" valign="top" style="padding-left:5px"><div id="quadro">
          <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_player');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong>Player</strong></div>
        <div class="texto_medio" id="quadro-conteudo">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="display:block;" id="tabela_player">
                  <tr>
                    <td align="center" style="height:300px">                
                        <script src="https://content.jwplatform.com/libraries/5PLwmcI5.js"></script>
                        <div id="video_main"></div>
                        <script>
			var player = jwplayer("video_main");
			player.setup({
                aboutlink: "",
                abouttext: "",
                aspectratio: '16:9',
                width: '535',
                height: '300',
                displaytitle: false,
                displaydescription: false,
                logo: {
                        hide: true,
                },
                        sharing: {},
                sources: [{"file":"<?php echo $url_source_http; ?>"}]
        });
</script>
                     </td>
                   </tr>
                 </table>
        </div>
      </div></td>
    </tr>
  </table>
  <?php } ?>
  <?php if($dados_stm["aplicacao"] == 'vod') { ?>
  <table width="900" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
      <td width="450" align="center" valign="top" style="padding-right:5px"><div id="quadro">
          <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_info_stm');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong><?php echo $lang['lang_info_pagina_informacoes_tab_streaming']; ?></strong></div>
        <div class="texto_medio" id="quadro-conteudo">
            <table width="430" border="0" cellpadding="0" cellspacing="0" style="display:block" id="tabela_info_stm">
              <tr>
                <td width="100" height="25" align="left" bgcolor="#F8F8F8" class="texto_padrao_destaque">&nbsp;<?php echo $lang['lang_info_login']; ?></td>
                <td width="330" align="left" bgcolor="#F8F8F8" class="texto_padrao"><?php echo $dados_stm["login"]; ?></td>
              </tr>
              <tr>
                <td height="25" align="left" bgcolor="#F8F8F8" class="texto_padrao_destaque">&nbsp;<?php echo $lang['lang_info_ip_conexao']; ?></td>
                <td align="left" bgcolor="#F8F8F8" class="texto_padrao_pequeno"><?php echo ucfirst($dados_servidor["nome"]); ?></td>
              </tr>
              <tr>
                <td height="25" align="left" bgcolor="#F8F8F8" class="texto_padrao_destaque">&nbsp;</td>
                <td align="left" bgcolor="#F8F8F8" class="texto_padrao_pequeno">&nbsp;</td>
              </tr>
            </table>
        </div>
      </div></td>
      <td width="450" align="center" valign="top" style="padding-left:5px"><div id="quadro">
          <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_info_plano');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong><?php echo $lang['lang_info_pagina_informacoes_tab_plano']; ?></strong></div>
        <div class="texto_medio" id="quadro-conteudo">
            <table width="430" border="0" cellpadding="0" cellspacing="0" style="display:block" id="tabela_info_plano">
              <tr>
                <td width="100" height="25" align="left" bgcolor="#F8F8F8" class="texto_padrao_destaque">&nbsp;<?php echo $lang['lang_info_espectadores']; ?></td>
                <td width="330" align="left" bgcolor="#F8F8F8" class="texto_padrao"><?php echo $limite_espectadores; ?></td>
              </tr>
              <?php if($dados_stm["aplicacao"] == 'tvstation' || $dados_stm["aplicacao"] == 'vod') { ?>
              <tr>
                <td height="25" align="left" bgcolor="#F8F8F8" class="texto_padrao_destaque">&nbsp;<?php echo $lang['lang_info_espaco_ftp']; ?></td>
                <td align="left" bgcolor="#F8F8F8" class="texto_padrao"><?php echo tamanho($dados_stm["espaco"]); ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td height="25" align="left" bgcolor="#F8F8F8" class="texto_padrao_destaque">&nbsp;<?php echo $lang['lang_info_bitrate']; ?></td>
                <td align="left" bgcolor="#F8F8F8" class="texto_padrao"><?php echo $dados_stm["bitrate"]; ?> Kbps</td>
              </tr>
            </table>
        </div>
      </div></td>
    </tr>
  </table>
  <?php } ?>
  <?php if($dados_stm["aplicacao"] == 'webrtc') { ?>
  <table width="900" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
      <td width="450" align="center" valign="top" style="padding-right:5px"><div id="quadro">
          <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_info_stm');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong><?php echo $lang['lang_info_pagina_informacoes_tab_streaming']; ?></strong></div>
        <div class="texto_medio" id="quadro-conteudo">
          <table width="438" border="0" cellpadding="0" cellspacing="0" style="display:block" id="tabela_info_stm">
            <tr>
              <td width="219" height="25" align="center" bgcolor="#F8F8F8" class="texto_padrao_destaque"><?php echo $lang['lang_info_login']; ?>&nbsp;</td>
              <td width="219" align="center" bgcolor="#F8F8F8" class="texto_padrao_destaque"><?php echo $lang['lang_info_ip_conexao']; ?></td>
            </tr>
            <tr>
              <td width="219" height="35" align="center" bgcolor="#F8F8F8" class="texto_padrao"><?php echo $dados_stm["login"]; ?></td>
              <td width="219" height="35" align="center" bgcolor="#F8F8F8" class="texto_padrao"><?php echo ucfirst($dados_servidor["nome"]); ?></td>
            </tr>
            <tr>
              <td width="219" height="25" align="center" bgcolor="#F8F8F8" class="texto_padrao_destaque"><?php echo $lang['lang_info_espectadores']; ?></td>
              <td width="219" align="center" bgcolor="#F8F8F8" class="texto_padrao_destaque"><?php echo $lang['lang_info_bitrate']; ?></td>
            </tr>
            <tr>
              <td width="219" height="35" align="center" bgcolor="#F8F8F8" class="texto_padrao"><?php echo $limite_espectadores; ?></td>
              <td width="219" height="35" align="center" bgcolor="#F8F8F8" class="texto_padrao"><?php echo $dados_stm["bitrate"]; ?> Kbps</td>
            </tr>
          </table>
        </div>
      </div></td>
      <td width="450" rowspan="3" align="center" valign="top" style="padding-left:5px">
      <div id="quadro">
          <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_player');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong>Player</strong></div>
        <div class="texto_medio" id="quadro-conteudo">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="display:block;" id="tabela_player">
              <tr>
                <td align="center" style="height:322px;background-color: #000000;">
                <div id="play-video-container">
  <button id="player-btn" type="button" class="btn" style="z-index: 1000;background: transparent; border: none;cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill:#ffffff; width: 72px; height: 72px; "><path d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM188.3 147.1c-7.6 4.2-12.3 12.3-12.3 20.9V344c0 8.7 4.7 16.7 12.3 20.9s16.8 4.1 24.3-.5l144-88c7.1-4.4 11.5-12.1 11.5-20.5s-4.4-16.1-11.5-20.5l-144-88c-7.4-4.5-16.7-4.7-24.3-.5z"/></svg></button>
<div class="alert alert-danger text-center" id="error-panel" style="display: none;color:#fff900;font-size: 14px; margin: 50px;"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill:#fff900; width: 32px; height: 32px; "><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg><br>Transmiss&atilde;o n&atilde;o dispon&iacute;vel no momento, tente novamente mais tarde.</div>
</div>
<video id="player-video" playsinline controls></video>
<script type="text/javascript" charset="utf-8">
let config_stm = {
    playSdpURL: "wss://<?php echo $servidor; ?>/webrtc-session.json",
    playApplicationName: "<?php echo $dados_stm["login"]; ?>",
    playStreamName: "<?php echo $dados_stm["webrtc_chave"]; ?>"
};
const player = new Plyr("#player-video", {disableContextMenu: true});
</script>
<script type="module" crossorigin="use-credentials" src="/player/inc-webrtc/play.js"></script>
                </td>
              </tr>
            </table>
        </div>
      </div>      </td>
    </tr>
    <tr>
      <td align="center" style="height:10px"></td>
    </tr>
    <tr>
      <td align="center" valign="top" style="padding-right:5px"><div id="quadro">
        <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_gerenciamento_streaming');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong><?php echo $lang['lang_info_pagina_informacoes_tab_gerenciamento_streaming']; ?></strong></div>
        <div class="texto_medio" id="quadro-conteudo2">
          <table width="430" border="0" align="center" cellpadding="0" cellspacing="0" style="display:block" id="tabela_gerenciamento_streaming2">
            <tr>
              <td width="143" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="window.open('/studio-web/<?php echo code_decode($dados_stm["login"],"E");?>');"><img src="img/icones/img-icone-studio-web.png" title="Studio Web" width="48" height="48" /> <br />
                Studio Web&nbsp;</td>
              <td width="143" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/configuracoes-streaming','conteudo');"><img src="img/icones/img-icone-configuracoes.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_config']; ?>" width="48" height="48" /> <br />
                  <?php echo $lang['lang_info_pagina_informacoes_tab_menu_config']; ?>&nbsp;</td>
              <td width="143" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="carregar_players();"><img src="img/icones/img-icone-players.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_players']; ?>" width="48" height="48" /> <br />
                  <?php echo $lang['lang_info_pagina_informacoes_tab_menu_players']; ?></td>
            </tr>
            <tr>
              <td height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/espectadores-conectados','conteudo');"><img src="img/icones/img-icone-espectadores.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_espectadores_conectados']; ?>" width="48" height="48" /> <br />
                  <?php echo $lang['lang_info_pagina_informacoes_tab_menu_espectadores_conectados']; ?></td>
              <td height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="carregar_estatisticas_streaming('<?php echo $login_code;?>');"><img src="img/icones/img-icone-estatistica.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_stats']; ?>" width="48" height="48" /> <br />
                  <?php echo $lang['lang_info_pagina_informacoes_tab_menu_stats']; ?></td>
              <td height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/app-android-webrtc','conteudo');"><img src="img/icones/img-icone-app-android-webrtc.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_app_android']; ?>" width="48" height="48" /> <br />
                  <?php echo $lang['lang_info_pagina_informacoes_tab_menu_app_android']; ?>&nbsp;</td>
            </tr>
          </table>
        </div>
      </div>      </td>
    </tr>
  </table>
  <?php } ?>
  <?php if($dados_stm["aplicacao"] == 'tvstation') { ?>
  <table width="885" border="0" cellpadding="0" cellspacing="0" align="center" style="margin-top:10px">
    <tr>
      <td width="885" align="center" valign="top">
      <table width="900" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="450" align="center" valign="top" style="padding-right:5px">
          <div id="quadro2">
            <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_gerenciamento_streaming');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong><?php echo $lang['lang_info_pagina_informacoes_tab_gerenciamento_streaming']; ?></strong></div>
            <div class="texto_medio" id="quadro-conteudo">
                <table width="430" border="0" align="center" cellpadding="0" cellspacing="0" style="display:block" id="tabela_gerenciamento_streaming">
                  <tr>
                    <td width="143" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/dados-conexao','conteudo');"><img src="img/icones/img-icone-dados-conexao.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_dados_conexao']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_dados_conexao']; ?>&nbsp;</td>
                    <td width="143" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/configuracoes-streaming','conteudo');"><img src="img/icones/img-icone-configuracoes.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_config']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_config']; ?>&nbsp;</td>
                    <td width="143" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="carregar_players();"><img src="img/icones/img-icone-players.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_players']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_players']; ?></td>
                  </tr>
                  <tr>
                    <td width="143" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/espectadores-conectados','conteudo');"><img src="img/icones/img-icone-espectadores.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_espectadores_conectados']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_espectadores_conectados']; ?></td>
                    <td width="143" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="carregar_estatisticas_streaming('<?php echo $login_code;?>');"><img src="img/icones/img-icone-estatistica.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_stats']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_stats']; ?></td>
                    <td width="143" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_janela('/gravador',410,480);"><img src="img/icones/img-icone-rec.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_gravar_transmissao']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_gravar_transmissao']; ?></td>
                  </tr>
                  <tr>
                    
                    <td height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/gerenciar-geoip','conteudo');"><img src="img/icones/img-icone-geoip.png" width="48" height="48" /> <br />Restri&ccedil;&atilde;o GeoIP&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                    <td width="143" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/app-wowza-gocoder','conteudo');"><img src="img/icones/img-icone-wowza-gocoder.png" title="App Wowza GoCoder" width="48" height="48" /> <br />
                        Fazer ao vivo usando Smartphone&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                    <td height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="pular_video('<?php echo $login_code;?>');"><img src="img/icones/img-icone-pular-video.png" title="Pular Video" width="48" height="48" /><br />Pular Video&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                    </tr>
                  <tr>
                    <?php if($dados_stm["exibir_app_android"] == 'sim') { ?>
                    <td width="143" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/app-android-web','conteudo');"><img src="img/icones/img-icone-app-android-web.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_app_android']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_app_android']; ?> Web&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                    <?php } ?>
                    <td height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/app-windows','conteudo');"><img src="img/icones/img-icone-app-win.png" width="48" height="48" /> <br />App Windows&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                    <td height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/app-multi-plataforma','conteudo');"><img src="img/icones/img-icone-app-multi-plataforma.png" width="48" height="48" /> <br />App Multi Plataforma&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                  </tr>
                  <tr>
                    
                       <?php if($dados_stm["srt_status"] == 'sim') { ?>
                    <td width="143" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="reiniciar_srt('<?php echo $login_code;?>');"><img src="img/icones/img-icone-restart-srt.png" title="Reiniciar SRT" width="48" height="48" /> <br />
                        Reiniciar SRT</td>
                    <?php } ?>
                    <td height="80" align="center">&nbsp;</td>
                  </tr>
                </table>
            </div>
          </div></td>
          <td width="450" align="center" valign="top" style="padding-left:5px">
          <div id="quadro">
            <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_gerenciamento_ondemand');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong><?php echo $lang['lang_info_pagina_informacoes_tab_gerenciamento_ondemand']; ?></strong></div>
            <div class="texto_medio" id="quadro-conteudo">
                <table width="437" border="0" align="center" cellpadding="0" cellspacing="0" style="display:block" id="tabela_gerenciamento_ondemand">
                  <tr>
                    <td width="143" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="menu_iniciar_playlist();"><img src="img/icones/img-icone-iniciar-playlist.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_iniciar_playlist']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_iniciar_playlist']; ?></td>
                    <td width="143" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/gerenciar-videos','conteudo');"><img src="img/icones/img-icone-gerenciador-videos.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_gerenciar_videos']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_gerenciar_videos']; ?>&nbsp;</td>
                    <td width="143" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/playlists','conteudo');"><img src="img/icones/img-icone-playlists.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_gerenciar_playlists']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_gerenciar_playlists']; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="143" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/gerenciar-agendamentos','conteudo');"><img src="img/icones/img-icone-agendamento.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_gerenciar_agendamentos']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_gerenciar_agendamentos']; ?></td>
                    <td width="143" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/gerenciar-playlists-comerciais','conteudo');"><img src="img/icones/img-icone-comerciais.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_comerciais']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_comerciais']; ?></td>
                    <td width="143" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/utilitario-renomear-videos','conteudo');"><img src="img/icones/img-icone-ferramenta-renomear-64x64.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_renomear_videos']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_renomear_videos']; ?></td>
                  </tr>
                  <tr>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/utilitario-migrar-videos','conteudo');"><img src="img/icones/img-icone-migrar-48x48.png" title="Migrar Videos" width="48" height="48" /> <br />
                      Migrar Videos&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                    <td width="143" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/utilitario-youtube','conteudo');"><img src="img/icones/img-icone-youtube-64x64.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_ferramenta_youtube']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_ferramenta_youtube']; ?>&nbsp;</td>
                    <td height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/gerenciar-lives','conteudo');"><img src="img/icones/img-icone-lives.png" title="Lives YouTube & Facebook" width="48" height="48" /><br />Lives Redes Sociais&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                  </tr>
                  <tr>
                     <td height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="diagnosticar_problemas('<?php echo $login_code;?>');"><img src="img/icones/img-icone-diagnosticar.png" title="Diagnosticar Problemas" width="48" height="48" /><br />Diagnosticar&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>

                    <td width="143" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/configuracoes-watermark-player','conteudo');"><img src="img/icones/img-icone-watermark.png" title="Watermark" width="48" height="48" /><br />Logo no Player&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                    <td height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/configuracoes-relay','conteudo');"><img src="img/icones/img-icone-relay-m3u8.png" title="Relay rtmp/m3u8 FIXO" width="48" height="48" /><br />Relay rtmp/m3u8&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                  </tr>
                  <tr>
                    <td height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/gerenciar-agendamentos-relay','conteudo');"><img src="img/icones/img-icone-agendamento-relay-48x48.png" title="Agendamento Relay m3u8" width="48" height="48" /> <br />Agendamento Relay&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                    
                    <td width="143" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/utilitario-conversor','conteudo');"><img src="img/icones/img-icone-conversor.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_utilitario_conversor']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_utilitario_conversor']; ?></td>
                  </tr>
                </table>
            </div>
          </div></td>
        </tr>
      </table>
      </td>
    </tr>
  </table>
  <?php } ?>
  <?php if($dados_stm["aplicacao"] == 'live') { ?>
  <table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px">
<tr>
          <td width="885" align="center">
          <div id="quadro">
            <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_gerenciamento_streaming');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong><?php echo $lang['lang_info_pagina_informacoes_tab_gerenciamento_streaming']; ?></strong></div>
            <div class="texto_medio" id="quadro-conteudo">
                <table width="887" border="0" align="center" cellpadding="0" cellspacing="0" style="display:block" id="tabela_gerenciamento_streaming">
                  <tr>
                    <td width="126" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/dados-conexao','conteudo');"><img src="img/icones/img-icone-dados-conexao.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_dados_conexao']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_dados_conexao']; ?>&nbsp;</td>
        <td width="126" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/configuracoes-streaming','conteudo');"><img src="img/icones/img-icone-configuracoes.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_config']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_config']; ?>&nbsp;</td>
        <td width="126" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="carregar_players();"><img src="img/icones/img-icone-players.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_players']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_players']; ?></td>
        <td width="126" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/espectadores-conectados','conteudo');"><img src="img/icones/img-icone-espectadores.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_espectadores_conectados']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_espectadores_conectados']; ?></td>
                    <td width="126" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="carregar_estatisticas_streaming('<?php echo $login_code;?>');"><img src="img/icones/img-icone-estatistica.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_stats']; ?>" width="48" height="48" /> <br />
                    <?php echo $lang['lang_info_pagina_informacoes_tab_menu_stats']; ?></td>
                    <td width="126" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/gerenciar-lives','conteudo');"><img src="img/icones/img-icone-lives.png" title="Lives YouTube &amp; Facebook" width="48" height="48" /><br />
                      Lives Redes Sociais&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                    <td width="126" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/app-wowza-gocoder','conteudo');"><img src="img/icones/img-icone-wowza-gocoder.png" title="Ao Vivo Celular" width="48" height="48" /> <br />
                    Fazer ao vivo usando Smartphone&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                  </tr>
                    <?php if($dados_stm["exibir_app_android"] == 'sim') { ?>
                  <tr>
                    <td width="126" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/app-android','conteudo');"><img src="img/icones/img-icone-app-android-64x64.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_app_android']; ?>" width="48" height="48" /> <br />
                    <?php echo $lang['lang_info_pagina_informacoes_tab_menu_app_android']; ?>&nbsp;</td>
                    <td width="143" height="80" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/app-android-web','conteudo');"><img src="img/icones/img-icone-app-android-web.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_app_android']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_app_android']; ?> Web&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                    <td height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/gerenciar-geoip','conteudo');"><img src="img/icones/img-icone-geoip.png" width="48" height="48" /> <br />Restri&ccedil;&atilde;o GeoIP&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
                    <td width="126" height="80" align="center">&nbsp;</td>
                    <td width="126" height="80" align="center">&nbsp;</td>
                    <td width="126" height="80" align="center">&nbsp;</td>
                    <td width="126" height="80" align="center">&nbsp;</td>
                  </tr>
                     <?php } ?>
               </table>
            </div>
          </div></td>
          </tr>
      </table>
  <?php } ?>
  <?php if($dados_stm["aplicacao"] == 'vod') { ?>
  <table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px">
<tr>
          <td width="885" align="center">
          <div id="quadro">
            <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_gerenciamento_streaming');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong><?php echo $lang['lang_info_pagina_informacoes_tab_gerenciamento_streaming']; ?></strong></div>
            <div class="texto_medio" id="quadro-conteudo">
                <table width="887" border="0" align="center" cellpadding="0" cellspacing="0" style="display:block" id="tabela_gerenciamento_streaming">
                  <tr>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/dados-conexao','conteudo');"><img src="img/icones/img-icone-dados-conexao.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_dados_conexao']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_dados_conexao']; ?>&nbsp;</td>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/configuracoes-streaming','conteudo');"><img src="img/icones/img-icone-configuracoes.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_config']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_config']; ?>&nbsp;</td>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="carregar_players();"><img src="img/icones/img-icone-players.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_players']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_players']; ?></td>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/espectadores-conectados','conteudo');"><img src="img/icones/img-icone-espectadores.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_espectadores_conectados']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_espectadores_conectados']; ?></td>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="carregar_estatisticas_streaming('<?php echo $login_code;?>');"><img src="img/icones/img-icone-estatistica.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_stats']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_stats']; ?></td>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/gerenciar-videos','conteudo');"><img src="img/icones/img-icone-gerenciador-videos.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_gerenciar_videos']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_gerenciar_videos']; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/utilitario-youtube','conteudo');"><img src="img/icones/img-icone-youtube-64x64.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_ferramenta_youtube']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_ferramenta_youtube']; ?>&nbsp;</td>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/utilitario-youtube','conteudo');">&nbsp;</td>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/espectadores-conectados','conteudo');">&nbsp;</td>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="carregar_estatisticas_streaming('<?php echo $login_code;?>');">&nbsp;</td>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/gerenciar-videos','conteudo');">&nbsp;</td>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/utilitario-renomear-videos','conteudo');">&nbsp;</td>
                  </tr>
                </table>
            </div>
          </div></td>
          </tr>
      </table>
  <?php } ?>
  <?php if($dados_stm["aplicacao"] == 'ipcamera') { ?>
  <table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px">
<tr>
          <td width="885" align="center">
          <div id="quadro">
            <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_gerenciamento_streaming');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong><?php echo $lang['lang_info_pagina_informacoes_tab_gerenciamento_ip_camera']; ?></strong></div>
            <div class="texto_medio" id="quadro-conteudo">
                <table width="887" border="0" align="center" cellpadding="0" cellspacing="0" style="display:block" id="tabela_gerenciamento_streaming">
                  <tr>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/gerenciar-ip-cameras','conteudo');"><img src="img/icones/img-icone-camera.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_gerenciar_ip_cameras']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_gerenciar_ip_cameras']; ?>&nbsp;</td>
              <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/configuracoes-streaming','conteudo');"><img src="img/icones/img-icone-configuracoes.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_config']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_config']; ?>&nbsp;</td>
              <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="carregar_players();"><img src="img/icones/img-icone-players.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_players']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_players']; ?></td>
              <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/espectadores-conectados','conteudo');"><img src="img/icones/img-icone-espectadores.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_espectadores_conectados']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_espectadores_conectados']; ?></td>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="carregar_estatisticas_streaming('<?php echo $login_code;?>');"><img src="img/icones/img-icone-estatistica.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_stats']; ?>" width="48" height="48" /> <br />
                    <?php echo $lang['lang_info_pagina_informacoes_tab_menu_stats']; ?></td>
                    <td width="147" height="75">&nbsp;</td>
                  </tr>
                </table>
            </div>
          </div></td>
          </tr>
      </table>
  <?php } ?>
  <?php if($dados_stm["aplicacao"] == 'relayrtsp') { ?>
  <table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px">
<tr>
          <td width="885" align="center">
          <div id="quadro">
            <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_gerenciamento_streaming');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong><?php echo $lang['lang_info_pagina_informacoes_tab_gerenciamento_streaming']; ?></strong></div>
            <div class="texto_medio" id="quadro-conteudo">
                <table width="887" border="0" align="center" cellpadding="0" cellspacing="0" style="display:block" id="tabela_gerenciamento_streaming">
                  <tr>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/configuracoes-relay','conteudo');"><img src="img/icones/img-icone-relay.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_config_relay']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_config_relay']; ?>&nbsp;</td>
              <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/configuracoes-streaming','conteudo');"><img src="img/icones/img-icone-configuracoes.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_config']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_config']; ?>&nbsp;</td>
              <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="carregar_players();"><img src="img/icones/img-icone-players.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_players']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_players']; ?></td>
              <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/espectadores-conectados','conteudo');"><img src="img/icones/img-icone-espectadores.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_espectadores_conectados']; ?>" width="48" height="48" /> <br />
                        <?php echo $lang['lang_info_pagina_informacoes_tab_menu_espectadores_conectados']; ?></td>
                    <td width="147" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="carregar_estatisticas_streaming('<?php echo $login_code;?>');"><img src="img/icones/img-icone-estatistica.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_stats']; ?>" width="48" height="48" /> <br />
                    <?php echo $lang['lang_info_pagina_informacoes_tab_menu_stats']; ?></td>
                    <td width="147" height="75">&nbsp;</td>
                  </tr>
                </table>
            </div>
          </div></td>
          </tr>
      </table>
  <?php } ?>
  <table width="900" border="0" cellpadding="0" cellspacing="0" align="center" style="margin-top:10px">
    <tr>
      <td width="885"><div id="quadro">
          <div id="quadro-topo"><span><img src="/img/icones/img-icone-olho-64x64.png" width="16" height="16" onclick="hide_show('tabela_painel');" style="cursor:pointer; padding-top:7px;" title="Ocultar/Hide" /></span><strong><?php echo $lang['lang_info_pagina_informacoes_tab_gerenciamento_painel']; ?></strong></div>
        <div class="texto_medio" id="quadro-conteudo">
            <table width="887" border="0" align="center" cellpadding="0" cellspacing="0" style="display:block" id="tabela_painel">
              <tr>
                <td width="126" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/configuracoes-painel','conteudo');"><img src="img/icones/img-icone-configuracoes.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_config_painel']; ?>" width="48" height="48" /> <br />
                    <?php echo $lang['lang_info_pagina_informacoes_tab_menu_config_painel']; ?></td>
              <td width="126" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/logs','conteudo');"><img src="img/icones/img-icone-logs-64x64.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_logs']; ?>" width="48" height="48" /> <br />
                    <?php echo $lang['lang_info_pagina_informacoes_tab_menu_logs']; ?></td>
          <td width="126" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/streaming-api','conteudo');"><img src="img/icones/img-icone-api.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_api']; ?>" width="48" height="48" /> <br />
                    <?php echo $lang['lang_info_pagina_informacoes_tab_menu_api']; ?>&nbsp;</td>
          <td width="126" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/configuracoes-painel','conteudo');"><img src="img/icones/img-icone-idioma.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_idioma']; ?>" width="48" height="48" /> <br />
                    <?php echo $lang['lang_info_pagina_informacoes_tab_menu_idioma']; ?>&nbsp;</td>

                <?php if($dados_revenda["stm_exibir_tutoriais"] == 'sim') { ?>
                <td width="126" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/ajuda','conteudo');"><img src="img/icones/img-icone-ajuda-64x64.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_ajuda']; ?>" width="48" height="48" /> <br />
                    <?php echo $lang['lang_info_pagina_informacoes_tab_menu_ajuda']; ?></td>
    <?php } ?>
                    <?php if($dados_revenda["stm_exibir_tutoriais"] == 'url') { ?>
                <td width="126" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="window.open('<?php echo $dados_revenda["url_tutoriais"]; ?>');"><img src="img/icones/img-icone-ajuda-64x64.png" title="<?php echo $lang['lang_info_pagina_informacoes_tab_menu_ajuda']; ?>" width="48" height="48" /> <br />
                    <?php echo $lang['lang_info_pagina_informacoes_tab_menu_ajuda']; ?></td>
    <?php } ?>
                    <?php if($dados_revenda["stm_exibir_downloads"] == 'sim') { ?>
                <td width="126" height="75" align="center" class="texto_padrao_destaque" style="cursor:pointer" onclick="abrir_log_sistema();window.open('/downloads','conteudo');"><img src="img/icones/img-icone-download-64x64.png" title="Downloads" width="48" height="48" /> <br />
                    Downloads</td>
                <?php } ?>
              </tr>
            </table>
        </div>
      </div></td>
    </tr>
  </table>
  <?php } else { ?>
  <table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:20px; background-color:#FFFF66; border:#DFDF00 4px dashed">
<tr>
        <td width="40" height="50" align="center" scope="col"><img src="/img/icones/atencao.png" width="16" height="16" /></td>
      <td width="860" align="left" class="texto_status_erro" scope="col"><?php echo $lang['lang_alerta_bloqueio']; ?></td>
    </tr>
    </table>
  <?php } ?>
  <?php } else { ?>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:15%; background-color:#FFFF66; border:#DFDF00 4px dashed">
<tr>
        <td width="180" height="150" align="center" scope="col"><img src="/img/icones/img-icone-manutencao-128x128.png" width="128" height="128" /></td>
      <td width="720" align="left" class="texto_status_erro_pequeno" scope="col" style="padding-left:5px; padding-right:5px"><?php echo $dados_servidor["mensagem_manutencao"];?></td>
    </tr>
    </table>
  <?php } ?>
<br />
<br />
</div>
<!-- In cio div log do sistema -->
<div id="log-sistema-fundo"></div>
<div id="log-sistema">
<div id="log-sistema-botao"><img src="img/icones/img-icone-fechar.png" onclick="document.getElementById('log-sistema-fundo').style.display = 'none';document.getElementById('log-sistema').style.display = 'none';" style="cursor:pointer" title="<?php echo $lang['lang_titulo_fechar']; ?>" /></div>
<div id="log-sistema-conteudo"></div>
</div>
<!-- Fim div log do sistema -->
</body>
</html>