<?php
require_once("admin/inc/protecao-final.php");
require_once("admin/inc/classe.ftp.php");

@mysqli_query($conexao,"ALTER TABLE `playlists_agendamentos` ADD `finalizacao` CHAR(20) NOT NULL DEFAULT 'repetir';");
@mysqli_query($conexao,"ALTER TABLE `playlists_agendamentos` ADD `codigo_playlist_finalizacao` INT(10) NOT NULL DEFAULT '0';");
@mysqli_query($conexao,"ALTER TABLE `playlists_agendamentos` ADD `shuffle` CHAR(3) NOT NULL DEFAULT 'nao';");
@mysqli_query($conexao,"ALTER TABLE `playlists_agendamentos` ADD `inicio` INT(1) NOT NULL DEFAULT '2';");

$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

/////////////////////////////////////////////////
/////////////////// Idioma //////////////////////
/////////////////////////////////////////////////
if($dados_stm["idioma_painel"] == "pt-br") {
$lang[ 'lang_info_gerenciador_agendamentos_aba_calendario' ] = 'Calendário' ;
$lang[ 'lang_info_gerenciador_agendamentos_aba_editar_agendamento' ] = 'Atualizar Agendamento' ;
$lang[ 'lang_info_gerenciador_agendamentos_misturar_videos_sim' ] = 'Misturar V&iacute;deos: Sim' ;
$lang[ 'lang_info_gerenciador_agendamentos_misturar_videos_nao' ] = 'Misturar V&iacute;deos: N&atilde;o' ;
$lang[ 'lang_info_gerenciador_agendamentos_finalizacao' ] = 'Finaliza&ccedil;ao' ;
$lang[ 'lang_info_gerenciador_agendamentos_selecione_playlist' ] = 'Selecione uma playlist' ;
$lang[ 'lang_acao_gerenciador_agendamentos_editar_resultado_ok' ] = 'Agendamento atualizado com sucesso.' ;
$lang[ 'lang_info_gerenciador_agendamentos_frequencia' ] = 'Frequência' ;
$lang[ 'lang_info_gerenciador_agendamentos_frequencia1' ] = 'Executar em uma data específica' ;
$lang[ 'lang_info_gerenciador_agendamentos_frequencia2' ] = 'Executar diariamente' ;
$lang[ 'lang_info_gerenciador_agendamentos_frequencia3' ] = 'Executar em dias da semana' ;
$lang[ 'lang_info_gerenciador_agendamentos_info_frequencia1' ] = 'Executar no dia' ;
$lang[ 'lang_info_gerenciador_agendamentos_info_frequencia2' ] = 'Executar diariamente às' ;
$lang[ 'lang_info_gerenciador_agendamentos_info_frequencia3' ] = 'Executar' ;
} else if($dados_stm["idioma_painel"] == "en") {
$lang[ 'lang_info_gerenciador_agendamentos_aba_calendario' ] = 'Calendar' ;
$lang[ 'lang_info_gerenciador_agendamentos_aba_editar_agendamento' ] = 'Update Agendamento' ;
$lang[ 'lang_info_gerenciador_agendamentos_misturar_videos_sim' ] = 'Shuffle Videos: Yes' ;
$lang[ 'lang_info_gerenciador_agendamentos_misturar_videos_nao' ] = 'Shuffle Videos: No' ;
$lang[ 'lang_info_gerenciador_agendamentos_finalizacao' ] = 'Finishing' ;
$lang[ 'lang_info_gerenciador_agendamentos_selecione_playlist' ] = 'Select a playlist' ;
$lang[ 'lang_acao_gerenciador_agendamentos_editar_resultado_ok' ] = 'Schedules has been updated.' ;
$lang[ 'lang_info_gerenciador_agendamentos_frequencia' ] = 'Frequency' ;
$lang[ 'lang_info_gerenciador_agendamentos_frequencia1' ] = 'Run on a specific date' ;
$lang[ 'lang_info_gerenciador_agendamentos_frequencia2' ] = 'Run daily' ;
$lang[ 'lang_info_gerenciador_agendamentos_frequencia3' ] = 'Run on weekdays' ;
$lang[ 'lang_info_gerenciador_agendamentos_info_frequencia2' ] = 'Executar daily at' ;
$lang[ 'lang_info_gerenciador_agendamentos_info_frequencia3' ] = 'Run' ;
$lang[ 'lang_info_gerenciador_agendamentos_horario_agendado' ] = 'Scheduled Time';
} else {
$lang[ 'lang_info_gerenciador_agendamentos_aba_calendario' ] = 'Calendario' ;
$lang[ 'lang_info_gerenciador_agendamentos_aba_editar_agendamento' ] = 'Actualizar Programaccion' ;
$lang[ 'lang_info_gerenciador_agendamentos_misturar_videos_sim' ] = 'Misturar V&iacute;deos: Si' ;
$lang[ 'lang_info_gerenciador_agendamentos_misturar_videos_nao' ] = 'Misturar V&iacute;deos: No' ;
$lang[ 'lang_info_gerenciador_agendamentos_finalizacao' ] = 'Finalizaci&oacute;n' ;
$lang[ 'lang_info_gerenciador_agendamentos_selecione_playlist' ] = 'Selecciona una playlist' ;
$lang[ 'lang_acao_gerenciador_agendamentos_editar_resultado_ok' ] = 'Agendamento actualizado con exito.' ;
$lang[ 'lang_info_gerenciador_agendamentos_frequencia' ] = 'Frecuencia' ;
$lang[ 'lang_info_gerenciador_agendamentos_frequencia1' ] = 'Ejecutar en una fecha especifica' ;
$lang[ 'lang_info_gerenciador_agendamentos_frequencia2' ] = 'Ejecutar todos los dias' ;
$lang[ 'lang_info_gerenciador_agendamentos_frequencia3' ] = 'Ejecutar en dias de la semana' ;
$lang[ 'lang_info_gerenciador_agendamentos_info_frequencia2' ] = 'Ejecutar diariamente a las ' ;
$lang[ 'lang_info_gerenciador_agendamentos_info_frequencia3' ] = 'Ejecutar' ;
$lang[ 'lang_info_gerenciador_agendamentos_horario_agendado' ] = 'Hora Programado' ;
}

