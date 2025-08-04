<?php
require_once("admin/inc/protecao-final.php");
var_dump("teste");
$estatistica = query_string('1');
$mes = query_string('2');
$ano = query_string('3');

$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));
$total_espectadores_db = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM estatisticas WHERE codigo_stm = '".$dados_stm["codigo"]."'"));
$total_espectadores_unicos_db = mysqli_num_rows(mysqli_query($conexao,"SELECT DISTINCT ip FROM estatisticas WHERE codigo_stm = '".$dados_stm["codigo"]."'"));
$tempo_conectado = mysqli_fetch_array(mysqli_query($conexao,"SELECT count(tempo_conectado) as total_registros, SUM(tempo_conectado) as total_tempo FROM estatisticas WHERE codigo_stm = '".$dados_stm["codigo"]."'"));

if($tempo_conectado["total_registros"]) {
$average_espectadores = $tempo_conectado["total_tempo"]/$tempo_conectado["total_registros"];
} else {
$average_espectadores = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Estat�sticas do Streaming</title>
<meta http-equiv="cache-control" content="no-cache">
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
<link href="/inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<link href="inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/inc/javascript.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">
   window.onload = function() {
	fechar_log_sistema();
   };
</script>
<style>
@media print {
    .no-print {display: none;}
}
.rotate_45 {
  /* Safari */
  -webkit-transform: rotate(-45deg);

  /* Firefox */
  -moz-transform: rotate(-45deg);

  /* IE */
  -ms-transform: rotate(-45deg);

  /* Opera */
  -o-transform: rotate(-45deg);

  /* Internet Explorer */
  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);

}
.rotate_90 {
  /* Safari */
  -webkit-transform: rotate(-90deg);

  /* Firefox */
  -moz-transform: rotate(-90deg);

  /* IE */
  -ms-transform: rotate(-90deg);

  /* Opera */
  -o-transform: rotate(-90deg);

  /* Internet Explorer */
  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);

}
</style>
</head>

<body>
<div id="sub-conteudo">
  <table width="700" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px; background-color:#FFFF66; border:#DFDF00 1px solid" class="no-print">
  <tr>
    <td width="30" height="25" align="center" scope="col"><img src="/admin/img/icones/atencao.png" width="16" height="16" /></td>
    <td width="670" align="left" class="texto_log_sistema_alerta" scope="col"><?php echo $lang['lang_info_estatisticas_info']; ?></td>
  </tr>
