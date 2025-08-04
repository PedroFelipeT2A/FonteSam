<?php
//ob_start(function($b){if(strpos($b, "<html")!==false) {return str_replace(PHP_EOL,"",$b);} else {return $b;}});

$dados_config = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT * FROM streamings where login = '".code_decode(query_string('1'),"D")."'"));
$dados_servidor = @mysqli_fetch_array(@mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

if($_SERVER["HTTPS"] != "on") {
  header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
  exit();
}

/////////////////////////////////////////////////
/////////////////// Idioma //////////////////////
/////////////////////////////////////////////////

if($dados_stm["idioma_painel"] == "es") {

$lang[ 'lang_info_studioweb_bem_vindo' ] = '&iexcl;Bienvenido de nuevo!';
$lang[ 'lang_info_studioweb_espectadores_conectados' ] = 'Televidentes: ';
$lang[ 'lang_info_studioweb_iniciar_transmissao' ] = 'Iniciar/Terminar Transmisi&oacute;n';
$lang[ 'lang_info_studioweb_configuracoes' ] = 'Configura&ccedil;&otilde;es';
$lang[ 'lang_info_studioweb_instrucoes_uso' ] = 'Instrucciones de Uso';
$lang[ 'lang_info_studioweb_tempo_conectado' ] = 'Tiempo Conectado';
$lang[ 'lang_info_studioweb_camera_microfone' ] = 'Camara & Micr&oacute;fono';
$lang[ 'lang_info_studioweb_ligar_microfone' ] = 'Prender/Apagar Micr&oacute;fono';
$lang[ 'lang_info_studioweb_ligar_camera' ] = 'Prender/Apagar Camara';
$lang[ 'lang_info_studioweb_escolha_microfone' ] = 'Escolha um Micr&oacute;fono';
$lang[ 'lang_info_studioweb_player_transmissao' ] = 'Transmisi&oacute;n';
$lang[ 'lang_info_studioweb_player_assistir' ] = 'Ver Transmisi&oacute;n';
$lang[ 'lang_info_studioweb_padrao' ] = 'Patron';
$lang[ 'lang_info_studioweb_resolucao' ] = 'Resoluci&oacute;n';
$lang[ 'lang_info_studioweb_conectar' ] = 'Conectar';
$lang[ 'lang_info_studioweb_desconectar' ] = 'Desconectar';
$lang[ 'lang_info_studioweb_status_conectado' ] = 'Conectado con exito, transmitiendo en vivo!';
$lang[ 'lang_info_studioweb_status_desconectado' ] = 'Desconectado!';
$lang[ 'lang_info_studioweb_status_transmitindo' ] = 'Transmitiendo En Vivo';
$lang[ 'lang_info_studioweb_status_erro_conectar' ] = 'Falla al intentar conectar!';
$lang[ 'lang_info_studioweb_status_erro_conectar_bitrate' ] = 'Falla al intentar conectar, bitrate esta por encima del limite de su plan!';
$lang[ 'lang_info_studioweb_status_reconectar' ] = 'Intentando reconectar...';
$lang[ 'lang_info_studioweb_status_alert_atualizar_config' ] = 'Actualizar Configura&ccedil;&otilde;es';
$lang[ 'lang_info_studioweb_status_alert_atualizar_config_msg' ] = 'Configura&ccedil;&otilde;es actualizadas con exito!';
$lang[ 'lang_info_studioweb_info_dados_invalidos' ] = 'Error al cargar el WebDJ, por favor comprobar si el enlace al que se ha accedido sigue siendo v&aacute;lido. Error 0x001';
$lang[ 'lang_info_studioweb_info_wss_offline' ] = 'No se pudo conectar al servidor. Error 0x002';
$lang[ 'lang_info_studioweb_info_erro_aovivo' ] = 'Ya hay una transmisi&oacute;n en vivo en curso. Para iniciar una nueva transmisi&oacute;n en vivo, debes primero terminar la otra.';
$lang[ 'lang_info_studioweb_info_transmissao' ] = 'No hay transmisi&oacute;n activa, primero debes conectar al servidor para luego reproducir la transmisi&oacute;n en este player.';
$lang[ 'lang_info_studioweb_info_modal_config' ] = 'Ajustes';
$lang[ 'lang_info_studioweb_info_modal_salvar' ] = 'Guardar';
$lang[ 'lang_info_studioweb_info_modal_atualizar' ] = 'Actualizar';
$lang[ 'lang_info_studioweb_info_modal_instrucoes' ] = 'Instrucciones de Uso';
$lang[ 'lang_info_studioweb_info_modal_instrucoes_msg' ] = '1-Cree la lista de reproducci&oacute;n agregando canciones desde su computadora/tel&eacute;fono m&oacute;vil.<br>2-Configure el samplerate y bitrate que deseadas en el bot&oacute;n <i class="mdi mdi-settings"></i><br>3-Haga click en <i class="mdi mdi-power-plug"></i> Conectar y luego en el bot&oacute;n play de la playlist para iniciar la transmisi&oacute;n.<br><br><b>Mixer Playlists</b><br>-Utilice el control deslizante para establecer el balance de volumen entre las dos listas de reproducci&oacute;n.<br><br><b>Micr&oacute;fono</b><br>-&Uacute;selo para hablar durante la transmisi&oacute;n.<br><br><b>Playlist Secundario</b><br>-&Uacute;selo para mezclar otro contenido como vi&ntilde;etas.<br><br><b>M&uacute;sicas "Desconocido"</b><br>-Eso pasa cuando la tag IDV3 del archivo de m&uacute;sica no est&aacute; configurado. Utilice un programa en su computadora para editar las tags correctamente.';
$lang[ 'lang_info_studioweb_info_modal_fechar' ] = 'Cerrar';
$lang[ 'lang_info_studioweb_info_firefox' ] = 'El WebDJ requiere el uso del navegador Mozilla Firefox. Usar otro navegador puede no funcionar, reducir la calidad de transmisi&oacute;n y generar est&aacute;tica. Haga click <a href="https://www.mozilla.org/pt-BR/firefox/download/thanks/" target="_blank">aqui</a> para descargar el Mozilla Firefox.';
$lang[ 'lang_info_studioweb_info_alterar_tema' ] = 'Cambiar Theme';

} else if($dados_stm["idioma_painel"] == "en") {

$lang[ 'lang_info_studioweb_bem_vindo' ] = 'Seja bem vindo de volta!';
$lang[ 'lang_info_studioweb_espectadores_conectados' ] = 'Espectadores: ';
$lang[ 'lang_info_studioweb_iniciar_transmissao' ] = 'Iniciar/Terminar Transmiss&atilde;o';
$lang[ 'lang_info_studioweb_configuracoes' ] = 'Configura&ccedil;&otilde;es';
$lang[ 'lang_info_studioweb_instrucoes_uso' ] = 'Instru&ccedil;&otilde;es de Uso';
$lang[ 'lang_info_studioweb_tempo_conectado' ] = 'Tempo Conectado';
$lang[ 'lang_info_studioweb_camera_microfone' ] = 'C&acirc;mera & Microfone';
$lang[ 'lang_info_studioweb_ligar_microfone' ] = 'Ligar/Desligar Microfone';
$lang[ 'lang_info_studioweb_ligar_camera' ] = 'Ligar/Desligar C&acirc;mera';
$lang[ 'lang_info_studioweb_escolha_microfone' ] = 'Escolha um Microfone';
$lang[ 'lang_info_studioweb_player_transmissao' ] = 'Transmiss&atilde;o';
$lang[ 'lang_info_studioweb_player_assistir' ] = 'Assistir Transmiss&atilde;o';
$lang[ 'lang_info_studioweb_padrao' ] = 'Padr&atilde;o';
$lang[ 'lang_info_studioweb_resolucao' ] = 'Resolu&ccedil;&atilde;o';
$lang[ 'lang_info_studioweb_conectar' ] = 'Conectar';
$lang[ 'lang_info_studioweb_desconectar' ] = 'Desconectar';
$lang[ 'lang_info_studioweb_status_conectado' ] = 'Conectado com sucesso, transmitindo ao vivo!';
$lang[ 'lang_info_studioweb_status_desconectado' ] = 'Desconectado!';
$lang[ 'lang_info_studioweb_status_transmitindo' ] = 'Transmitindo Ao Vivo';
$lang[ 'lang_info_studioweb_status_erro_conectar' ] = 'Falha ao tentar conectar-se!';
$lang[ 'lang_info_studioweb_status_erro_conectar_bitrate' ] = 'Falha ao tentar conectar-se, o bitrate configurado esta acima do limite de seu plano!';
$lang[ 'lang_info_studioweb_status_reconectar' ] = 'Tentando reconectar...';
$lang[ 'lang_info_studioweb_status_alert_atualizar_config' ] = 'Atualizar Configura&ccedil;&otilde;es';
$lang[ 'lang_info_studioweb_status_alert_atualizar_config_msg' ] = 'Configura&ccedil;&otilde;es atualizadas com sucesso!';
$lang[ 'lang_info_studioweb_info_dados_invalidos' ] = 'Erro ao carregar o Studio Web, por favor verifique se o link acessado esta correto. Error 0x001';
$lang[ 'lang_info_studioweb_info_wss_offline' ] = 'N&atilde;o foi poss&iacute;vel conectar-se ao servidor. Error 0x002';
$lang[ 'lang_info_studioweb_info_erro_aovivo' ] = 'J&aacute; existe transmiss&atilde;o ao vivo em andamento. Para iniciar uma transmiss&atilde;o ao vivo primeiro termine a outra transmiss&atilde;o.';
$lang[ 'lang_info_studioweb_info_transmissao' ] = 'Nenhuma transmiss&atilde;o ativa, primeiro voc&ecirc; deve iniciar uma transmiss&atilde;o clicando em "Conectar"';
$lang[ 'lang_info_studioweb_info_modal_config' ] = 'Configura&ccedil;&otilde;es';
$lang[ 'lang_info_studioweb_info_modal_salvar' ] = 'Salvar';
$lang[ 'lang_info_studioweb_info_modal_atualizar' ] = 'Atualizar';
$lang[ 'lang_info_studioweb_info_modal_instrucoes' ] = 'Instru&ccedil;&otilde;es de Uso';
$lang[ 'lang_info_studioweb_info_modal_instrucoes_msg' ] = '1-Crie a playlist adicionando as m&uacute;sicas de seu computador/celular.<br>2-Configure o samplerate e bitrate desejado no bot&atilde;o <i class="mdi mdi-settings"></i><br>3-Clique em <i class="mdi mdi-power-plug"></i> Conectar e depois no bot&atilde;o de play da playlist para iniciar a transmiss&atilde;o.<br><br><b>Mixer Playlists</b><br>-Utilize o slide para definir o equilibrio do volume entre as duas playlists.<br><br><b>Microfone</b><br>-Utilize para locu&ccedil;&atilde;o durante a transmiss&atilde;o.<br><br><b>Playlist Secund&aacute;ria</b><br>-Utilize para mixar outros conte&uacute;dos como vinhetas.<br><br><b>M&uacute;sicas com nome "Desconhecido"</b><br>-Isso ocorre quando a tag IDV3 do arquivo de m&uacute;sica n&atilde;o est&aacute; configurado. Use um programa em seu computador para editar as tags corretamente.';
$lang[ 'lang_info_studioweb_info_modal_fechar' ] = 'Fechar';
$lang[ 'lang_info_studioweb_info_firefox' ] = 'O WebDJ requer uso do navegador Mozilla Firefox. Usar outro navegador poder&aacute; reduzir a qualidade da transmiss&atilde;o e gerar est&aacute;tica. Clique <a href="https://www.mozilla.org/pt-BR/firefox/download/thanks/" target="_blank">aqui</a> para fazer download do Mozilla Firefox.';

} else {


$lang[ 'lang_info_studioweb_bem_vindo' ] = 'Seja bem vindo de volta!';
$lang[ 'lang_info_studioweb_espectadores_conectados' ] = 'Espectadores: ';
$lang[ 'lang_info_studioweb_iniciar_transmissao' ] = 'Iniciar/Terminar Transmiss&atilde;o';
$lang[ 'lang_info_studioweb_configuracoes' ] = 'Configura&ccedil;&otilde;es';
$lang[ 'lang_info_studioweb_instrucoes_uso' ] = 'Instru&ccedil;&otilde;es de Uso';
$lang[ 'lang_info_studioweb_tempo_conectado' ] = 'Tempo Conectado';
$lang[ 'lang_info_studioweb_camera_microfone' ] = 'C&acirc;mera & Microfone';
$lang[ 'lang_info_studioweb_ligar_microfone' ] = 'Ligar/Desligar Microfone';
$lang[ 'lang_info_studioweb_ligar_camera' ] = 'Ligar/Desligar C&acirc;mera';
$lang[ 'lang_info_studioweb_escolha_microfone' ] = 'Escolha um Microfone';
$lang[ 'lang_info_studioweb_player_transmissao' ] = 'Transmiss&atilde;o';
$lang[ 'lang_info_studioweb_player_assistir' ] = 'Assistir Transmiss&atilde;o';
$lang[ 'lang_info_studioweb_padrao' ] = 'Padr&atilde;o';
$lang[ 'lang_info_studioweb_resolucao' ] = 'Resolu&ccedil;&atilde;o';
$lang[ 'lang_info_studioweb_conectar' ] = 'Conectar';
$lang[ 'lang_info_studioweb_desconectar' ] = 'Desconectar';
$lang[ 'lang_info_studioweb_status_conectado' ] = 'Conectado com sucesso, transmitindo ao vivo!';
$lang[ 'lang_info_studioweb_status_desconectado' ] = 'Desconectado!';
$lang[ 'lang_info_studioweb_status_transmitindo' ] = 'Transmitindo Ao Vivo';
$lang[ 'lang_info_studioweb_status_erro_conectar' ] = 'Falha ao tentar conectar-se!';
$lang[ 'lang_info_studioweb_status_erro_conectar_bitrate' ] = 'Falha ao tentar conectar-se, o bitrate configurado esta acima do limite de seu plano!';
$lang[ 'lang_info_studioweb_status_reconectar' ] = 'Tentando reconectar...';
$lang[ 'lang_info_studioweb_status_alert_atualizar_config' ] = 'Atualizar Configura&ccedil;&otilde;es';
$lang[ 'lang_info_studioweb_status_alert_atualizar_config_msg' ] = 'Configura&ccedil;&otilde;es atualizadas com sucesso!';
$lang[ 'lang_info_studioweb_info_dados_invalidos' ] = 'Erro ao carregar o Studio Web, por favor verifique se o link acessado esta correto. Error 0x001';
$lang[ 'lang_info_studioweb_info_wss_offline' ] = 'N&atilde;o foi poss&iacute;vel conectar-se ao servidor. Error 0x002';
$lang[ 'lang_info_studioweb_info_erro_aovivo' ] = 'J&aacute; existe transmiss&atilde;o ao vivo em andamento. Para iniciar uma transmiss&atilde;o ao vivo primeiro termine a outra transmiss&atilde;o.';
$lang[ 'lang_info_studioweb_info_transmissao' ] = 'Nenhuma transmiss&atilde;o ativa, primeiro voc&ecirc; deve iniciar uma transmiss&atilde;o clicando em "Conectar"';
$lang[ 'lang_info_studioweb_info_modal_config' ] = 'Configura&ccedil;&otilde;es';
$lang[ 'lang_info_studioweb_info_modal_salvar' ] = 'Salvar';
$lang[ 'lang_info_studioweb_info_modal_atualizar' ] = 'Atualizar';
$lang[ 'lang_info_studioweb_info_modal_instrucoes' ] = 'Instru&ccedil;&otilde;es de Uso';
$lang[ 'lang_info_studioweb_info_modal_instrucoes_msg' ] = '1-Crie a playlist adicionando as m&uacute;sicas de seu computador/celular.<br>2-Configure o samplerate e bitrate desejado no bot&atilde;o <i class="mdi mdi-settings"></i><br>3-Clique em <i class="mdi mdi-power-plug"></i> Conectar e depois no bot&atilde;o de play da playlist para iniciar a transmiss&atilde;o.<br><br><b>Mixer Playlists</b><br>-Utilize o slide para definir o equilibrio do volume entre as duas playlists.<br><br><b>Microfone</b><br>-Utilize para locu&ccedil;&atilde;o durante a transmiss&atilde;o.<br><br><b>Playlist Secund&aacute;ria</b><br>-Utilize para mixar outros conte&uacute;dos como vinhetas.<br><br><b>M&uacute;sicas com nome "Desconhecido"</b><br>-Isso ocorre quando a tag IDV3 do arquivo de m&uacute;sica n&atilde;o est&aacute; configurado. Use um programa em seu computador para editar as tags corretamente.';
$lang[ 'lang_info_studioweb_info_modal_fechar' ] = 'Fechar';
$lang[ 'lang_info_studioweb_info_firefox' ] = 'O WebDJ requer uso do navegador Mozilla Firefox. Usar outro navegador poder&aacute; reduzir a qualidade da transmiss&atilde;o e gerar est&aacute;tica. Clique <a href="https://www.mozilla.org/pt-BR/firefox/download/thanks/" target="_blank">aqui</a> para fazer download do Mozilla Firefox.';

}

if(query_string('2') == "espectadores") {

$dados_wowza = total_espectadores_conectados($dados_servidor["ip"],$dados_servidor["senha"],$dados_stm["login"]);

echo $lang['lang_info_studioweb_espectadores_conectados'].$dados_wowza["espectadores"];

exit();
}

if($dados_servidor["nome_principal"]) {
$servidor = $dados_servidor["nome_principal"].".".$dados_config["dominio_padrao"];
} else {
$servidor = $dados_servidor["nome"].".".$dados_config["dominio_padrao"];
}

$webrtc_chave = substr(md5($dados_stm["login"].time()),0,10);

mysqli_query($conexao,"Update streamings set webrtc_chave = '".$webrtc_chave."' where codigo = '".$dados_stm["codigo"]."'");

$status_streaming = status_streaming($dados_servidor["ip"],$dados_servidor["senha"],$dados_stm["login"]);
//$status_streaming["status_transmissao"] == "aovivo"

for($i=0;$i<=1000;$i++){echo "\n\n";}for($i=0;$i<=100;$i++){echo "        ";}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>[<?php echo $dados_stm["login"];?>] Studio Web</title>
  <link rel="stylesheet" href="/inc-studio-web/iconfonts/mdi/font/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="/inc-studio-web/vendor.bundle.base.css">
  <link rel="stylesheet" href="/inc-studio-web/vendor.bundle.addons.css">
  <link  href="/player/inc-webrtc/plyr.css" rel="stylesheet" />
  <?php if($_COOKIE["theme"] == "dark") { ?>
  <link rel="stylesheet" href="/inc-studio-web/style.css">
  <?php } else { ?>
  <link rel="stylesheet" href="/inc-studio-web/style-light.css">
  <?php } ?>
  <link rel="shortcut icon" href="/inc-studio-web/favicon.png" />
  <style type="text/css">#publish-video-container, #publisher-video {background-color: #cfcfcf; width: 100%; border-radius: 10px;}#play-video-container{display:flex;justify-content:center;align-items:center;}#play-video-container, #player-video { width: 100%;}.plyr{display: none;border-radius: 10px;} .plyr__time, .plyr__progress, .plyr__control--overlaid{display: none!important;}#video-live-indicator { position: absolute; top: 0.5em; left: 1.5em; opacity: 90%; }</style>
</head>
<body>
  <div class="container-scroller">
    <nav class="navbar col-lg-12 col-12 p-0 d-flex flex-row">
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
          <div class="welcome-message"><i class="mdi mdi-webcam btn-inverse-danger" style=" font-size: 48px; vertical-align: middle;"></i>&nbsp;StudioWeb&nbsp;</div>
        </div>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item top-login" style="margin-right: 15px;"><i class="mdi mdi-account-check mx-0" style="font-size:22px; vertical-align: middle;"></i> <?php echo $dados_stm["login"];?></li>
          <li class="nav-item top-espectadores" style="margin-right: 15px;"><i class="mdi mdi-chart-bar mx-0" style="font-size:20px; vertical-align: middle;"></i> <span id="espectadores-atual"></span></li>
          <li class="nav-item top-data" style="margin-right: 15px;"><i class="mdi mdi-calendar mx-0" style="font-size:20px; vertical-align: middle;"></i> <span id="data-atual" style="margin-right: 15px;"></span> <i class="mdi mdi-clock mx-0" style="font-size:20px; vertical-align: middle;"></i> <span id="hora-atual"></span></li>
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator" href="javascript:alterar_tema();" data-toggle="tooltip" data-placement="bottom" title="<?php echo $lang['lang_info_studioweb_info_alterar_tema']; ?>" data-original-title="<?php echo $lang['lang_info_studioweb_info_alterar_tema']; ?>">
              <i class="mdi mdi-theme-light-dark mx-0"></i>
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="modal fade" id="aviso-erro" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="background: rgb(0 0 0 / 90%)!important;">
      <div class="modal-dialog" role="document" style="max-width: 70%!important;">
        <div class="modal-content">
          <div class="modal-header" style="padding:15px">
            <h5 class="modal-title" id="ModalLabel">Erro!</h5>
          </div>
          <div class="modal-body" style="padding: 15px;">
            <div id="aviso-erro-dados-invalidos" class="alert alert-danger" role="alert" style="display: none;"><i class="mdi mdi-alert-circle"></i> <?php echo $lang['lang_info_studioweb_info_dados_invalidos']; ?></div>
            <div id="aviso-erro-offline" class="alert alert-warning" role="alert" style="display: none;"><i class="mdi mdi-alert-circle"></i> <?php echo $lang['lang_info_studioweb_info_wss_offline']; ?></div>
            <div id="aviso-erro-aovivo" class="alert alert-warning" role="alert" style="display: none;"><i class="mdi mdi-alert-circle"></i> <?php echo $lang['lang_info_studioweb_info_erro_aovivo']; ?></div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="instrucoes" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="padding:15px">
            <h5 class="modal-title" id="ModalLabel"><?php echo $lang['lang_info_studioweb_info_modal_instrucoes']; ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="padding: 15px;font-size: 13px;">
            <?php echo $lang['lang_info_studioweb_info_modal_instrucoes_msg']; ?>
          </div>
          <div class="modal-footer" style="padding: 5px;">
            <button type="button" class="btn btn-sm btn-info" data-dismiss="modal"><?php echo $lang['lang_info_studioweb_info_modal_fechar']; ?></button>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <?php if($dados_stm["codigo"] > 0 && $status_streaming["status_transmissao"] != "aovivo" && $status_streaming["status"] == "loaded") { ?>

            <div class="col-lg-4" style="margin-bottom:18px">
              <div class="card">
                <div class="card-body" style="min-height: 128px;">
                  <button id="publish-play-stop" class="btn btn-sm btn-inverse-success" data-toggle="tooltip" data-placement="bottom" title="<?php echo $lang['lang_info_studioweb_iniciar_transmissao']; ?>" data-original-title="<?php echo $lang['lang_info_studioweb_iniciar_transmissao']; ?>"><i class="mdi mdi-power-plug" style="vertical-align: middle;"></i> <?php echo $lang['lang_info_studioweb_conectar']; ?></button>
                  <button class="btn btn-sm btn-inverse-warning" onclick="$('#instrucoes').modal('show');" data-toggle="tooltip" data-placement="bottom" title="<?php echo $lang['lang_info_studioweb_instrucoes_uso']; ?>" data-original-title="<?php echo $lang['lang_info_studioweb_instrucoes_uso']; ?>"><i class="mdi mdi-help-circle"></i></button>
                  <button class="btn btn-sm btn-inverse-secondary" data-toggle="tooltip" data-placement="bottom" title="<?php echo $lang['lang_info_studioweb_tempo_conectado']; ?>" data-original-title="<?php echo $lang['lang_info_studioweb_tempo_conectado']; ?>"><i class="mdi mdi-alarm" style="vertical-align: middle;"></i> <span id="timer">00:00:00</span></button>
                  <input class="form-control" type="text" id="status" readonly="readonly" value="<?php echo $lang['lang_info_studioweb_bem_vindo']; ?>" style="margin-top:20px;background: transparent!important;" />
                </div>
              </div>
            </div>

            <div class="col-lg-4" style="margin-bottom:18px">
              <div class="card">
                <div class="card-body" style="min-height: 128px;">
                  <div class="d-flex justify-content-between" style="margin-bottom: 5px;">
                    <div>
                      <h6><?php echo $lang['lang_info_studioweb_camera_microfone']; ?></h6>
                    </div>
                  </div>
                  <div class="input-group mb-2">
                  <select id="camera-list-select" class="form-control"></select>
                  <div class="input-group-append"><button id="camera-toggle" class="btn btn-sm btn-inverse-danger control-button" data-toggle="tooltip" data-placement="bottom" title="<?php echo $lang['lang_info_studioweb_ligar_camera']; ?>" data-original-title="<?php echo $lang['lang_info_studioweb_ligar_camera']; ?>" style="margin-right: 3px;"><i class="mdi mdi-webcam"></i></button></div>
                  </div>
                  <div class="input-group">
                  <select id="mic-list-select" class="form-control"></select>
                  <div class="input-group-append"><button id="mute-toggle" class="btn btn-sm btn-inverse-info control-button" data-toggle="tooltip" data-placement="bottom" title="<?php echo $lang['lang_info_studioweb_ligar_microfone']; ?>" data-original-title="<?php echo $lang['lang_info_studioweb_ligar_microfone']; ?>" style="margin-right: 3px;"><i class="mdi mdi-microphone"></i></button></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4" style="margin-bottom:18px">
              <div class="card">
                <div class="card-body" style="min-height: 128px;">
                  <div class="d-flex justify-content-between" style="margin-bottom: 5px;">
                    <div>
                      <h6><?php echo $lang['lang_info_studioweb_configuracoes']; ?></h6>
                    </div>
                  </div>
                  <form id="publish-settings-form">
	                  <div class="input-group mb-2">
	                  <div class="input-group-prepend"> <span class="input-group-text"><i class="mdi mdi-file-video"></i></span></div>
	                  <input type="number" class="form-control" id="videoBitrate" name="videoBitrate" value="<?php echo $dados_stm["bitrate"]-96;?>" min="500" max="<?php echo $dados_stm["bitrate"]-32;?>" data-toggle="tooltip" data-placement="bottom" title="Bitrate Video Kbps" data-original-title="Bitrate Video Kbps">
	                  <div class="input-group-append"><span class="input-group-text">Kbps</span></div>
	                  <div class="input-group-prepend"> <span class="input-group-text"><i class="mdi mdi-music-note"></i></span></div>
	                  <input type="number" class="form-control" id="audioBitrate" name="audioBitrate" value="96" min="32" max="320" data-toggle="tooltip" data-placement="bottom" title="Bitrate Audio Kbps" data-original-title="Bitrate Audio Kbps">
	                  <div class="input-group-append"><span class="input-group-text">Kbps</span></div>
	                  </div>

	                  <div class="input-group">
	                  <div class="input-group-prepend"> <span class="input-group-text"><i class="mdi mdi-image-filter-center-focus"></i></span></div>
	                  <select id="frameSize" name="frameSize" class="form-control" data-toggle="tooltip" data-placement="bottom" title="<?php echo $lang['lang_info_studioweb_resolucao']; ?>" data-original-title="<?php echo $lang['lang_info_studioweb_resolucao']; ?>"><option selected value="default"> <?php echo $lang['lang_info_studioweb_padrao']; ?> </option> <option value="1920x1080"> 1920x1080 </option> <option value="1280x720"> 1280x720 </option> <option value="800x600"> 800x600 </option> <option value="640x360"> 640x360 </option></select>
	                  <div class="input-group-prepend"> <span class="input-group-text"><i class="mdi mdi-apps"></i></span></div>
	                  <input type="number" class="form-control" id="videoFrameRate" name="videoFrameRate" value="30" min="24" max="60" data-toggle="tooltip" data-placement="bottom" title="Frame Rate" data-original-title="Frame Rate">
	                  </div>
              	  </form>

                </div>
              </div>
            </div>

          </div>
          <div class="row">
            <div class="col-lg-12" id="error-publish" style="display:none">
              <div class="alert alert-danger text-center" role="alert" style="color:#b61d26;font-size: 12px;"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill:#b61d26; width: 32px; height: 32px; "><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg><br><?php echo $lang['lang_info_studioweb_info_wss_offline']; ?></div>
            </div>
            <div class="col-lg-12" id="error-publish-bitrate" style="display:none">
              <div class="alert alert-danger text-center" role="alert" style="color:#b61d26;font-size: 12px;"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill:#b61d26; width: 32px; height: 32px; "><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg><br><?php echo $lang['lang_info_studioweb_status_erro_conectar_bitrate']; ?></div>
            </div>

            <div id="player-transmissao" class="col-lg-6">
              <div class="card">
                <div id="card-transmissao" class="card-body" style="padding: 0;background-color: #cfcfcf;border-radius: 10px;">
                	<div id="publish-video-container">
		                <video id="publisher-video" autoplay playsinline muted></video>
		                <div id="video-live-indicator">
		                  <span id="video-live-indicator-live" class="badge badge-pill badge-danger" style="display:none;">LIVE</span>
		                  <span id="video-live-indicator-error" class="badge badge-pill badge-warning" style="display:none;">ERRO</span>
		                </div>
		              </div>
                </div>
              </div>
            </div>

            <div id="player-assistir" class="col-lg-6">
              <div class="card">
                <div id="card-assistir" class="card-body" style="padding: 0;background-color: #cfcfcf;border-radius: 10px;">
                	<div id="play-video-container">
  <button id="player-btn" type="button" style="z-index: 1000;background: transparent; border: none;cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill:#ffffff; width: 72px; height: 72px; "><path d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM188.3 147.1c-7.6 4.2-12.3 12.3-12.3 20.9V344c0 8.7 4.7 16.7 12.3 20.9s16.8 4.1 24.3-.5l144-88c7.1-4.4 11.5-12.1 11.5-20.5s-4.4-16.1-11.5-20.5l-144-88c-7.4-4.5-16.7-4.7-24.3-.5z"/></svg></button>
<div class="alert alert-danger text-center" id="error-panel" style="display: none;color:#b61d26;font-size: 12px; margin: 20px;"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill:#b61d26; width: 32px; height: 32px; "><path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg><br><?php echo $lang['lang_info_studioweb_info_transmissao']; ?></div>
</div>
<video id="player-video" playsinline controls></video>
                </div>
              </div>
            </div>

              </div>
            </div>
            <?php }  ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
	let state = {
    publishing: false,
    pendingPublish: false,
    pendingPublishTimeout: undefined,
    muted: false,
    video: true,
    selectedCam: '',
    selectedMic: '',
    settings: {
      sdpURL: "wss://<?php echo $servidor; ?>/webrtc-session.json",
      applicationName: "<?php echo $dados_stm["login"]; ?>",
      streamName: "<?php echo $webrtc_chave; ?>",
      audioBitrate: "64",
      audioCodec: "opus",
      videoBitrate: "1024",
      videoCodec: "42e01f",
      videoFrameRate: "30",
      frameSize: "default"
    }
  };
  let msg_btn_start = '<i class="mdi mdi-power-plug" style="vertical-align: middle;"></i> <?php echo $lang['lang_info_studioweb_conectar']; ?>';
  let msg_btn_stop = '<i class="mdi mdi-power-plug-off" style="vertical-align: middle;"></i> <?php echo $lang['lang_info_studioweb_desconectar']; ?>';
  let msg_status1 = '<?php echo $lang['lang_info_studioweb_status_conectado']; ?>';
  let msg_status2 = '<?php echo $lang['lang_info_studioweb_status_desconectado']; ?>';
  let msg_status3 = '<?php echo $lang['lang_info_studioweb_status_erro_conectar']; ?>';
  let bitrate_package = <?php echo $dados_stm["bitrate"]; ?>;
</script>
<script src="/inc-studio-web/vendor.bundle.base.js"></script>
<script src="/inc-studio-web/vendor.bundle.addons.js"></script>
<script type="text/javascript" src="/player/inc-webrtc/adapter-latest.js"></script>
<script src="/player/inc-webrtc/plyr.polyfilled.js"></script>
<script type="module" crossorigin="use-credentials" src="/player/inc-webrtc/play.js"></script>
<script type="module" crossorigin="use-credentials" src="/player/inc-webrtc/publish.js"></script>
<script type="text/javascript">
window.oncontextmenu = function () {
return false;
}
$(document).keydown(function (event) {
if (event.keyCode == 123) {
return false;
} else if ((event.ctrlKey && event.shiftKey && event.keyCode == 73) || (event.ctrlKey && event.shiftKey && event.keyCode == 74)) {
return false;
} else if (event.ctrlKey && event.keyCode == 85) {
return false;
}
});
<?php if($dados_stm["codigo"] == 0 || empty($dados_stm["login"])) { ?>
$('#aviso-erro').modal('show');
$('#aviso-erro-dados-invalidos').show();
<?php } elseif($status_streaming["status"] != "loaded") { ?>
$('#aviso-erro').modal('show');
$('#aviso-erro-offline').show();
<?php } elseif($status_streaming["status_transmissao"] == "aovivo") { ?>
$('#aviso-erro').modal('show');
$('#aviso-erro-aovivo').show();
<?php } else { ?>
let config_stm = {
    playSdpURL: "wss://<?php echo $servidor; ?>/webrtc-session.json",
    playApplicationName: "<?php echo $dados_stm["login"]; ?>",
    playStreamName: "<?php echo $webrtc_chave; ?>"
};
const player = new Plyr("#player-video", {disableContextMenu: true});

$('[data-toggle="tooltip"]').tooltip();

// Timer
var seconds = 0, minutes = 0, hours = 0, t;

    function add() {
        seconds++;
        if (seconds >= 60) {
            seconds = 0;
            minutes++;
            }
            if (minutes >= 60) {
                minutes = 0;
                hours++;
            }


        var tempo = (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);
        $( "#timer" ).html( tempo );
    }
// CLock
function startClock(){var e=new Date,t=e.getHours(),n=e.getMinutes(),c=e.getSeconds();n=checkTime(n),c=checkTime(c),document.getElementById("hora-atual").innerHTML=t+":"+n+":"+c,setTimeout(startClock,500)}function checkTime(e){return e<10&&(e="0"+e),e}
//Cookie
function setCookie(e,t,i){var o=new Date;o.setDate(o.getDate()+i);var n=escape(t)+(null==i?"":"; expires="+o.toUTCString());document.cookie=e+"="+n}function getCookie(e){var t,i,o,n=document.cookie.split(";");for(t=0;t<n.length;t++)if(i=n[t].substr(0,n[t].indexOf("=")),o=n[t].substr(n[t].indexOf("=")+1),(i=i.replace(/^\s+|\s+$/g,""))==e)return unescape(o)}
//Tema
function alterar_tema() {
  var tema_atual = getCookie("theme");
  if(tema_atual == "dark") {
    setCookie("theme", "light", 7);
    $('link[href$="/inc-studio-web/style.css"]').attr('href','/inc-studio-web/style-light.css');
  } else {
    setCookie("theme", "dark", 7);
    $('link[href$="/inc-studio-web/style-light.css"]').attr('href','/inc-studio-web/style.css');
  }
}
$( document ).ready(function() {
	
  $("#play-video-container").height($("#publisher-video").height());
  $("#publish-video-container").height($("#publisher-video").height());

  var data_atual = new Date().toLocaleDateString(undefined, {
      day: '2-digit',
      month: 'long',
      year: 'numeric',
      weekday: 'long'
  });
  $( "#data-atual" ).html( data_atual );
  startClock();

$.get( "/studio-web/<?php echo query_string('1');?>/espectadores", function( total ) {
    $( "#espectadores-atual" ).html( total );
  });
setInterval(function () {
  $.get( "/studio-web/<?php echo query_string('1');?>/espectadores", function( total ) {
    $( "#espectadores-atual" ).html( total );
  });
}, 45000);

});
window.onresize = function() {
  $("#play-video-container").height($("#publisher-video").height());
  $("#publish-video-container").height($("#publisher-video").height());
}
<?php }  ?>
</script>
</body>
</html>