if($_POST["editar"]) {

list($hora,$minuto) = explode(":",$_POST["hora"]);

if(count($_POST["dias"]) > 0){
  $dias = implode(",",$_POST["dias"]);
}

mysqli_query($conexao,"Update playlists_agendamentos set codigo_playlist = '".$_POST["codigo_playlist"]."', frequencia = '".$_POST["frequencia"]."', data = '".$_POST["data"]."', hora = '".$hora."', minuto = '".$minuto."', dias = '".$dias."', finalizacao = '".$_POST["finalizacao"]."', codigo_playlist_finalizacao = '".$_POST["codigo_playlist_finalizacao"]."', shuffle = '".$_POST["shuffle"]."' where codigo = '".$_POST["codigo_agendamento"]."'") or die(mysqli_error($conexao));

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] .= status_acao("".$lang['lang_acao_gerenciador_agendamentos_editar_resultado_ok']."","ok");

header("Location: /gerenciar-agendamentos");
exit();
}

if($_POST["cadastrar"]) {

$dados_playlist = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM playlists where codigo = '".$_POST["codigo_playlist"]."'"));

list($hora,$minuto) = explode(":",$_POST["hora"]);

if(count($_POST["dias"]) > 0){
	$dias = implode(",",$_POST["dias"]);
}

$shuffle = ($_POST["shuffle"] == "sim") ? "sim" : "nao";

mysqli_query($conexao,"INSERT INTO playlists_agendamentos (codigo_stm,codigo_playlist,frequencia,data,hora,minuto,dias,tipo,shuffle,finalizacao,codigo_playlist_finalizacao) VALUES ('".$dados_stm["codigo"]."','".$_POST["codigo_playlist"]."','".$_POST["frequencia"]."','".$_POST["data"]."','".$hora."','".$minuto."','".$dias.",','playlist','".$shuffle."','".$_POST["finalizacao"]."','".$_POST["codigo_playlist_finalizacao"]."')") or die(mysqli_error($conexao));

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] .= status_acao("".$lang['lang_acao_gerenciador_agendamentos_resultado_ok']."","ok");

header("Location: /gerenciar-agendamentos");
exit();
}

if($_POST["remover_logs"]) {
mysqli_query($conexao,"Delete From playlists_agendamentos_logs Where codigo_stm = '".$dados_stm["codigo"]."'");

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] .= status_acao("Logs removidos com sucesso.","ok");

header("Location: /gerenciar-agendamentos");
exit();
}