</table>
	<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;">
	  <tr>
        <td height="30" align="left" class="texto_padrao_destaque"><div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_estatisticas_registros_tab_titulo']; ?></strong></div>
          <div class="texto_medio" id="quadro-conteudo">
              <table width="788" border="0" cellspacing="0" cellpadding="0" align="center" id="tabela_info_trans" style="display:block">
                <tr>
                  <td width="394" height="70" align="center" class="texto_padrao_pequeno"><img src="/img/icones/img-icone-espectadores.png" width="48" height="48" /></td>
                  <td width="394" align="center" class="texto_padrao_pequeno"><img src="/img/icones/img-icone-agendamento.png" width="48" height="48" /></td>
                </tr>
                <tr>
                  <td height="30" align="center" class="texto_padrao_pequeno"><span class="texto_padrao_destaque"><?php echo $total_espectadores_db; echo "cai aqui" ;?></span><br /><?php echo $lang['lang_info_estatisticas_total_espectadores']; ?></td>
                  <td align="center" class="texto_padrao_pequeno"><span class="texto_padrao_destaque"><?php echo seconds2time($average_espectadores); echo "cai aqui 2"; ?></span><br /><?php echo $lang['lang_info_estatisticas_total_espectadores_tempo_conectados']; ?></td>
                </tr>
              </table>
          </div>
        </div></td>
      </tr>
    </table>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;" class="no-print">
	  <tr>
        <td height="30" align="left" class="texto_padrao_destaque"><div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_estatisticas_grafico_periodo_tab_titulo']; ?></strong></div>
          <div class="texto_medio" id="quadro-conteudo">
              <table width="788" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td colspan="4" align="center"><select name="estatistica" class="input" id="estatistica" style="width:220px;height:40px" onchange="tipo_estatistica(this.value);">
                  <optgroup label="<?php echo $lang['lang_info_estatisticas_periodo_graficos']; ?>">
          <option value="1"><?php echo $lang['lang_info_estatisticas_estatistica_espectadores']; ?></option>
          <option value="2"><?php echo $lang['lang_info_estatisticas_estatistica_espectadores']; ?> Ano</option>
          <option value="3"><?php echo $lang['lang_info_estatisticas_estatistica_tempo_conectado']; ?></option>
          <option value="4"><?php echo $lang['lang_info_estatisticas_estatistica_paises']; ?></option>
          <option value="5"><?php echo $lang['lang_info_estatisticas_estatistica_players']; ?></option>
          <option value="6"><?php echo $lang['lang_info_estatisticas_estatistica_espectadores_hora']; ?></option>
          </optgroup>
        </select>&nbsp;<select name="mes" class="input" id="mes" style="width:220px;height:40px">
                  <optgroup label="<?php echo $lang['lang_info_estatisticas_periodo']; ?>">
          <option value="01" <?php if(date("m") == '01') { echo ' selected="selected"'; } ?>><?php echo $lang['lang_info_estatisticas_periodo_01']; ?></option>
          <option value="02" <?php if(date("m") == '02') { echo ' selected="selected"'; } ?>><?php echo $lang['lang_info_estatisticas_periodo_02']; ?></option>
          <option value="03" <?php if(date("m") == '03') { echo ' selected="selected"'; } ?>><?php echo $lang['lang_info_estatisticas_periodo_03']; ?></option>
          <option value="04" <?php if(date("m") == '04') { echo ' selected="selected"'; } ?>><?php echo $lang['lang_info_estatisticas_periodo_04']; ?></option>
          <option value="05" <?php if(date("m") == '05') { echo ' selected="selected"'; } ?>><?php echo $lang['lang_info_estatisticas_periodo_05']; ?></option>
          <option value="06" <?php if(date("m") == '06') { echo ' selected="selected"'; } ?>><?php echo $lang['lang_info_estatisticas_periodo_06']; ?></option>
          <option value="07" <?php if(date("m") == '07') { echo ' selected="selected"'; } ?>><?php echo $lang['lang_info_estatisticas_periodo_07']; ?></option>
          <option value="08" <?php if(date("m") == '08') { echo ' selected="selected"'; } ?>><?php echo $lang['lang_info_estatisticas_periodo_08']; ?></option>
          <option value="09" <?php if(date("m") == '09') { echo ' selected="selected"'; } ?>><?php echo $lang['lang_info_estatisticas_periodo_09']; ?></option>
          <option value="10" <?php if(date("m") == '10') { echo ' selected="selected"'; } ?>><?php echo $lang['lang_info_estatisticas_periodo_10']; ?></option>
          <option value="11" <?php if(date("m") == '11') { echo ' selected="selected"'; } ?>><?php echo $lang['lang_info_estatisticas_periodo_11']; ?></option>
          <option value="12" <?php if(date("m") == '12') { echo ' selected="selected"'; } ?>><?php echo $lang['lang_info_estatisticas_periodo_12']; ?></option>
          </optgroup>
        </select>&nbsp;<select name="ano" class="input" id="ano" style="width:150px;height:40px">
                  <optgroup label="<?php echo $lang['lang_info_estatisticas_periodo']; ?>">
			<?php
				$ano_inicial = date("Y")-1;
				$ano_final = date("Y")+1;
				$qtd = $ano_final-$ano_inicial;
					for($i=0; $i <= $qtd; $i++) {
							if(sprintf("%02s",$ano_inicial+$i) == date("Y")) {
								echo "<option value=\"".sprintf("%02s",$ano_inicial+$i)."\" selected=\"selected\">".sprintf("%02s",$ano_inicial+$i)."</option>\n";
							} else {
								echo "<option value=\"".sprintf("%02s",$ano_inicial+$i)."\">".sprintf("%02s",$ano_inicial+$i)."</option>\n";
							}
					}
			?>
            </optgroup>
        </select>&nbsp;&nbsp;
        <input type="button" class="botao" style="height:40px" value="<?php echo $lang['lang_botao_titulo_visualizar']; ?>" onclick="window.location = '/estatisticas/'+document.getElementById('estatistica').value+'/'+document.getElementById('mes').value+'/'+document.getElementById('ano').value+'';" /></td>
                </tr>
              </table>
          </div>
        </div></td>
      </tr>
