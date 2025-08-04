<?php
require_once("admin/inc/protecao-final.php");
require_once("admin/inc/classe.ssh.php");

@mysqli_query($conexao,"ALTER TABLE `streamings` ADD `watermark_posicao` VARCHAR(255) NOT NULL;");

$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));
$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));

if(isset($_FILES["logo"]) || isset($_POST["posicao"])) {

// Faz upload da logo
// Conexão SSH
	$ssh = new SSH();
	$ssh->conectar($dados_servidor["ip"],$dados_servidor["porta_ssh"]);
	$ssh->autenticar("root",code_decode($dados_servidor["senha"],"D"));
	if(isset($_POST["remover_watermark"])){
		$ssh->executar("rm -fv /home/streaming/".$dados_stm["login"]."/logo-watermark.png");
		mysqli_query($conexao,"UPDATE streamings SET watermark_posicao = '' WHERE login = '".$dados_stm["login"]."'");
	}else{
		$ssh->executar("mkdir -v /home/streaming/".$dados_stm["login"]."");
		$ssh->enviar_arquivo($_FILES["logo"]["tmp_name"],"/home/streaming/".$dados_stm["login"]."/logo-watermark.png",0777);
		mysqli_query($conexao,"UPDATE streamings SET watermark_posicao = '".$_POST["posicao"]."' WHERE login = '".$dados_stm["login"]."'");
	}

// Cria o sessão do status das ações executadas e redireciona.
	$_SESSION["status_acao"] = status_acao("Configuração alterada com sucesso.","ok");

	header("Location: /configuracoes-watermark-player");
	exit();

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Streaming</title>
	<meta http-equiv="cache-control" content="no-cache">
	<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
	<link href="inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="inc/javascript.js"></script>
	<script type="text/javascript" src="inc/javascript-abas.js"></script>
	<script type="text/javascript">
		window.onload = function() {
			fechar_log_sistema();
		};
	</script>
</head>

<body>
	<div id="sub-conteudo-pequeno">
		<?php
		if($_SESSION['status_acao']) {

			$status_acao = stripslashes($_SESSION['status_acao']);

			echo '<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px">'.$status_acao.'</table>';

			unset($_SESSION['status_acao']);
		}
		?>
		<form method="post" action="/configuracoes-watermark-player" style="padding:0px; margin:0px" enctype="multipart/form-data">
			<div id="quadro">
				<div class="texto_medio" id="quadro-conteudo">
					
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
						<tr>
							<td>
								<div class="tab-pane" id="tabPane1">
									<div class="tab-page" id="tabPage1">
										<h2 class="tab"><?php echo $lang['lang_info_config_painel_aba_configuracoes']; ?> Watermark</h2>
										<table width="690" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid; border-left:#CCCCCC 1px solid; border-right:#CCCCCC 1px solid;">
											<tr>
												<td width="160" height="35" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Logo Atual</td>
												<td width="730" align="left" class="texto_padrao_pequeno">
													<img src="https://<?php echo $dados_servidor['nome'];?>.<?php echo $dados_config['dominio_padrao'];?>:1443/watermark.php?login=<?php echo $dados_stm["login"];?>">
												</td>
											</tr>
											<tr>
												<td width="160" height="35" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Logo</td>
												<td width="730" align="left" class="texto_status_streaming_offline">
											    <input type="file" name="logo" class="input" style="width: 100%" /><br />
													Somente PNG fundo transparente
												MAX 300x300 pixels</td>
										  </tr>
											<tr>
												<td width="160" height="80" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Posição</td>
										  <td width="730" align="left">
													<table style="background-color:#FFFFFF; border:#CCCCCC 1px solid;">
														<tbody><tr>
															<td width="40" align="center">
																<input type="radio" name="posicao" value="left,top" <?php if($dados_stm['watermark_posicao'] == 'left,top'){echo 'checked';}?>>
															</td>
															<td width="40" align="center" class="text-center">
																<input type="radio" name="posicao" <?php if($dados_stm['watermark_posicao'] == 'hcenter,top'){echo 'checked';}?> disabled>
															</td>
													  <td width="40" align="center" class="text-right">
																<input type="radio" name="posicao" value="right,top" <?php if($dados_stm['watermark_posicao'] == 'right,top'){echo 'checked';}?>>
															</td>
														</tr>
														<tr>
															<td width="40" align="center">
																<input type="radio" name="posicao" <?php if($dados_stm['watermark_posicao'] == 'left,vcenter'){echo 'checked';}?> disabled>
															</td>
															<td width="40" align="center" class="text-center">
																<input type="radio" name="posicao" <?php if($dados_stm['watermark_posicao'] == 'hcenter,vcenter'){echo 'checked';}?> disabled>
															</td>
															<td width="40" align="center" class="text-right">
																<input type="radio" name="posicao" <?php if($dados_stm['watermark_posicao'] == 'right,vcenter'){echo 'checked';}?> disabled>
															</td>
														</tr>
														<tr>
															<td width="40" align="center">
																<input type="radio" name="posicao" value="left,bottom" <?php if($dados_stm['watermark_posicao'] == 'left,bottom'){echo 'checked';}?>>
															</td>
															<td width="40" align="center" class="text-center">
																<input type="radio" name="posicao" <?php if($dados_stm['watermark_posicao'] == 'hcenter,bottom'){echo 'checked';}?> disabled>
															</td>
													  <td width="40" align="center" class="text-right">
																<input type="radio" name="posicao" value="right,bottom" <?php if($dados_stm['watermark_posicao'] == 'right,bottom'){echo 'checked';}?>>
															</td>
														</tr>
													</tbody></table>
												</td>
											</tr>
											<tr>
												<td height="40">&nbsp;</td>
												<td align="left">
													<input type="submit" class="botao" value="<?php echo $lang['lang_botao_titulo_configurar']; ?>" />
													<input type="submit" class="botao" name="remover_watermark" value="Remover" /> </td>
												</tr>
											</table>
										</div>
									</div></td>
								</tr>
							</table>
						</div>
					</div>
				</form>
			</div>
			<!-- Início div log do sistema -->
			<div id="log-sistema-fundo"></div>
			<div id="log-sistema">
				<div id="log-sistema-botao"><img src="img/icones/img-icone-fechar.png" onclick="document.getElementById('log-sistema-fundo').style.display = 'none';document.getElementById('log-sistema').style.display = 'none';" style="cursor:pointer" title="<?php echo $lang['lang_titulo_fechar']; ?>" /></div>
				<div id="log-sistema-conteudo"></div>
			</div>
			<!-- Fim div log do sistema -->
		</body>
		</html>