if(query_string('1') == "carregar") {

header('Content-Type: application/json; charset=ISO-8859-1');

$lista_agendamentos = '[';

$sql = mysqli_query($conexao,"SELECT * FROM playlists_agendamentos where codigo_stm = '".$dados_stm["codigo"]."' ORDER by data");
while ($dados_agendamento = mysqli_fetch_array($sql)) {

$dados_playlist = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM playlists where codigo = '".$dados_agendamento["codigo_playlist"]."'"));
$dados_playlist_finalizacao = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM playlists where codigo = '".$dados_agendamento["codigo_playlist_finalizacao"]."'"));
$duracao_playlist = mysqli_fetch_array(mysqli_query($conexao,"SELECT SUM(duracao_segundos) as total FROM playlists_videos where codigo_playlist = '".$dados_playlist["codigo"]."'"));

$misturar = ($dados_agendamento["shuffle"] == "sim") ? $lang[ 'lang_info_gerenciador_agendamentos_misturar_videos_sim' ] : $lang[ 'lang_info_gerenciador_agendamentos_misturar_videos_nao' ];

$finalizacao = ($dados_agendamento["codigo_playlist_finalizacao"] > 0 && $dados_agendamento["finalizacao"] == "iniciar_playlist") ? $lang[ 'lang_info_gerenciador_agendamentos_finalizacao' ].": ".$dados_playlist_finalizacao["nome"] : $lang[ 'lang_info_gerenciador_agendamentos_finalizacao' ].": Loop/Repetir";

$duracao = $lang[ 'lang_info_gerenciador_playlists_lista_tabela_duracao' ].": ".sprintf('%02d:%02d:%02d', ($duracao_playlist["total"]/3600),($duracao_playlist["total"]/60%60), $duracao_playlist["total"]%60);

if($dados_agendamento["frequencia"] == "1") {

list($ano,$mes,$dia) = explode("-",$dados_agendamento["data"]);
$data = $dia."/".$mes."/".$ano;

$descricao = $lang['lang_info_gerenciador_agendamentos_info_frequencia1']." ".$data." ".$dados_agendamento["hora"].":".$dados_agendamento["minuto"]."<br>".$misturar."<br>".$finalizacao."<br>".$duracao;

$lista_agendamentos .= '{"id": "'.$dados_agendamento["codigo"].'", "title": "'.$dados_playlist["nome"].'", "className": "bg-dark", "start": "'.$dados_agendamento["data"].' '.$dados_agendamento["hora"].':'.$dados_agendamento["minuto"].'", "end": "'.date("Y-m-d H:i", (strtotime(date($dados_agendamento["data"].' '.$dados_agendamento["hora"].':'.$dados_agendamento["minuto"])) + $duracao_playlist["total"])).'", "description": "'.$descricao.'"},';

} elseif($dados_agendamento["frequencia"] == "2") {

$period = new DatePeriod(
     new DateTime($_GET['start']),
     new DateInterval('P1D'),
     new DateTime($_GET['end'].' +1 day')
);

foreach ($period as $p_key => $p_val) {

$descricao = "".$lang['lang_info_gerenciador_agendamentos_info_frequencia2']." ".$dados_agendamento["hora"].":".$dados_agendamento["minuto"]."<br>".$misturar."<br>".$finalizacao."<br>".$duracao;

$lista_agendamentos .= '{"id": "'.$dados_agendamento["codigo"].'", "title": "'.$dados_playlist["nome"].'", "className": "bg-info", "start": "'.$p_val->format('Y-m-d').' '.$dados_agendamento["hora"].':'.$dados_agendamento["minuto"].'", "end": "'.date("Y-m-d H:i", (strtotime(date($p_val->format('Y-m-d').' '.$dados_agendamento["hora"].':'.$dados_agendamento["minuto"])) + $duracao_playlist["total"])).'", "description": "'.$descricao.'"},';
}

} else {

$array_dias_semana = explode(",",$dados_agendamento["dias"]);

$period = new DatePeriod(
     new DateTime($_GET['start']),
     new DateInterval('P1D'),
     new DateTime($_GET['end'].' +1 day')
);

foreach ($period as $p_key => $p_val) {

$dia_semana = date('N', strtotime($p_val->format('Y-m-d')));

if (in_array($dia_semana, $array_dias_semana)) { 

if($dia_semana == "1") {
$dia_nome = $lang['lang_label_segunda'];
} elseif($dia_semana == "2") {
$dia_nome = $lang['lang_label_terca'];
} elseif($dia_semana == "3") {
$dia_nome = $lang['lang_label_quarta'];
} elseif($dia_semana == "4") {
$dia_nome = $lang['lang_label_quinta'];
} elseif($dia_semana == "5") {
$dia_nome = $lang['lang_label_sexta'];
} elseif($dia_semana == "6") {
$dia_nome = $lang['lang_label_sabado'];
} elseif($dia_semana == "7") {
$dia_nome = $lang['lang_label_domingo'];
} else {
$dia_nome = "";
}

$descricao = $lang['lang_info_gerenciador_agendamentos_info_frequencia3']." ".$dia_nome." ".$dados_agendamento["hora"].":".$dados_agendamento["minuto"]."<br>".$misturar."<br>".$finalizacao."<br>".$duracao;

$lista_agendamentos .= '{"id": "'.$dados_agendamento["codigo"].'", "title": "'.$dados_playlist["nome"].'", "className": "bg-secondary", "start": "'.$p_val->format('Y-m-d').' '.$dados_agendamento["hora"].':'.$dados_agendamento["minuto"].'", "end": "'.date("Y-m-d H:i", (strtotime(date($p_val->format('Y-m-d').' '.$dados_agendamento["hora"].':'.$dados_agendamento["minuto"])) + $duracao_playlist["total"])).'", "description": "'.$descricao.'"},';

}

}

}

unset($lista_dias);
}

$lista_agendamentos = rtrim($lista_agendamentos, ',');

echo $lista_agendamentos.']';

exit();

}