</table>
<?php if($estatistica == "1") { ?>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;">
	  <tr>
        <td height="30" align="left" class="texto_padrao_destaque"><div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_estatisticas_grafico_espectadores_dia_tab_titulo']; ?></strong></div>
          <div class="texto_medio" id="quadro-conteudo">
              <table width="788" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td colspan="4" align="center">
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_espectadores_dia',
                type: 'area'
            },
            title: {
                text: '<?php echo $lang['lang_info_estatisticas_total_espectadores']; ?>'
            },
			subtitle: {
                text: '<?php echo $lang['lang_info_estatisticas_periodo_'.$mes.'']; ?> <?php echo $ano; ?>'
            },
            xAxis: {
                categories: [
				<?php
				$array_dias_meses = array("01" => "31", "02" => "29", "03" => "31", "04" => "30", "05" => "31", "06" => "30", "07" => "31", "08" => "31", "09" => "30", "10" => "31", "11" => "30", "12" => "31");
				
				for($i=1;$i<=$array_dias_meses[$mes];$i++){
				
				$dias .= sprintf("%02s",$i).",";

				}
				
				echo substr($dias, 0, -1);			
				?>				
				],
                tickmarkPlacement: 'on',
                title: {
                    enabled: false
                }
            },
            yAxis: {
                title: {
                    text: '<?php echo $lang['lang_info_estatisticas_total_espectadores']; ?>'
                },
                labels: {
                    formatter: function() {
                        return this.value;
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x +'/<?php echo $mes;?>/<?php echo $ano;?>: '+ Highcharts.numberFormat(this.y, 0, ',') +' <?php echo $lang['lang_info_estatisticas_legenda_espectadores']; ?>';
                }
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
					cursor: 'pointer',
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666',
						enabled: false,
                    	symbol: 'circle',
                    	radius: 2,
                    	states: {
                        	hover: {
                            enabled: true
                        	}
						}
                    }
                }
            },
            series: [{
                name: '<?php echo $lang['lang_info_estatisticas_info_stats_espectadores_dia']; ?>',
                data: [
				<?php
				
				for($i=1;$i<=$array_dias_meses["".$mes.""];$i++){
				
				$dia = sprintf("%02s",$i);
				$data = $ano."-".$mes."-".$dia;
				$total_espectadores_mes = mysqli_num_rows(mysqli_query($conexao,"SELECT codigo_stm,data FROM estatisticas where codigo_stm = '".$dados_stm["codigo"]."' AND data = '".$data."'"));
				echo $total_espectadores_mes.",";
				echo "\n";
				
				}
				?>
				]
            }]
        });
    });
    
});
</script>
<center><div id="grafico_espectadores_dia" style="min-width: 600px; height: 300px; margin: 0 auto"></div></center>
                  </td>
                </tr>
              </table>
          </div>
        </div></td>
      </tr>
  </table>
<?php } else if($estatistica == "2") { ?>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;">
	  <tr>
        <td height="30" align="left" class="texto_padrao_destaque"><div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_estatisticas_grafico_espectadores_ano_tab_titulo']; ?></strong></div>
          <div class="texto_medio" id="quadro-conteudo">
              <table width="788" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td colspan="4" align="center">
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_espectadores_ano',
                type: 'area'
            },
            title: {
                text: '<?php echo $lang['lang_info_estatisticas_info_stats_espectadores_ano']; ?>'
            },
			subtitle: {
                text: '<?php echo $ano; ?>'
            },
            xAxis: {
                categories: [
				<?php
				echo "'".$lang['lang_info_estatisticas_periodo_01']."','".$lang['lang_info_estatisticas_periodo_02']."','".$lang['lang_info_estatisticas_periodo_03']."','".$lang['lang_info_estatisticas_periodo_04']."','".$lang['lang_info_estatisticas_periodo_05']."','".$lang['lang_info_estatisticas_periodo_06']."','".$lang['lang_info_estatisticas_periodo_07']."','".$lang['lang_info_estatisticas_periodo_08']."','".$lang['lang_info_estatisticas_periodo_09']."','".$lang['lang_info_estatisticas_periodo_10']."','".$lang['lang_info_estatisticas_periodo_11']."','".$lang['lang_info_estatisticas_periodo_12']."'";
				?>				
				],
                tickmarkPlacement: 'on',
                title: {
                    enabled: false
                }
            },
            yAxis: {
                title: {
                    text: '<?php echo $lang['lang_info_estatisticas_total_espectadores']; ?>'
                },
                labels: {
                    formatter: function() {
                        return this.value;
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x +'/<?php echo $ano;?>: '+ Highcharts.numberFormat(this.y, 0, ',') +' <?php echo $lang['lang_info_estatisticas_legenda_espectadores']; ?>';
                }
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
					cursor: 'pointer',
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666',
						enabled: false,
                    	symbol: 'circle',
                    	radius: 2,
                    	states: {
                        	hover: {
                            enabled: true
                        	}
						}
                    }
                }
            },
            series: [{
                name: '<?php echo $lang['lang_info_estatisticas_total_espectadores']; ?>',
                data: [
				<?php
				
				$array_meses_ano = array("01","02","03","04","05","06","07","08","09","10","11","12");
				
				foreach($array_meses_ano as $mes_ano) {
				
				$mes_ano = sprintf("%02s",$mes_ano);
				
				$total_espectadores_ano = mysqli_num_rows(mysqli_query($conexao,"SELECT codigo_stm,data FROM estatisticas where codigo_stm = '".$dados_stm["codigo"]."' AND YEAR(data) = '".$ano."' AND MONTH(data) = '".$mes_ano."'"));
				
				$total_espectadores_meses .= $total_espectadores_ano.",";
				
				}
				echo substr($total_espectadores_meses, 0, -1);	
				?>
				]
            }]
        });
    });
    
});
</script>
<center><div id="grafico_espectadores_ano" style="min-width: 600px; height: 300px; margin: 0 auto"></div></center>
                  </td>
                </tr>
              </table>
          </div>
        </div></td>
      </tr>
  </table>
