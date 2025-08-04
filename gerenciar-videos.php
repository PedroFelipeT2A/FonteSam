<?php
ini_set("memory_limit", "128M");
ini_set("max_execution_time", 300);

require_once("admin/inc/protecao-final.php");
require_once("admin/inc/classe.ftp.php");

$login_code = code_decode($_SESSION["login_logado"],"E");

$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));
$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="cache-control" content="no-cache">
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
    <link href="/inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
    <link href="inc/estilo-streaming.css" rel="stylesheet" type="text/css" />

    <meta name="viewport" content="minimum-scale=1, initial-scale=1, width=device-width, shrink-to-fit=no"/>
    <link rel="preconnect" href="https://fonts.gstatic.com/"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500"/>
    <style>
      body {
        font-family: Roboto,Helvetica Neue For Number,-apple-system,BlinkMacSystemFont,Segoe UI,PingFang SC,Hiragino Sans GB,Microsoft YaHei,Helvetica Neue,Helvetica,Arial,sans-serif;
      }
    </style>
    <meta charSet="utf-8" class="next-head"/>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1" class="next-head"/>
    <link rel="preload" href="file-manager-ui/_next/static/v1/pages/index.js" as="script" crossorigin="anonymous"/>
    <link rel="preload" href="file-manager-ui/_next/static/v1/pages/_app.js" as="script" crossorigin="anonymous"/>
    <link rel="preload" href="file-manager-ui/_next/static/runtime/webpack-c338d2af2513c103e40f.js" as="script" crossorigin="anonymous"/>
    <link rel="preload" href="file-manager-ui/_next/static/chunks/commons.67a9413127497509dab3.js" as="script" crossorigin="anonymous"/>
    <link rel="preload" href="file-manager-ui/_next/static/runtime/main-a2b8fbdec40056ac5ef4.js" as="script" crossorigin="anonymous"/>
    <style id="jss-server-side">html{box-sizing:border-box;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}*,*::after,*::before{box-sizing:inherit}body{color:rgba(0, 0, 0, 0.87);margin:0;font-size:0.875rem;font-family:"Roboto", "Helvetica", "Arial", sans-serif;font-weight:400;line-height:1.43;letter-spacing:0.01071em;background-color:#fafafa}@media print{body{background-color:#fff}</style>
  </head>
  <body>
    <div id="sub-conteudo">
      <table width="890" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="310" height="25" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_gerenciador_videos_pastas']; ?></td>
          <td width="580" height="25" align="left" class="texto_padrao_destaque" style="padding-left:9px;"><?php echo $lang['lang_info_gerenciador_videos_videos_pasta']; ?></td>
        </tr>
        <tr>
          <td align="left" style="padding-left:5px;">
            <div id="borda_lista_pastas" style="background-color:#FFFFFF; border: #CCCCCC 1px solid; width:285px; height:250px; text-align:left; float:left; padding:5px; overflow: auto;">
              <span id="status_lista_pastas" class="texto_padrao_pequeno"></span>
                          <ul id="lista-pastas"></ul>
                        </div>
                      </td>
          <td align="left">
            <div id="videos" style="background-color:#FFFFFF; border: #CCCCCC 1px solid; width:560px; height:250px; text-align:left; float:right; padding:5px; overflow: auto;">
              <span id="msg_pasta" class="texto_padrao_pequeno"><?php echo $lang['lang_info_gerenciador_videos_info_lista_videos']; ?></span>
              <ul id="lista-videos-pasta"></ul>
            </div>
          </td>
        </tr>
        <tr>
          <td height="30" align="left" style="padding-left:5px;">
            <img src="/img/icones/img-icone-cadastrar.png" width="16" height="16" align="absmiddle" />&nbsp;<a href="javascript:criar_pasta('<?php echo $login_code; ?>');" class="texto_padrao"><?php echo $lang['lang_info_gerenciador_videos_botao_criar_pasta']; ?></a>&nbsp;&nbsp;<img src="/img/icones/img-icone-atualizar.png" width="16" height="16" align="absmiddle" border="0" />&nbsp;<a href="javascript:carregar_pastas('<?php echo $login_code; ?>');" class="texto_padrao"><?php echo $lang['lang_info_gerenciador_videos_botao_recarregar_pastas']; ?></a>&nbsp;
          </td>
          <td width="580" align="left" class="texto_padrao_destaque" style="padding-left:9px;"><?php echo $lang['lang_info_gerenciador_videos_pasta_selecionada']; ?>&nbsp;<span id="msg_pasta_selecionada" class="texto_padrao_vermelho"><?php echo $lang['lang_info_gerenciador_videos_pasta_selecionada_nenhuma']; ?></span></td>
        </tr>
        <tr>
          <td align="center" valign="top">
            <div style="padding-top:20px;padding-left:80px;"><span id="estatistica_uso_plano_ftp"></span></div>
                        <span class="texto_padrao_pequeno">(<?php echo tamanho($dados_stm["espaco_usado"]); ?> / <?php echo tamanho($dados_stm["espaco"]); ?>)</span>
          </td>
          <td align="left" style="padding-left:9px;">
            <input name="pasta_selecionada" type="hidden" id="pasta_selecionada" value="" />

            <div id="__next"><div></div></div>
          </td>
        </tr>
      </table>
      <table width="690" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:20px; margin-bottom:20px; background-color:#FFFF66; border:#DFDF00 1px solid">
        <tr>
          <td width="30" height="25" align="center" scope="col"><img src="/img/icones/atencao.png" width="16" height="16" /></td>
          <td width="660" align="left" class="texto_pequeno_erro" scope="col"><?php echo $lang['lang_info_gerenciador_videos_info_caracteres_especiais']; ?></td>
        </tr>
      </table>
    </div>

    <script type="text/javascript" src="/inc/ajax-streaming-videos.js" charset="utf-8"></script>
    <script type="text/javascript" src="/inc/javascript.js"></script>

    <script id="__NEXT_DATA__" type="application/json" crossorigin="anonymous">{"dataManager":"[]","props":{"pageProps":{}},"page":"/","query":{},"buildId":"v1","dynamicBuildId":false,"assetPrefix":"file-manager-ui","nextExport":true}</script>
    <script async="" id="__NEXT_PAGE__/" src="file-manager-ui/_next/static/v1/pages/index.js" crossorigin="anonymous"></script>
    <script async="" id="__NEXT_PAGE__/_app" src="file-manager-ui/_next/static/v1/pages/_app.js" crossorigin="anonymous"></script>
    <script src="file-manager-ui/_next/static/runtime/webpack-c338d2af2513c103e40f.js" async="" crossorigin="anonymous"></script>
    <script src="file-manager-ui/_next/static/chunks/commons.67a9413127497509dab3.js" async="" crossorigin="anonymous"></script>
    <script src="file-manager-ui/_next/static/runtime/main-a2b8fbdec40056ac5ef4.js" async="" crossorigin="anonymous"></script>

    <!-- In?cio div log do sistema -->
    <div id="log-sistema-fundo"></div>
    <div id="log-sistema">
      <div id="log-sistema-botao"><img src="/img/icones/img-icone-fechar.png" onclick="document.getElementById('log-sistema-fundo').style.display = 'none';document.getElementById('log-sistema').style.display = 'none';document.getElementById('log-sistema-conteudo').innerHTML = '';" style="cursor:pointer" title="<?php echo $lang['lang_titulo_fechar']; ?>" /></div>
      <div id="log-sistema-conteudo"></div>
    </div>
    <!-- Fim div log do sistema -->

    <script type="text/javascript">
      // Checar o status dos streamings
      estatistica_uso_plano( <?php echo $dados_stm["login"]; ?>,'ftp','nao');
    </script>

    <script type="text/javascript">
      window.onload = function() {
        window.upload_url = 'https://<?php echo strtolower($dados_servidor["nome"]); ?>.<?php echo $dados_config["dominio_padrao"]; ?>:1443/upload-videos.php'
        window.upload_login = '<?php echo $dados_stm["login"]; ?>'
        window.upload_folder = document.getElementById("pasta_selecionada").value

        carregar_pastas('<?php echo $login_code; ?>');
              fechar_log_sistema();
      };

      function recarregar_pasta() {
        carregar_videos_pasta('<?php echo $login_code; ?>', window.upload_folder);
        carregar_pastas('<?php echo $login_code; ?>');
      }
    </script>
  </body>
</html>