if(query_string('1') == "editar-agendamento") {

$codigo = query_string('2');
    
$dados_agendamento_selecionado = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM playlists_agendamentos where codigo = '".$codigo."'"));
$dados_playlist_selecionada = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM playlists where codigo = '".$dados_agendamento_selecionado["codigo_playlist"]."'"));

$frequencia1 = ($dados_agendamento_selecionado["frequencia"] == "1") ? 'selected="selected"' : '';
$frequencia2 = ($dados_agendamento_selecionado["frequencia"] == "2") ? 'selected="selected"' : '';
$frequencia3 = ($dados_agendamento_selecionado["frequencia"] == "3") ? 'selected="selected"' : '';

$dia_semana1 = (preg_match('/1/i',$dados_agendamento_selecionado["dias"])) ? 'checked="checked"' : '';
$dia_semana2 = (preg_match('/2/i',$dados_agendamento_selecionado["dias"])) ? 'checked="checked"' : '';
$dia_semana3 = (preg_match('/3/i',$dados_agendamento_selecionado["dias"])) ? 'checked="checked"' : '';
$dia_semana4 = (preg_match('/4/i',$dados_agendamento_selecionado["dias"])) ? 'checked="checked"' : '';
$dia_semana5 = (preg_match('/5/i',$dados_agendamento_selecionado["dias"])) ? 'checked="checked"' : '';
$dia_semana6 = (preg_match('/6/i',$dados_agendamento_selecionado["dias"])) ? 'checked="checked"' : '';
$dia_semana7 = (preg_match('/7/i',$dados_agendamento_selecionado["dias"])) ? 'checked="checked"' : '';

$shuffle = ($dados_agendamento_selecionado["shuffle"] == "sim") ? 'checked="checked"' : '';
$finalizacao1 = ($dados_agendamento_selecionado["finalizacao"] == "repetir") ? 'checked="checked"' : '';
$finalizacao2 = ($dados_agendamento_selecionado["finalizacao"] == "iniciar_playlist") ? 'checked="checked"' : '';
$lista_playlist_finalizacao_editar = ($dados_agendamento_selecionado["finalizacao"] == "iniciar_playlist") ? '' : 'display:none';

list($ano,$mes,$dia) = explode("-",$dados_agendamento_selecionado["data"]);
$data_agendamento_selecionado = $dia."/".$mes."/".$ano;

echo '<div id="quadro">
<div id="quadro-topo"><strong>'.$lang['lang_info_gerenciador_agendamentos_aba_editar_agendamento'].'</strong></div>
<div class="texto_medio" id="quadro-conteudo">
<form method="post" action="/gerenciar-agendamentos" style="padding:0px; margin:0px" id="editar_agendamento" name="editar_agendamento">
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid;">
      <tr>
        <td width="130" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">'.$lang['lang_info_gerenciador_agendamentos_playlist'].'</td>
        <td align="left">
        <select name="codigo_playlist" id="codigo_playlist" style="width:250px;">';

$query_playlists = mysqli_query($conexao,"SELECT * FROM playlists where codigo_stm = '".$dados_stm["codigo"]."' ORDER by codigo ASC");
while ($dados_playlist = mysqli_fetch_array($query_playlists)) {
                                        
  $total_videos = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM playlists_videos where codigo_playlist = '".$dados_playlist["codigo"]."'"));
  $duracao = mysqli_fetch_array(mysqli_query($conexao,"SELECT *,SUM(duracao_segundos) as total FROM playlists_videos where codigo_playlist = '".$dados_playlist["codigo"]."'"));

  $playlist_selecionada = ($dados_playlist_selecionada["codigo"] == $dados_playlist["codigo"]) ? "selected='selected'" : "";

  if($total_videos > 0) {
  echo '<option value="'.$dados_playlist["codigo"].'" '.$playlist_selecionada.'>'.$dados_playlist["nome"].' ('.gmdate("H:i:s", $duracao["total"]).')</option>';
  } else {
  echo '<option value="'.$dados_playlist["codigo"].'" disabled="disabled">'.$dados_playlist["nome"].' ('.$lang['lang_info_sem_videos'].')</option>';
  }

}
        echo '</select>
        </td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">'.$lang[ 'lang_info_gerenciador_agendamentos_frequencia' ].'</td>
        <td align="left">
        <select name="frequencia" id="frequencia" style="width:250px;" onchange="valida_opcoes_frequencia_agendamento(this.value);">
          <option value="1" '.$frequencia1.'>'.$lang[ 'lang_info_gerenciador_agendamentos_frequencia1' ].'</option>
          <option value="2" '.$frequencia2.'>'.$lang[ 'lang_info_gerenciador_agendamentos_frequencia2' ].'</option>
          <option value="3" '.$frequencia3.'>'.$lang[ 'lang_info_gerenciador_agendamentos_frequencia3' ].'</option>
        </select>
        </td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">'.$lang['lang_info_gerenciador_agendamentos_data_inicio'].'</td>
        <td align="left" class="texto_padrao_vermelho_destaque"><input name="data" type="date" id="data" value="'.$dados_agendamento_selecionado["data"].'" maxlength="10" style="width:125px;height: 20px;" />&nbsp;<input name="hora" type="time" id="hora" value="'.$dados_agendamento_selecionado["hora"].':'.$dados_agendamento_selecionado["minuto"].'" maxlength="6" style="width:112px;vertical-align: bottom;" /></td>
      </tr>
      <tr>
        <td height="40" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Días da Semana</td>
        <td align="left" valign="middle" class="texto_padrao">
        <input name="dias[]" type="checkbox" value="1" id="dias" style="vertical-align:middle" '.$dia_semana1.' />'.$lang['lang_label_segunda'].'&nbsp;
        <input name="dias[]" type="checkbox" value="2" id="dias" style="vertical-align:middle" '.$dia_semana2.' />'.$lang['lang_label_terca'].'&nbsp;
        <input name="dias[]" type="checkbox" value="3" id="dias" style="vertical-align:middle" '.$dia_semana3.' />'.$lang['lang_label_quarta'].'&nbsp;
        <input name="dias[]" type="checkbox" value="4" id="dias" style="vertical-align:middle" '.$dia_semana4.' />'.$lang['lang_label_quinta'].'<br>
        <input name="dias[]" type="checkbox" value="5" id="dias" style="vertical-align:middle" '.$dia_semana5.' />'.$lang['lang_label_sexta'].'&nbsp;
        <input name="dias[]" type="checkbox" value="6" id="dias" style="vertical-align:middle" '.$dia_semana6.' />'.$lang['lang_label_sabado'].'&nbsp;
        <input name="dias[]" type="checkbox" value="7" id="dias" style="vertical-align:middle" '.$dia_semana7.' />'.$lang['lang_label_domingo'].'</td>
      </tr>
      <tr>
        <td height="27" align="left" style="padding-left:5px;" class="texto_padrao_destaque">'.$lang['lang_info_gerenciador_playlists_botao_misturar_videos'].'</td>
        <td align="left" valign="middle" class="texto_padrao">
        <input name="shuffle" type="checkbox" value="sim" id="shuffle" style="vertical-align:middle" '.$shuffle.' />&nbsp;'.$lang['lang_label_sim'].'</td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">'.$lang['lang_info_gerenciador_agendamentos_finalizacao'].'</td>
        <td align="left" valign="middle" class="texto_padrao"><input type="radio" name="finalizacao" id="finalizacao" value="repetir" '.$finalizacao1.' onclick="lista_playlist_finalizacao_editar(\'nao\')" />
        &nbsp;Repetir V&iacute;deos(loop)
          <input type="radio" name="finalizacao" id="finalizacao" value="iniciar_playlist" onclick="lista_playlist_finalizacao_editar(\'sim\')" '.$finalizacao2.' />&nbsp;Iniciar Outra Playlist</td>
      </tr>
      <tr id="lista_playlist_finalizacao_editar" style="'.$lista_playlist_finalizacao_editar.'">
        <td height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">'.$lang['lang_info_gerenciador_agendamentos_playlist'].'</td>
        <td align="left">
        <select name="codigo_playlist_finalizacao" id="codigo_playlist_finalizacao" style="width:250px;">';

$query_playlists = mysqli_query($conexao,"SELECT * FROM playlists where codigo_stm = '".$dados_stm["codigo"]."' ORDER by codigo ASC");
while ($dados_playlist = mysqli_fetch_array($query_playlists)) {
                                      
$total_videos = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM playlists_videos where codigo_playlist = '".$dados_playlist["codigo"]."'"));
$duracao = mysqli_fetch_array(mysqli_query($conexao,"SELECT *,SUM(duracao_segundos) as total FROM playlists_videos where codigo_playlist = '".$dados_playlist["codigo"]."'"));
$playlist_selecionada = ($dados_agendamento_selecionado["codigo_playlist_finalizacao"] == $dados_playlist["codigo"]) ? "selected='selected'" : "";

if($total_videos > 0) {
echo '<option value="'.$dados_playlist["codigo"].'" '.$playlist_selecionada.'>'.$dados_playlist["nome"].' ('.gmdate("H:i:s", $duracao["total"]).')</option>';
} else {
echo '<option value="'.$dados_playlist["codigo"].'" disabled="disabled">'.$dados_playlist["nome"].' ('.$lang['lang_info_sem_videos'].')</option>';
}

}

        echo '</select>
        </td>
      </tr>
      <tr>
        <td height="40">&nbsp;</td>
        <td align="left">
          <input type="submit" class="botao" value="'.$lang['lang_botao_titulo_atualizar'].'" />
          <input name="editar" type="hidden" id="editar" value="sim" />
          <input name="codigo_agendamento" type="hidden" id="codigo_agendamento" value="'.$codigo.'" />
          </td>
      </tr>
    </table>
</form>';
    
exit();
}