<?php } else if($estatistica == "3") { ?>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;">
	  <tr>
        <td height="30" align="left" class="texto_padrao_destaque"><div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_estatisticas_grafico_espectadores_tempo_conectado_tab_titulo']; ?></strong></div>
          <div class="texto_medio" id="quadro-conteudo">
              <table width="788" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td colspan="4" align="center">
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_espectadores_tempo_conectado',
                type: 'area'
            },
            title: {
                text: '<?php echo $lang['lang_info_estatisticas_grafico_espectadores_tempo_conectado_tab_titulo']; ?>'
            },
			subtitle: {
                text: '<?php echo $lang['lang_info_estatisticas_periodo_'.$mes.'']; ?> <?php echo $ano;?>'
            },
            xAxis: {
                categories: [
				<?php
				$array_dias_meses = array("01" => "31", "02" => "28", "03" => "31", "04" => "30", "05" => "31", "06" => "30", "07" => "31", "08" => "31", "09" => "30", "10" => "31", "11" => "30", "12" => "31");
				
				for($i=1;$i<=$array_dias_meses[$mes];$i++){
				
				$dias .= sprintf("%02s",$i).",";

				}
				
				echo substr($dias, 0, -1);			
				?>				
				],
                tickmarkPlacement: 'on',
                title: {
                    enabled: false
                }
            },
            yAxis: {
                title: {
                    text: '<?php echo $lang['lang_info_estatisticas_info_stats_espectadores_tempo_conectado_minutos']; ?>'
                },
                labels: {
                    formatter: function() {
                        return this.value;
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x +'/<?php echo $mes;?>/<?php echo $ano;?>: '+ Highcharts.numberFormat(this.y, 0, ',') +' <?php echo $lang['lang_info_estatisticas_legenda_minutos']; ?>';
                }
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
					cursor: 'pointer',
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666',
						enabled: false,
                    	symbol: 'circle',
                    	radius: 2,
                    	states: {
                        	hover: {
                            enabled: true
                        	}
						}
                    }
                }
            },
            series: [{
                name: '<?php echo $lang['lang_info_estatisticas_info_stats_espectadores_tempo_conectado_minutos']; ?>',
                data: [
				<?php
				for($i=1;$i<=$array_dias_meses["".$mes.""];$i++){
				
				$dia = sprintf("%02s",$i);
				
				$data = $ano."-".$mes."-".$dia;

				
				$tempo_conectado_stat = mysqli_fetch_array(mysqli_query($conexao,"SELECT count(tempo_conectado) as total_registros, SUM(tempo_conectado) as total_tempo FROM estatisticas WHERE codigo_stm = '".$dados_stm["codigo"]."' AND data = '".$data."'"));
				
				$media = ($tempo_conectado_stat["total_registros"] > 0) ? $tempo_conectado_stat["total_tempo"]/$tempo_conectado_stat["total_registros"] : '0';
				echo date('i',mktime(0,0,$media,15,03,2013)).",";
				echo "\n";
				
				}
				?>
				]
            }]
        });
    });
    
});

</script>
<center><div id="grafico_espectadores_tempo_conectado" style="min-width: 600px; height: 300px; margin: 0 auto"></div></center>
                  </td>
                </tr>
              </table>
          </div>
        </div></td>
      </tr>
  </table>
<?php } else if($estatistica == "4") { ?>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;">
	  <tr>
        <td height="30" align="left" class="texto_padrao_destaque"><div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_estatisticas_grafico_localidade_tab_titulo']; ?></strong></div>
          <div class="texto_medio" id="quadro-conteudo">
              <table width="788" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td colspan="4" align="center">
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_paises',
                type: 'pie',
            		options3d: {
                		enabled: true,
                		alpha: 45,
                		beta: 0
            		}
            },
            title: {
                text: '<?php echo $lang['lang_info_estatisticas_info_stats_paises']; ?>'
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage, 0, ',') +' %';
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
					depth: 35,
                    dataLabels: {
                        enabled: true
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: '<?php echo $lang['lang_info_estatisticas_info_stats_paises']; ?>',
                data: [
				
				<?php
				
				$sql_paises = mysqli_query($conexao,"SELECT distinct(pais) as pais, count(pais) as total FROM estatisticas where codigo_stm = '".$dados_stm["codigo"]."' GROUP by pais ORDER by total DESC");
				while ($dados_pais_ip = mysqli_fetch_array($sql_paises)) {

				if($dados_pais_ip["total"] >= 1) {
				
				echo "['".$dados_pais_ip["pais"]."', ".$dados_pais_ip["total"]."],";
				echo "\n";

				}

				}
				
				?>

                ]
            }]
        });
    });
    
});
</script>
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_estados',
                type: 'pie',
            		options3d: {
                		enabled: true,
                		alpha: 45,
                		beta: 0
            		}
            },
            title: {
                text: '<?php echo $lang['lang_info_estatisticas_info_stats_estados']; ?>'
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage, 0, ',') +' %';
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
					depth: 35,
                    dataLabels: {
                        enabled: true
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: '<?php echo $lang['lang_info_estatisticas_info_stats_estados']; ?>',
                data: [
				
				<?php
				
				$sql_estados = mysqli_query($conexao,"SELECT distinct(estado) as estado, count(estado) as total FROM estatisticas where codigo_stm = '".$dados_stm["codigo"]."' AND estado != '' GROUP by estado ORDER by total DESC");
				while ($dados_stat_estado = mysqli_fetch_array($sql_estados)) {

				if($dados_stat_estado["total"] >= 1) {
				
				echo "['".$dados_stat_estado["estado"]."', ".$dados_stat_estado["total"]."],";
				echo "\n";

				}

				}
				
				?>

                ]
            }]
        });
    });
    
});
</script>
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_cidades',
                type: 'pie',
            		options3d: {
                		enabled: true,
                		alpha: 45,
                		beta: 0
            		}
            },
            title: {
                text: '<?php echo $lang['lang_info_estatisticas_info_stats_cidades']; ?>'
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage, 0, ',') +' %';
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
					depth: 35,
                    dataLabels: {
                        enabled: true
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                data: [
				
				<?php
				
				$sql_cidades = mysqli_query($conexao,"SELECT distinct(cidade) as cidade, count(cidade) as total FROM estatisticas where codigo_stm = '".$dados_stm["codigo"]."' AND cidade != '' GROUP by cidade ORDER by total DESC");
				while ($dados_stat_cidade = mysqli_fetch_array($sql_cidades)) {

				if($dados_stat_cidade["total"] >= 1) {
				
				echo "['".$dados_stat_cidade["cidade"]."', ".$dados_stat_cidade["total"]."],";
				echo "\n";

				}

				}
				
				?>

                ]
            }]
        });
    });
    
});
</script>
<center><div id="grafico_paises" style="min-width: 600px; height: 300px; margin: 0 auto"></div></center>
<br /><br />
<center><div id="grafico_estados" style="min-width: 600px; height: 300px; margin: 0 auto"></div></center>
<br /><br />
<center><div id="grafico_cidades" style="min-width: 600px; height: 300px; margin: 0 auto"></div></center>
                  </td>
                </tr>
              </table>

          </div>
        </div></td>
      </tr>
  </table>