if(query_string('1') == "remover") {

$codigo = query_string('2');
    
$dados_agendamento = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM playlists_agendamentos where codigo = '".$codigo."'"));

mysqli_query($conexao,"Delete From playlists_agendamentos where codigo = '".$dados_agendamento["codigo"]."'");
    
// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] .= status_acao("".$lang['lang_info_remover_agendamento_resultado_ok']."","ok");

header("Location: /gerenciar-agendamentos");
    
exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="cache-control" content="no-cache">
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
<link href="/inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<link href="inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/inc/fullcalendar.css">  
<link rel="stylesheet" href="/inc/custom-fullcalendar.advance.css">  
<link rel="stylesheet" href="/inc/custom-flatpickr.css">  
<link rel="stylesheet" href="/inc/theme-checkbox-radio.css"> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="/inc/ajax-streaming.js"></script>
<script type="text/javascript" src="/inc/javascript.js"></script>
<script type="text/javascript" src="/inc/javascript-abas.js"></script>
<script src="/inc/jquery.min.js"></script>
<script src="/inc/moment.min.js"></script>
<script src="/inc/fullcalendar.min.js"></script>
<script src="/inc/fullcalendar.<?php echo $dados_stm["idioma_painel"]; ?>.js"></script>
<script src="/inc/flatpickr.js"></script>
<script src="/inc/popper.min.js"></script>
<script src="/inc/bootstrap.min.js"></script>
<script type="text/javascript">
   window.onload = function() {
	fechar_log_sistema();
   };
function valida_opcoes_frequencia_agendamento( frequencia ) {
	
document.getElementById("data").disabled = true;

for(var cont = 0; cont < document.agendamentos.dias.length; cont++) {
document.agendamentos.dias[cont].disabled = true;
}

if(frequencia == "1") {
document.getElementById("data").disabled = false;
}

if(frequencia == "3") {

for(var cont = 0; cont < document.agendamentos.dias.length; cont++) {
document.agendamentos.dias[cont].disabled = false;
}

}

}
function validar_tipo_agendamento( tipo ) {

document.getElementById("codigo_playlist").disabled = true;
document.getElementById("servidor_relay").disabled = true;

if(tipo == "playlist"){
document.getElementById("codigo_playlist").disabled = false;
} else {
document.getElementById("servidor_relay").disabled = false;
}

}
function lista_playlist_finalizacao(opcao) {

if(opcao == "sim") {
$('#lista_playlist_finalizacao').show();
} else {
$('#lista_playlist_finalizacao').hide();
}

}
function lista_playlist_finalizacao_editar(opcao) {

if(opcao == "sim") {
$('#lista_playlist_finalizacao_editar').show();
} else {
$('#lista_playlist_finalizacao_editar').hide();
}

}
function remover_agendamento_calendario( codigo ) {

  if(window.confirm("Pt-BR: Deseja realmente remover?\nEn-US: Do you really want to remove?\nES: Realmente quieres eliminar?")) {
    window.location='/gerenciar-agendamentos/remover/'+codigo;
  }
}

// Função para carregar dados do agendamento a editar
function editar_agendamento_calendario( codigo ) {
  
  $('.popover').popover('hide');
  document.getElementById("log-sistema-conteudo").innerHTML = "<img src='/img/ajax-loader.gif' />";
  document.getElementById('log-sistema-fundo').style.display = "block";
  document.getElementById('log-sistema').style.display = "block";
  
  var http = new Ajax();
  http.open("GET", "/gerenciar-agendamentos/editar-agendamento/"+codigo , true);
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
//Calendario
$(document).ready(function() {

    var calendar = $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        locale: '<?php echo $dados_stm["idioma_painel"]; ?>',
        events: '/gerenciar-agendamentos/carregar',
        editable: true,
        eventLimit: true,
        displayEventTime: false,
        eventStartEditable: false,
        eventDurationEditable: false,
        eventMouseover: function(event, jsEvent, view) {
        },
        eventMouseout: function(event, jsEvent, view) {},
        eventClick: function(event, jsEvent, view) {
            $('.popover').remove();
            $(this).attr('id', event.id);
            $('#'+event.id).popover({
                template: '<div class="popover popover-primary" role="tooltip"><div class="arrow"></div><h3 id="ppo_'+event.id+'" class="popover-header"></h3><div class="popover-body"></div></div>',
                html: true,
                title: event.title,
                content: event.description,
                placement: 'top',
            });

            $('#'+event.id).popover('show');
            $('#ppo_'+event.id).append('<button type="button" class="btn btn-sm btn-light btn-popover-close" onclick="$(\'.popover\').popover(\'hide\')"><i class="fa fa-times-circle-o"></i></button><button type="button" class="btn btn-sm btn-danger btn-popover-close" onclick="remover_agendamento_calendario('+event.id+')"><i class="fa fa-trash-o"></i></button><button type="button" class="btn btn-sm btn-info btn-popover-close" onclick="editar_agendamento_calendario('+event.id+')"><i class="fa fa-pencil"></i></button>');}
    })
    
});
</script>
</head>

<body>
<div id="sub-conteudo">
<?php
if($_SESSION['status_acao']) {

$status_acao = stripslashes($_SESSION['status_acao']);

echo '<table width="890" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px">'.$status_acao.'</table>';

unset($_SESSION['status_acao']);
}
?>
<div id="quadro">
<div id="quadro-topo"><strong><?php echo $lang['lang_info_gerenciador_agendamentos_tab_titulo']; ?></strong></div>
<div class="texto_medio" id="quadro-conteudo" style="margin-bottom: 30px;">
  <div class="tab-pane" id="tabPane1">
   	  <div class="tab-page" id="tabPage1" style="padding-top:10px">
       	<h2 class="tab"><?php echo $lang['lang_info_gerenciador_agendamentos_aba_calendario']; ?></h2>
        <div id="calendar"></div>
      </div>
      <div class="tab-page" id="tabPage1">
        <h2 class="tab"><?php echo $lang['lang_info_gerenciador_agendamentos_aba_agendamentos']; ?></h2>
  <table width="890" border="0" align="center" cellpadding="0" cellspacing="0" style="border-left:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;; border-bottom:#D5D5D5 1px solid;" id="tab" class="sortable">
    <tr style="background:url(/admin/img/img-fundo-titulo-tabela.png) repeat-x; cursor:pointer">
      <td width="220" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;<?php echo $lang['lang_info_gerenciador_agendamentos_playlist']; ?></td>
      <td width="520" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;<?php echo $lang['lang_info_gerenciador_agendamentos_horario_agendado']; ?></td>
      <td width="150" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid;">&nbsp;<?php echo $lang['lang_info_gerenciador_agendamentos_executar_acao']; ?></td>
    </tr>
<?php
$total_agendamentos = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM playlists_agendamentos where codigo_stm = '".$dados_stm["codigo"]."' ORDER by data"));