<?php } else if($estatistica == "5") { ?>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;">
	  <tr>
        <td height="30" align="left" class="texto_padrao_destaque"><div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_estatisticas_grafico_players_tab_titulo']; ?></strong></div>
          <div class="texto_medio" id="quadro-conteudo">
              <table width="788" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td colspan="4" align="center">
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_players',
                type: 'pie',
            		options3d: {
                		enabled: true,
                		alpha: 45,
                		beta: 0
            		}
            },
            title: {
                text: '<?php echo $lang['lang_info_estatisticas_info_stats_players']; ?>'
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage, 0, ',') +' %';
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
					depth: 35,
                    dataLabels: {
                        enabled: true
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                data: [
				
				<?php
				
				$sql_players = mysqli_query($conexao,"SELECT distinct(player) as player, count(player) as total FROM estatisticas where codigo_stm = '".$dados_stm["codigo"]."' AND player != '' GROUP by player ORDER by total DESC");
				while ($dados_player = mysqli_fetch_array($sql_players)) {

				if($dados_player["total"] > 0) {
				
				echo "['".$dados_player["player"]."', ".$dados_player["total"]."],";
				echo "\n";

				}

				}
				
				?>

                ]
            }]
        });
    });
    
});
</script>
<center><div id="grafico_players" style="min-width: 600px; height: 300px; margin: 0 auto"></div></center>
                  </td>
                </tr>
              </table>
          </div>
        </div></td>
      </tr>
  </table>
<?php } else if($estatistica == "6") { ?>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px;">
	  <tr>
        <td height="30" align="left" class="texto_padrao_destaque"><div id="quadro">
            <div id="quadro-topo"> <strong><?php echo $lang['lang_info_estatisticas_grafico_espectadores_hora_tab_titulo']; ?></strong></div>
          <div class="texto_medio" id="quadro-conteudo">
              <table width="788" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                  <td colspan="4" align="center">
<script type="text/javascript">

$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico_espectadores_tempo_conectado_hora',
                type: 'area'
            },
            title: {
                text: '<?php echo $lang['lang_info_estatisticas_grafico_espectadores_hora_tab_titulo']; // espectadores Conectados por Hora ?>'
            },
			subtitle: {
                text: '<?php echo $lang['lang_info_estatisticas_periodo_'.$mes.'']; ?> <?php echo $ano;?>'
            },
            xAxis: {
                categories: ['00:00-00:59','01:00-01:59','02:00-02:59','03:00-03:59','04:00-04:59','05:00-05:59','06:00-06:59','07:00-07:59','08:00-08:59','09:00-09:59','10:00-10:59','11:00-11:59','12:00-12:59','13:00-13:59','14:00-14:59','15:00-15:59','16:00-16:59','17:00-17:59','18:00-18:59','19:00-19:59','20:00-20:59','21:00-21:59','22:00-22:59','23:00-23:59'],
                tickmarkPlacement: 'on',
            },
            yAxis: {
                title: {
                    text: '<?php echo $lang['lang_info_estatisticas_total_espectadores']; // Total de espectadores ?>'
                },
                labels: {
                    formatter: function() {
                        return this.value;
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    return ''+this.x+': '+ Highcharts.numberFormat(this.y, 0, ',') +' <?php echo $lang['lang_info_estatisticas_legenda_espectadores']; // ouvinte(s) ?>';
                }
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
					cursor: 'pointer',
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666',
						enabled: false,
                    	symbol: 'circle',
                    	radius: 2,
                    	states: {
                        	hover: {
                            enabled: true
                        	}
						}
                    }
                }
            },
            series: [{
                name: '<?php echo $lang['lang_info_estatisticas_info_stats_espectadores_ano']; ?>',
                data: [<?php
				
				for($i=0;$i<=23;$i++){
				
				$hora = sprintf("%02s",$i);
				
				$total_espectadores = mysqli_num_rows(mysqli_query($conexao,"SELECT codigo_stm,data,hora FROM estatisticas where codigo_stm = '".$dados_stm["codigo"]."' AND YEAR(data) = '".$ano."' AND MONTH(data) = '".$mes."' AND HOUR(hora) = '".$hora."'"));
				
				$array_total_espectadores .= $total_espectadores.",";
				
				}
				echo substr($array_total_espectadores, 0, -1);	
				
				unset($array_total_espectadores);
				unset($total_espectadores);
				?>]
            }]
        });
    });
    
});

</script>
<center><div id="grafico_espectadores_tempo_conectado_hora" style="min-width: 600px; height: 300px; margin: 0 auto"></div></center>
                  </td>
                </tr>
              </table>
          </div>
        </div></td>
      </tr>
  </table>
<?php } ?>
<br />
<br />
<br />
<br />
</div>
</body>
</html>