if($total_agendamentos > 0) {

$sql = mysqli_query($conexao,"SELECT * FROM playlists_agendamentos where codigo_stm = '".$dados_stm["codigo"]."' ORDER by data");
while ($dados_agendamento = mysqli_fetch_array($sql)) {

$dados_playlist = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM playlists where codigo = '".$dados_agendamento["codigo_playlist"]."'"));

list($ano,$mes,$dia) = explode("-",$dados_agendamento["data"]);
$data = $dia."/".$mes."/".$ano;

if($dados_agendamento["frequencia"] == "1") {
$descricao = "Executar no dia ".$data." ".sprintf("%02d",$dados_agendamento["hora"]).":".sprintf("%02d",$dados_agendamento["minuto"])."";
} elseif($dados_agendamento["frequencia"] == "2") {
$descricao = "Executar diariamente às ".sprintf("%02d",$dados_agendamento["hora"]).":".sprintf("%02d",$dados_agendamento["minuto"])."";
} else {

$array_dias = explode(",",$dados_agendamento["dias"]);

foreach($array_dias as $dia) {

if($dia == "1") {
$dia_nome = "<font color='#003399'>".$lang['lang_label_segunda']."</font>";
} elseif($dia == "2") {
$dia_nome = "<font color='#FF0000'>".$lang['lang_label_terca']."</font>";
} elseif($dia == "3") {
$dia_nome = "<font color='#FF9900'>".$lang['lang_label_quarta']."</font>";
} elseif($dia == "4") {
$dia_nome = "<font color='#CC0066'>".$lang['lang_label_quinta']."</font>";
} elseif($dia == "5") {
$dia_nome = "<font color='#009900'>".$lang['lang_label_sexta']."</font>";
} elseif($dia == "6") {
$dia_nome = "<font color='#663300'>".$lang['lang_label_sabado']."</font>";
} elseif($dia == "7") {
$dia_nome = "<font color='#663399'>".$lang['lang_label_domingo']."</font>";
} else {
$dia_nome = "";
}

$lista_dias .= "".$dia_nome.", ";

}

$descricao = "Executar ".substr($lista_dias, 0, -2)." ".sprintf("%02d",$dados_agendamento["hora"]).":".sprintf("%02d",$dados_agendamento["minuto"])."";
}

$agendamento_code = code_decode($dados_agendamento["codigo"],"E");

echo "<tr>
<td height='25' align='left' scope='col' class='texto_padrao'>&nbsp;".$dados_playlist["nome"]."</td>
<td height='25' align='left' scope='col' class='texto_padrao'>&nbsp;".$descricao."</td>
<td height='25' align='left' scope='col' class='texto_padrao'>";

echo "<select style='width:100%' id='".$agendamento_code."' onchange='executar_acao_streaming(this.id,this.value);'>
  <option value='' selected='selected'>".$lang['lang_info_gerenciador_agendamentos_acao']."</option>
  <option value='ondemand-remover-agendamento'>".$lang['lang_info_gerenciador_agendamentos_acao_remover']."</option>
</select>";

echo "</td>
</tr>";

unset($lista_dias);
unset($dia_nome);
}

} else {

echo "<tr>
    <td height='23' colspan='3' align='center' class='texto_padrao'>".$lang['lang_info_sem_registros']."</td>
  </tr>";

}
?>
  </table>
  </div>
      <div class="tab-page" id="tabPage2">
       	<h2 class="tab"><?php echo $lang['lang_info_gerenciador_agendamentos_aba_cadastrar_agendamento']; ?></h2>
        <form method="post" action="/gerenciar-agendamentos" style="padding:0px; margin:0px" name="agendamentos">
    <table width="890" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid; border-left:#CCCCCC 1px solid; border-right:#CCCCCC 1px solid;">
      <tr>
        <td width="160" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_gerenciador_agendamentos_playlist']; ?></td>
        <td width="730" align="left">
        <select name="codigo_playlist" id="codigo_playlist" style="width:250px;">
        <?php
		$query_playlists = mysqli_query($conexao,"SELECT * FROM playlists where codigo_stm = '".$dados_stm["codigo"]."' ORDER by codigo ASC");
		while ($dados_playlist = mysqli_fetch_array($query_playlists)) {
	
		$total_videos = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM playlists_videos where codigo_playlist = '".$dados_playlist["codigo"]."'"));
		$duracao = mysqli_fetch_array(mysqli_query($conexao,"SELECT *,SUM(duracao_segundos) as total FROM playlists_videos where codigo_playlist = '".$dados_playlist["codigo"]."'"));

		if($total_videos > 0) {
		echo '<option value="'.$dados_playlist["codigo"].'">'.$dados_playlist["nome"].' ('.gmdate("H:i:s", $duracao["total"]).')</option>';
		} else {
		echo '<option value="'.$dados_playlist["codigo"].'" disabled="disabled">'.$dados_playlist["nome"].' ('.$lang['lang_info_sem_videos'].')</option>';
		}
		}
        ?>
        </select>        </td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Frequência</td>
        <td align="left">
        <select name="frequencia" id="frequencia" style="width:250px;" onchange="valida_opcoes_frequencia_agendamento(this.value);">
          <option value="1" selected="selected">Executar em uma data específica</option>
          <option value="2">Executar diariamente</option>
          <option value="3">Executar em dias da semana</option>
        </select>
        </td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque"><?php echo $lang['lang_info_gerenciador_agendamentos_data_inicio']; ?></td>
        <td align="left" class="texto_padrao_vermelho_destaque"><input name="data" type="date" id="data" value="" maxlength="10" style="width:125px;height: 20px;" />&nbsp;<input name="hora" type="time" id="hora" value="" maxlength="6" style="width:112px;vertical-align: bottom;" /></td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Días da Semana</td>
        <td align="left" valign="middle" class="texto_padrao">
        <input name="dias[]" type="checkbox" value="1" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_segunda']; ?>&nbsp;
        <input name="dias[]" type="checkbox" value="2" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_terca']; ?>&nbsp;
        <input name="dias[]" type="checkbox" value="3" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_quarta']; ?>&nbsp;
        <input name="dias[]" type="checkbox" value="4" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_quinta']; ?>&nbsp;
        <input name="dias[]" type="checkbox" value="5" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_sexta']; ?>&nbsp;
        <input name="dias[]" type="checkbox" value="6" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_sabado']; ?>&nbsp;
        <input name="dias[]" type="checkbox" value="7" id="dias" disabled="disabled" style="vertical-align:middle" /><?php echo $lang['lang_label_domingo']; ?></td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque"><?php echo $lang['lang_info_gerenciador_playlists_botao_misturar_videos']; ?></td>
        <td align="left" valign="middle" class="texto_padrao">
        <input name="shuffle" type="checkbox" value="sim" id="shuffle" style="vertical-align:middle" />&nbsp;<?php echo $lang['lang_label_sim']; ?></td>
      </tr>
      <tr>
        <td height="30" align="left" style="padding-left:5px;" class="texto_padrao_destaque">Finalização</td>
        <td align="left" valign="middle" class="texto_padrao"><input type="radio" name="finalizacao" id="finalizacao" value="repetir" checked="checked" onclick="lista_playlist_finalizacao('nao')" />
        &nbsp;Repetir V&iacute;deos(loop)
          <input type="radio" name="finalizacao" id="finalizacao" value="iniciar_playlist" onclick="lista_playlist_finalizacao('sim')" />&nbsp;Iniciar Outra Playlist</td>
      </tr>
      <tr id="lista_playlist_finalizacao" style="display:none">
        <td width="160" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_gerenciador_agendamentos_playlist']; ?></td>
        <td width="730" align="left">
        <select name="codigo_playlist_finalizacao" id="codigo_playlist_finalizacao" style="width:250px;">
        <?php
		$query_playlists = mysqli_query($conexao,"SELECT * FROM playlists where codigo_stm = '".$dados_stm["codigo"]."' ORDER by codigo ASC");
		while ($dados_playlist = mysqli_fetch_array($query_playlists)) {
	
		$total_videos = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM playlists_videos where codigo_playlist = '".$dados_playlist["codigo"]."'"));
		$duracao = mysqli_fetch_array(mysqli_query($conexao,"SELECT *,SUM(duracao_segundos) as total FROM playlists_videos where codigo_playlist = '".$dados_playlist["codigo"]."'"));

		if($total_videos > 0) {
		echo '<option value="'.$dados_playlist["codigo"].'">'.$dados_playlist["nome"].' ('.gmdate("H:i:s", $duracao["total"]).')</option>';
		} else {
		echo '<option value="'.$dados_playlist["codigo"].'" disabled="disabled">'.$dados_playlist["nome"].' ('.$lang['lang_info_sem_videos'].')</option>';
		}
		}
        ?>
        </select>        </td>
      </tr>
      <tr>
        <td height="40">&nbsp;</td>
        <td align="left">
          <input type="submit" class="botao" value="<?php echo $lang['lang_botao_titulo_cadastrar']; ?>" />
          <input name="cadastrar" type="hidden" id="cadastrar" value="sim" />          </td>
      </tr>
    </table>
    <table width="890" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:20px;">
      <tr>
        <td height="30" align="left" class="texto_padrao_destaque"><div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_gerenciador_agendamentos_tab_info_titulo']; ?></strong></div>
          <div class="texto_medio" id="quadro-conteudo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td height="25" class="texto_padrao_pequeno"><?php echo $lang['lang_info_gerenciador_agendamentos_instrucoes']; ?></td>
                </tr>
              </table>
          </div>
        </div></td>
      </tr>
    </table>
    </form>
      </div>
      <div class="tab-page" id="tabPage3">
       	<h2 class="tab">Logs&nbsp;<?php echo $lang['lang_info_gerenciador_agendamentos_aba_agendamentos']; ?>&nbsp;<img src="/admin/img/icones/img-icone-fechar.png" onclick="document.form_remover_logs.submit();" style="cursor:pointer" title="Reset Logs" width="12" height="12" align="absmiddle" /></h2>
        <form action="/gerenciar-agendamentos" method="post" name="form_remover_logs"><input name="remover_logs" type="hidden" value="sim" /></form>
        <table width="890" border="0" align="center" cellpadding="0" cellspacing="0" style="border-left:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid; border-bottom:#D5D5D5 1px solid;" id="tab2" class="sortable">
          <tr style="background:url(/img/img-fundo-titulo-tabela.png) repeat-x; cursor:pointer">
            <td width="200" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;Data de Execução</td>
            <td width="690" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid;">&nbsp;<?php echo $lang['lang_info_gerenciador_agendamentos_playlist']; ?></td>
          </tr>
<?php
$total_logs_agendamentos = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM playlists_agendamentos_logs where codigo_stm = '".$dados_stm["codigo"]."' ORDER by data"));

if($total_logs_agendamentos > 0) {

$sql = mysqli_query($conexao,"SELECT * FROM playlists_agendamentos_logs WHERE codigo_stm = '".$dados_stm["codigo"]."' ORDER by data DESC LIMIT 100");
while ($dados_log_agendamento = mysqli_fetch_array($sql)) {

echo "<tr>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>&nbsp;".formatar_data($dados_stm["formato_data"], $dados_log_agendamento["data"], $dados_stm["timezone"])."</td>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>&nbsp;".$dados_log_agendamento["playlist"]."</td>
</tr>";

}

} else {

echo "<tr>
    <td height='23' colspan='2' align='center' class='texto_padrao'>".$lang['lang_info_sem_registros']."</td>
  </tr>";

}
?>
        </table>
      </div>
    </div>
    </div>
    </div>
</div>
<!-- Início div log do sistema -->
<div id="log-sistema-fundo"></div>
<div id="log-sistema">
<div id="log-sistema-botao"><img src="/admin/img/icones/img-icone-fechar.png" onclick="document.getElementById('log-sistema-fundo').style.display = 'none';document.getElementById('log-sistema').style.display = 'none';" style="cursor:pointer" title="<?php echo $lang['lang_titulo_fechar']; ?>" /></div>
<div id="log-sistema-conteudo"></div>
</div>
<!-- Fim div log do sistema -->
</body>
</html>
