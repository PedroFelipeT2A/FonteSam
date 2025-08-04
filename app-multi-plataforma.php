<?php
require_once("admin/inc/protecao-final.php");
require_once("app/wideimage/WideImage.php");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));
$dados_app_multi_plataforma = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM app_multi_plataforma where codigo_stm = '".$dados_stm["codigo"]."'"));

$url_player = (!empty($dados_revenda["dominio_padrao"])) ? "playerv.".$dados_revenda["dominio_padrao"]."" : "playerv.".$dados_config["dominio_padrao"]."";

//Funções
function formatar_nome_radio2($nome){$characteres=array('S'=>'S','s'=>'s','Ð'=>'Dj','Z'=>'Z','z'=>'z','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','f'=>'f','¹'=>'','²'=>'','&'=>'e','³'=>'','£'=>'','$'=>'','%'=>'','¨'=>'','§'=>'','º'=>'','ª'=>'','©'=>'','Ã£'=>'','('=>'',')'=>'',"'"=>'','@'=>'','='=>'',':'=>'','!'=>'','?'=>'','...'=>'','®'=>'','/'=>'','´'=>'','+'=>'','*'=>'','['=>'',']'=>'');return strtr($nome,$characteres);}function nome_app_play2($texto){$characteres=array('S'=>'S','s'=>'s','Ð'=>'Dj','Z'=>'Z','z'=>'z','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','f'=>'f','¹'=>'','²'=>'','&'=>'e','³'=>'','£'=>'','$'=>'','%'=>'','¨'=>'','§'=>'','º'=>'','ª'=>'','©'=>'','Ã£'=>'','('=>'',')'=>'',"'"=>'','@'=>'','='=>'',':'=>'','!'=>'','?'=>'','...'=>'',' '=>'','-'=>'','^'=>'','~'=>'','.'=>'','|'=>'',','=>'','<'=>'','>'=>'','{'=>'','}'=>'','®'=>'','/'=>'','´'=>'','+'=>'','*'=>'','['=>'',']'=>'');return strtolower(strtr($texto,$characteres));}function nome_app_apk2($texto){$characteres=array('S'=>'S','s'=>'s','Ð'=>'Dj','Z'=>'Z','z'=>'z','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','f'=>'f','¹'=>'','²'=>'','&'=>'e','³'=>'','£'=>'','$'=>'','%'=>'','¨'=>'','§'=>'','º'=>'','ª'=>'','©'=>'','Ã£'=>'','('=>'',')'=>'',"'"=>'','@'=>'','='=>'',':'=>'','!'=>'','?'=>'','...'=>'',' '=>'','-'=>'','^'=>'','~'=>'','.'=>'','|'=>'',','=>'','<'=>'','>'=>'','{'=>'','}'=>'',' '=>'','®'=>'','/'=>'','´'=>'','+'=>'','*'=>'','['=>'',']'=>'');return strtr($texto,$characteres);}function copiar_source2($DirFont,$DirDest){mkdir($DirDest);if($dd=opendir($DirFont)){while(false!==($Arq=readdir($dd))){if($Arq!="."&&$Arq!=".."){$PathIn="$DirFont/$Arq";$PathOut="$DirDest/$Arq";if(is_dir($PathIn)){copiar_source2($PathIn,$PathOut);chmod($PathOut,0777);}elseif(is_file($PathIn)){copy($PathIn,$PathOut);chmod($PathOut,0777);}}}closedir($dd);}}function criar_arquivo_config2($arquivo,$conteudo){$fd=fopen($arquivo,"w");fputs($fd,$conteudo);fclose($fd);}function browse2($dir){global $filenames;if($handle=opendir($dir)){while(false!==($file=readdir($handle))){if($file!="."&&$file!=".."&&is_file($dir.'/'.$file)){$filenames[]=$dir.'/'.$file;}else if($file!="."&&$file!=".."&&is_dir($dir.'/'.$file)){browse2($dir.'/'.$file);}}closedir($handle);}return $filenames;}function replace2($arquivo,$string_atual,$string_nova){$str=file_get_contents($arquivo);$str=str_replace($string_atual,$string_nova,$str);file_put_contents($arquivo,$str);}function remover_source_app2($Dir){if($dd=@opendir($Dir)){while(false!==($Arq=@readdir($dd))){if($Arq!="."&&$Arq!=".."){$Path="$Dir/$Arq";if(is_dir($Path)){remover_source_app2($Path);}elseif(is_file($Path)){@unlink($Path);}}}@closedir($dd);}@rmdir($Dir);}function mudar_permissao2($Dir){if($dd=opendir($Dir)){while(false!==($Arq=readdir($dd))){if($Arq!="."&&$Arq!=".."){$Path="$Dir/$Arq";@chmod($Path,0777);}}closedir($dd);}}


/////////////////////////////////////////////////
/////////////////// Idioma //////////////////////
/////////////////////////////////////////////////

if($dados_stm["idioma_painel"] == "pt-br") {

$lang[ 'lang_info_pagina_app_multi_tab_criar' ] = 'Criar Aplicativo' ;
$lang[ 'lang_info_streaming_app_android_tab_app_pronto' ] = 'Pr&eacute;via Aplicativo' ;
$lang[ 'lang_info_streaming_app_android_tab_configurar_app' ] = 'Configurar Aplicativo' ;
$lang[ 'lang_info_streaming_app_android_info_configuracoes' ] = '1-Preencha os dados abaixo para configurar seu App. Ap&oacute;s a configura&ccedil;&atilde;o ser&aacute; exibido a pr&eacute;via e c&oacute;digo para instala&ccedil;&atilde;o em seu site.<br>2-Insira em seu site o c&oacute;digo do bot&atilde;o de instala&ccedil;&atilde;o.<br>3-Seu visitante ir&aacute; clicar no bot&atilde;o de instala&ccedil;&atilde;o e o app ser&aacute; instalado de forma segura.' ;
$lang[ 'lang_info_streaming_app_android_info_desativar_campo' ] = '(deixe este campo em branco para desativa-lo)';
$lang[ 'lang_info_streaming_app_android_app_cor_texto' ] = 'Cor Texto';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_claro' ] = 'Cor Menu Claro';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_escuro' ] = 'Cor Menu Escuro';
$lang[ 'lang_info_streaming_app_android_app_cor_splash' ] = 'Cor Fundo Abertura(Splash)';
$lang[ 'lang_info_streaming_app_android_app_historia' ] = 'Texto Hist&oacute;ria';
$lang[ 'lang_info_streaming_app_android_app_programacao' ] = 'Texto Programa&ccedil;&atilde;o';
$lang[ 'lang_info_pagina_app_multi_botao_google_play' ] = 'Download do Apk para Google Play';
$lang[ 'lang_info_pagina_app_multi_botao_recompilar' ] = 'Recriar Apk Android';
$lang[ 'lang_info_pagina_app_multi_recompilar_info' ] = 'Marque esta op&ccedil;&atilde;o somente se for criar uma nova vers&atilde;o para Google Play';
$lang[ 'lang_info_streaming_app_android_info_instalar_app' ] = 'Bot&atilde;o Instala&ccedil;&atilde;o:';
$lang[ 'lang_info_streaming_app_android_info_instalar_app2' ] = 'Insira o c&oacute;digo acima em seu site, ao clicar, ser&aacute; aberto o app no navegador do ouvinte e ser&aacute; exibido uma mensagem para instala&ccedil;&atilde;o do app.';
$lang[ 'lang_info_pagina_app_multi_resultado_erro_compilar' ] = 'Erro ao compilar aplicativo android nativo, tente novamente ou contate o suporte.';
$lang[ 'lang_info_pagina_app_multi_resultado_erro_imagens' ] = 'A logo e a imagem de fundo s&atilde;o obrigat&oacute;rios.';

$lang[ 'lang_info_pagina_anuncio_tab_titulo' ] = 'An&uacute;ncios';
$lang[ 'lang_info_pagina_anuncio_tab_titulo_cadastrar' ] = 'Cadastrar An&uacute;ncio';
$lang[ 'lang_info_pagina_anuncio_nome' ] = 'Nome';
$lang[ 'lang_info_pagina_anuncio_banner' ] = 'Imagem';

$lang[ 'lang_info_pagina_push_tab_titulo' ] = 'Notifica&ccedil;&otilde;es';
$lang[ 'lang_info_pagina_push_tab_enviar_titulo' ] = 'T&iacute;tulo';
$lang[ 'lang_info_pagina_push_tab_enviar_destino' ] = 'Destino';
$lang[ 'lang_info_pagina_push_tab_enviar_titulo' ] = 'T&iacute;tulo';
$lang[ 'lang_info_pagina_push_tab_enviar_url_icone' ] = 'URL Icone(SSL)';
$lang[ 'lang_info_pagina_push_tab_enviar_url_imagem' ] = 'URL Imagem(SSL)';
$lang[ 'lang_info_pagina_push_tab_enviar_link' ] = 'Link ao Clicar';
$lang[ 'lang_info_pagina_push_tab_enviar_mensagem' ] = 'Mensagem';
$lang[ 'lang_info_pagina_push_resultado_notificacao_ok' ] = 'Notifica&ccedil;&atilde;o adicionada com sucesso!';
$lang[ 'lang_info_pagina_push_tab_enviar_botao' ] = 'Adicionar';

} else if($dados_stm["idioma_painel"] == "en") {

$lang[ 'lang_info_pagina_app_multi_tab_criar' ] = 'Create App' ;
$lang[ 'lang_info_streaming_app_android_tab_app_pronto' ] = 'App Preview' ;
$lang[ 'lang_info_streaming_app_android_tab_configurar_app' ] = 'Configure App' ;
$lang[ 'lang_info_streaming_app_android_info_configuracoes' ] = '1-Fill in the data below to configure your App. After the configuration the preview and installation code on your website is displayed. <br> 2-Insert the code of the installation button on your website. <br> 3-Your visitor will click on the install button and the app will be safely installed .' ;
$lang[ 'lang_info_streaming_app_android_info_desativar_campo' ] = '(leave empty to disable displaying)';
$lang[ 'lang_info_streaming_app_android_app_cor_texto' ] = 'Text Color';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_claro' ] = 'Menu Color Light';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_escuro' ] = 'Menu Color Dark';
$lang[ 'lang_info_streaming_app_android_app_cor_splash' ] = 'Splash Color';
$lang[ 'lang_info_streaming_app_android_app_historia' ] = 'Text TV Story';
$lang[ 'lang_info_pagina_app_multi_botao_google_play' ] = 'Download the Apk to publish';
$lang[ 'lang_info_pagina_app_multi_botao_recompilar' ] = 'Recompile Apk Android';
$lang[ 'lang_info_pagina_app_multi_recompilar_info' ] = 'Check this option only if you need to create a new APK version to Google Play';
$lang[ 'lang_info_streaming_app_android_app_programacao' ] = 'Text TV Schedules';
$lang[ 'lang_info_streaming_app_android_info_instalar_app' ] = 'Install Button:';
$lang[ 'lang_info_streaming_app_android_info_instalar_app2' ] = 'Add the code above on your website, when clicking, it will be open the app in the listener\'s browser and a message is displayed to install the app.';
$lang[ 'lang_info_pagina_app_multi_resultado_erro_compilar' ] = 'Error compiling native android app, try again or contact support.';
$lang[ 'lang_info_pagina_app_multi_resultado_erro_imagens' ] = 'The logo and background image are required.';

$lang[ 'lang_info_pagina_anuncio_tab_titulo' ] = 'Banners';
$lang[ 'lang_info_pagina_anuncio_tab_titulo_cadastrar' ] = 'Add Banner';
$lang[ 'lang_info_pagina_anuncio_nome' ] = 'Name';
$lang[ 'lang_info_pagina_anuncio_banner' ] = 'Banner';

$lang[ 'lang_info_pagina_push_tab_titulo' ] = 'Notifications';
$lang[ 'lang_info_pagina_push_tab_enviar_titulo' ] = 'Title';
$lang[ 'lang_info_pagina_push_tab_enviar_destino' ] = 'To';
$lang[ 'lang_info_pagina_push_tab_enviar_titulo' ] = 'Title';
$lang[ 'lang_info_pagina_push_tab_enviar_url_icone' ] = 'URL Icon(SSL)';
$lang[ 'lang_info_pagina_push_tab_enviar_url_imagem' ] = 'URL Image(SSL)';
$lang[ 'lang_info_pagina_push_tab_enviar_link' ] = 'Link When Click';
$lang[ 'lang_info_pagina_push_tab_enviar_mensagem' ] = 'Mensage';
$lang[ 'lang_info_pagina_push_resultado_notificacao_ok' ] = 'Notification successfully added!';
$lang[ 'lang_info_pagina_push_tab_enviar_botao' ] = 'Add';

} else {

$lang[ 'lang_info_pagina_app_multi_tab_criar' ] = 'Crear App' ;
$lang[ 'lang_info_streaming_app_android_tab_app_pronto' ] = 'Vista Previa App' ;
$lang[ 'lang_info_streaming_app_android_tab_configurar_app' ] = 'Configurar App' ;
$lang[ 'lang_info_streaming_app_android_info_configuracoes' ] = '1-Complete los datos a continuaci&oacute;n para crear su aplicaci&oacute;n. Despu&eacute;s de la configuraci&oacute;n se mostrar&aacute; la previa y el c&oacute;digo de instalaci&oacute;n en su sitio web.<br>2-A&ntilde;ade el c&oacute;digo del bot&oacute;n de instalaci&oacute;n en su sitio web.<br>3-Su visitante har&aacute; clic en el bot&oacute;n de instalaci&oacute;n y se agregar&aacute; la aplicaci&oacute;n.' ;
$lang[ 'lang_info_streaming_app_android_info_desativar_campo' ] = '(deje este campo en blanco para deshabilitarlo)';
$lang[ 'lang_info_streaming_app_android_app_cor_texto' ] = 'Color Texto';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_claro' ] = 'Color Menu Claro';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_escuro' ] = 'Color Menu Oscuro';
$lang[ 'lang_info_streaming_app_android_app_cor_splash' ] = 'Color Inicio(Splash)';
$lang[ 'lang_info_pagina_app_multi_botao_google_play' ] = 'Descargar Apk para Google Play';
$lang[ 'lang_info_pagina_app_multi_botao_recompilar' ] = 'Recrear Apk Android';
$lang[ 'lang_info_pagina_app_multi_recompilar_info' ] = 'Marca esta opci&oacute;n s&oacute;lo si vas a crear una nueva versi&oacute;n APK para Google Play';
$lang[ 'lang_info_streaming_app_android_app_historia' ] = 'Texto Historial';
$lang[ 'lang_info_streaming_app_android_app_programacao' ] = 'Texto Programaci&oacute;n';
$lang[ 'lang_info_streaming_app_android_info_instalar_app' ] = 'Botón de Instalación:';
$lang[ 'lang_info_streaming_app_android_info_instalar_app2' ] = 'A&ntilde;ade el c&oacute;digo anterior en su sitio web, al hacer clic, abra la aplicaci&oacute;n en el navegador del oyente y se muestra un mensaje para instalar la aplicaci&oacute;n.';
$lang[ 'lang_info_pagina_app_multi_resultado_erro_compilar' ] = 'Error al compilar la aplicación nativa de Android, inténtelo de nuevo o comuníquese con el soporte.';
$lang[ 'lang_info_pagina_app_multi_resultado_erro_imagens' ] = 'El logo y la imagen de fondo son obligatorios.';

$lang[ 'lang_info_pagina_anuncio_tab_titulo' ] = 'Banners';
$lang[ 'lang_info_pagina_anuncio_tab_titulo_cadastrar' ] = 'Agregar Banner';
$lang[ 'lang_info_pagina_anuncio_nome' ] = 'Nombre';
$lang[ 'lang_info_pagina_anuncio_banner' ] = 'Banner';

$lang[ 'lang_info_pagina_push_tab_titulo' ] = 'Notificaciones';
$lang[ 'lang_info_pagina_push_tab_enviar_titulo' ] = 'Asunto';
$lang[ 'lang_info_pagina_push_tab_enviar_destino' ] = 'Destino';
$lang[ 'lang_info_pagina_push_tab_enviar_titulo' ] = 'Titulo';
$lang[ 'lang_info_pagina_push_tab_enviar_url_icone' ] = 'URL Icono(SSL)';
$lang[ 'lang_info_pagina_push_tab_enviar_url_imagem' ] = 'URL Imagen(SSL)';
$lang[ 'lang_info_pagina_push_tab_enviar_link' ] = 'Link al Clic';
$lang[ 'lang_info_pagina_push_tab_enviar_mensagem' ] = 'Mensaje';
$lang[ 'lang_info_pagina_push_resultado_notificacao_ok' ] = 'Notificaci&oacute;n agregada con exito!';
$lang[ 'lang_info_pagina_push_tab_enviar_botao' ] = 'Agregar';

}

if($_POST["acao_form"] == "criar") {

if(empty($_FILES["logo"]["tmp_name"]) || empty($_FILES["fundo"]["tmp_name"])) {

$_SESSION["status_acao"] = status_acao($lang['lang_info_pagina_app_multi_resultado_erro_imagens'],"alerta");

header("Location: /app-multi-plataforma");
exit();
}

$source = "source-app-multiplataforma";

if($_POST["pkg_personalizado"]) {
$package_uniq = str_replace("com.","",$_POST["pkg_personalizado"]);
} else if($_POST["pkg_personalizado_historico"]) {
$package_uniq = str_replace("com.","",$_POST["pkg_personalizado_historico"]);
} else {
$package_uniq = "app".md5(uniqid(rand(), true));
}

$array_pastas_package = explode(".", $package_uniq);

$package = "com.stmvideo.webtv.".$package_uniq."";
$package_path = str_replace(".","/",$package_uniq);

$hash = $package_uniq."_".md5($_POST["nome"]);

$patch_dir_apps = $_SERVER['DOCUMENT_ROOT']."/app/apps";
$patch_app = $_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."";
$patch_tmp = $_SERVER['DOCUMENT_ROOT']."/app/apps/tmp";

copiar_source2($_SERVER['DOCUMENT_ROOT']."/app/".$source."/", $patch_app);

$logo = WideImage::load($_FILES["logo"]["tmp_name"]);
$logo = $logo->resize(300, 300, 'fill');
$logo->saveToFile($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");

// Copia o ícone
$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(72, 72);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-hdpi/ic_launcher.png");
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-hdpi/ic_maskable.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(48, 48);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-mdpi/ic_launcher.png");
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-mdpi/ic_maskable.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(96, 96);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xhdpi/ic_launcher.png");
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-xhdpi/ic_maskable.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(144, 144);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xxhdpi/ic_launcher.png");
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-xxhdpi/ic_maskable.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(192, 192);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xxxhdpi/ic_launcher.png");
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-xxxhdpi/ic_maskable.png");

// Copia o Splash
$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(450, 450);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-hdpi/splash.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(300, 300);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-mdpi/splash.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(600, 600);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xhdpi/splash.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(900, 900);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xxhdpi/splash.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(1200, 1200);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xxxhdpi/splash.png");

$fundo = WideImage::load($_FILES["fundo"]["tmp_name"]);
$fundo = $fundo->resize(640, 1136, 'fill');
$fundo->saveToFile($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/background-".$dados_stm["login"].".jpg");

// Cria icone para o Play
$play_icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$play_icone = $play_icone->resize(512, 512);
$play_icone->saveToFile("".$patch_app."/arquivos_google_play/img-play-logo.png");

// Cria a imagem de destaque para o Play com a logo da tv
$destaque = WideImage::load("".$patch_app."/arquivos_google_play/img-play-destaque.jpg");
$logo_destaque = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$play_destaque = $destaque->merge($logo_destaque, 'center', 'center', 100);
$play_destaque->saveToFile("".$patch_app."/arquivos_google_play/img-play-destaque.jpg");

// Cria o print do app para o Play com a logo da tv
$fundo_print_play = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/background-".$dados_stm["login"].".jpg");
$fundo_print_play = $fundo_print_play->resize(640, 1136);
$logo_print_play = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$print_play = $fundo_print_play->merge($logo_print_play, 'center', 'center', 100);
$print_play->saveToFile("".$patch_app."/arquivos_google_play/img-play-app.png");

// Cria tamanhos diferenciados par google play
$printapp_800x1280 = WideImage::load("".$patch_app."/arquivos_google_play/img-play-app.png");
$printapp_800x1280 = $printapp_800x1280->resize(800, 1280, 'fill');
$printapp_800x1280->saveToFile("".$patch_app."/arquivos_google_play/img-play-app-800x1280.png");

// Dados da tv
replace2("".$patch_app."/app/build.gradle","RADIO_NOME",utf8_encode(str_replace("&","&amp;",$_POST["nome"])));
replace2("".$patch_app."/app/build.gradle","URL_APP","playerv.".$dados_config["dominio_padrao"]."/player-app-multi-plataforma/".$dados_stm["login"]."?app-multi=android");
replace2("".$patch_app."/app/build.gradle","cor_menu_escuro",$_POST["cor_menu_escuro"]);
replace2("".$patch_app."/app/build.gradle","cor_splash",$_POST["cor_splash"]);

replace2("".$patch_app."/twa-manifest.json","RADIO_NOME",utf8_encode(str_replace("&","&amp;",$_POST["nome"])));
replace2("".$patch_app."/twa-manifest.json","URL_APP","playerv.".$dados_config["dominio_padrao"]."/player-app-multi-plataforma/".$dados_stm["login"]."?app-multi=android");
replace2("".$patch_app."/twa-manifest.json","cor_menu_escuro",$_POST["cor_menu_escuro"]);
replace2("".$patch_app."/twa-manifest.json","cor_splash",$_POST["cor_splash"]);
replace2("".$patch_app."/twa-manifest.json","URL_ICONE","playerv.".$dados_config["dominio_padrao"]."/app-multi-plataforma/logo-".$dados_stm["login"].".png");

replace2("".$patch_app."/app/build.gradle","com.shoutcast.stm.radio_nome",$package);
replace2("".$patch_app."/app/src/main/AndroidManifest.xml","com.shoutcast.stm.radio_nome",$package);


if($_POST["versao"] == '1.0') {
$codigo_versao = 1;
} elseif($_POST["versao"] == '1.1') {
$codigo_versao = 2;
} elseif($_POST["versao"] == '1.2') {
$codigo_versao = 3;
} elseif($_POST["versao"] == '1.3') {
$codigo_versao = 4;
} elseif($_POST["versao"] == '1.4') {
$codigo_versao = 5;
} elseif($_POST["versao"] == '1.5') {
$codigo_versao = 6;
} elseif($_POST["versao"] == '1.6') {
$codigo_versao = 7;
} elseif($_POST["versao"] == '1.7') {
$codigo_versao = 8;
} elseif($_POST["versao"] == '1.8') {
$codigo_versao = 9;
} elseif($_POST["versao"] == '1.9') {
$codigo_versao = 10;
} elseif($_POST["versao"] == '1.10') {
$codigo_versao = 11;
} elseif($_POST["versao"] == '1.11') {
$codigo_versao = 12;
} elseif($_POST["versao"] == '1.12') {
$codigo_versao = 13;
} elseif($_POST["versao"] == '1.13') {
$codigo_versao = 14;
} elseif($_POST["versao"] == '1.14') {
$codigo_versao = 15;
} elseif($_POST["versao"] == '1.15') {
$codigo_versao = 16;
} elseif($_POST["versao"] == '1.16') {
$codigo_versao = 17;
} elseif($_POST["versao"] == '1.17') {
$codigo_versao = 18;
} elseif($_POST["versao"] == '1.18') {
$codigo_versao = 19;
} elseif($_POST["versao"] == '1.19') {
$codigo_versao = 20;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '2.0') {
$codigo_versao = 22;
} elseif($_POST["versao"] == '2.1') {
$codigo_versao = 23;
} elseif($_POST["versao"] == '2.2') {
$codigo_versao = 24;
} elseif($_POST["versao"] == '2.3') {
$codigo_versao = 25;
} elseif($_POST["versao"] == '2.4') {
$codigo_versao = 26;
} elseif($_POST["versao"] == '2.5') {
$codigo_versao = 27;
} elseif($_POST["versao"] == '2.6') {
$codigo_versao = 28;
} elseif($_POST["versao"] == '2.7') {
$codigo_versao = 29;
} elseif($_POST["versao"] == '2.8') {
$codigo_versao = 30;
} elseif($_POST["versao"] == '2.9') {
$codigo_versao = 31;
} elseif($_POST["versao"] == '3.0') {
$codigo_versao = 32;
} elseif($_POST["versao"] == '4.0') {
$codigo_versao = 33;
} elseif($_POST["versao"] == '5.0') {
$codigo_versao = 34;
} else {
$codigo_versao = 1;
}

replace2("".$patch_app."/app/build.gradle","codigo_versao",$codigo_versao);
replace2("".$patch_app."/app/build.gradle","numero_versao",$_POST["versao"]);

replace2("".$patch_app."/twa-manifest.json","codigo_versao",$codigo_versao);
replace2("".$patch_app."/twa-manifest.json","numero_versao",$_POST["versao"]);


$nome_apk = nome_app_apk2($_POST["nome"]);

// Compila o App
shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;cd ".$_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash.";./gradlew bundleRelease;./gradlew assembleRelease");

// Assina o app com certificado
if($dados_stm["app_certificado"] == "padrao") {

//Cria o certificado
$nome_certificado = md5($nome_apk);

shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;keytool -genkey -alias streaming -keyalg RSA -keystore /home/painelvideo/public_html/app/keys/".$nome_certificado.".jks -dname 'CN=Streaming, OU=Streaming, O=Streaming, L=Streaming, S=Brasil, C=BR' -storepass ".$nome_certificado." -keypass ".$nome_certificado." -validity 365000;cp -v /home/painelvideo/public_html/app/keys/".$nome_certificado.".jks /home/painelvideo/public_html/app/apps/".$hash."/".$nome_certificado.".jks");

replace2("".$patch_app."/twa-manifest.json","CERTIFICADO",$nome_certificado.".jks");
replace2("".$patch_app."/twa-manifest.json","CERTIFICADO_ALIAS","streaming");

// Assina o app com certificado
shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;/opt/android-sdk-linux/build-tools/28.0.3/apksigner sign --ks-pass pass:".$nome_certificado." --ks /home/painelvideo/public_html/app/keys/".$nome_certificado.".jks --out /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/apk/release/app-release-unsigned.apk;jarsigner -verbose -sigalg SHA256withRSA -digestalg SHA-256 -keystore /home/painelvideo/public_html/app/keys/".$nome_certificado.".jks -storepass ".$nome_certificado." -keypass ".$nome_certificado." /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab streaming;cp -f /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab");

$finger_print_certificado = shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;keytool -list -v -keystore /home/painelvideo/public_html/app/keys/".$nome_certificado.".jks -alias streaming -storepass ".$nome_certificado." 2> /dev/null |  grep 'SHA256:' | /bin/awk '{ print \$2;}'");

mysqli_query($conexao,"Update streamings set app_certificado = '".$nome_certificado."' where login = '".$dados_stm["login"]."'");

// Cria TXT com dados do certificado
$txt_certificado = "Certificado: certificado.jks\nKey: ".$nome_certificado."\nAlias: streaming";
@file_put_contents($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/Certificado.txt", $txt_certificado);
@copy($_SERVER['DOCUMENT_ROOT']."/app/keys/".$nome_certificado.".jks",$_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/certificado.jks");


} else {

shell_exec("cp -v /home/painelvideo/public_html/app/keys/".$dados_stm["app_certificado"].".jks /home/painelvideo/public_html/app/apps/".$hash."/".$dados_stm["app_certificado"].".jks");

replace2("".$patch_app."/twa-manifest.json","CERTIFICADO",$dados_stm["app_certificado"].".jks");
replace2("".$patch_app."/twa-manifest.json","CERTIFICADO_ALIAS","streaming");

shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;/opt/android-sdk-linux/build-tools/28.0.3/apksigner sign --ks-pass pass:".$dados_stm["app_certificado"]." --ks /home/painelvideo/public_html/app/keys/".$dados_stm["app_certificado"].".jks --out /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/apk/release/app-release-unsigned.apk;jarsigner -verbose -sigalg SHA256withRSA -digestalg SHA-256 -keystore /home/painelvideo/public_html/app/keys/".$dados_stm["app_certificado"].".jks -storepass ".$dados_stm["app_certificado"]." -keypass ".$dados_stm["app_certificado"]." /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab streaming;cp -f /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab");

$finger_print_certificado = shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;keytool -list -v -keystore /home/painelvideo/public_html/app/keys/".$dados_stm["app_certificado"].".jks -alias streaming -storepass ".$dados_stm["app_certificado"]." 2> /dev/null |  grep 'SHA256:' | /bin/awk '{ print \$2;}'");

// Cria TXT com dados do certificado
$txt_certificado = "Certificado: certificado.jks\nKey: ".$dados_stm["app_certificado"]."\nAlias: streaming";
@file_put_contents($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/Certificado.txt", $txt_certificado);
@copy($_SERVER['DOCUMENT_ROOT']."/app/keys/".$dados_stm["app_certificado"].".jks",$_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/certificado.jks");

}

if(!file_exists($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk") || !file_exists($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab")) {

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao($lang['lang_info_pagina_app_multi_resultado_erro_compilar'],"erro");

header("Location: /app-multi-plataforma");
exit();
}

// Cria o zip com o conteudo para publicação no google play
$zip = new ZipArchive();
if ($zip->open($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash.".zip", ZIPARCHIVE::CREATE)!==TRUE) {
    die("Não foi possível criar o arquivo ZIP: ".$hash.".zip");
}

$zip->addEmptyDir("".$nome_apk."");
$zip->addFile($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk","".$nome_apk."/App-".$nome_apk.".apk");
$zip->addFile($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab","".$nome_apk."/App-".$nome_apk.".aab");
$zip->addFile($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/img-play-logo.png","".$nome_apk."/img-play-logo.png");
$zip->addFile($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/img-play-destaque.jpg","".$nome_apk."/img-play-destaque.jpg");
$zip->addFile($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/img-play-app.png","".$nome_apk."/img-play-app.png");
$zip->addFile($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/img-play-app-800x1280.png","".$nome_apk."/img-play-app-800x1280.png");
$status=$zip->getStatusString();
$zip->close();

if(!file_exists($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash.".zip")) {
shell_exec("cd ".$_SERVER['DOCUMENT_ROOT']."/app/apps/;/usr/bin/zip -1 ".$hash.".zip ".$hash.";/usr/bin/zip -1 ".$hash.".zip ".$hash."/arquivos_google_play/*");
}

// Remove source
if($hash != "") {
remover_source_app2($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."");
}

$assetlinks_lista = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/player/.well-known/assetlinks.json"));
array_push($assetlinks_lista, array('relation' => array("delegate_permission/common.handle_all_urls"), 'target' => array('namespace' => 'android_app', 'package_name' => $package, 'sha256_cert_fingerprints' => array(trim(str_replace(PHP_EOL, '',$finger_print_certificado))))));
file_put_contents($_SERVER['DOCUMENT_ROOT']."/player/.well-known/assetlinks.json", json_encode($assetlinks_lista, JSON_UNESCAPED_SLASHES));

//////////////////////////////////////////////////////////////////////////////////////

mysqli_query($conexao,"INSERT INTO app_multi_plataforma (codigo_stm,nome,url_logo,url_background,cor_texto,cor_menu_claro,cor_menu_escuro,cor_splash,modelo,apk_package,apk_versao,apk_criado,apk_cert_sha256,apk_zip) VALUES ('".$dados_stm["codigo"]."','".$_POST["nome"]."','/app-multi-plataforma/logo-".$dados_stm["login"].".png','/app-multi-plataforma/background-".$dados_stm["login"].".jpg','".$_POST["cor_texto"]."','".$_POST["cor_menu_claro"]."','".$_POST["cor_menu_escuro"]."','".$_POST["cor_splash"]."','".$_POST["modelo"]."','".$package."','".$_POST["versao"]."','sim','".trim(str_replace(PHP_EOL, '',$finger_print_certificado))."','".$hash.".zip')");


$_SESSION["status_acao"] = status_acao($lang['lang_info_config_painel_resultado_ok'],"ok");

header("Location: /app-multi-plataforma");
exit();
}

if($_POST["acao_form"] == "configurar") {

if($dados_app_multi_plataforma["apk_criado"] == "nao" && (empty($_FILES["logo"]["tmp_name"]) || empty($_FILES["fundo"]["tmp_name"]))) {

$_SESSION["status_acao"] = status_acao($lang['lang_info_pagina_app_multi_resultado_erro_imagens'],"alerta");

header("Location: /app-multi-plataforma");
exit();
}

if($dados_app_multi_plataforma["apk_criado"] == "nao" || $_POST['apk_criado'] == "nao") {

$source = "source-app-multiplataforma";

if($_POST["pkg_personalizado"]) {
$package_uniq = str_replace("com.","",$_POST["pkg_personalizado"]);
} else if($_POST["pkg_personalizado_historico"]) {
$package_uniq = str_replace("com.","",$_POST["pkg_personalizado_historico"]);
} else {
$package_uniq = "app".md5(uniqid(rand(), true));
}

$array_pastas_package = explode(".", $package_uniq);

$package = "com.stmvideo.webtv.".$package_uniq."";
$package_path = str_replace(".","/",$package_uniq);

$hash = $package_uniq."_".md5($_POST["nome"]);

$patch_dir_apps = $_SERVER['DOCUMENT_ROOT']."/app/apps";
$patch_app = $_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."";
$patch_tmp = $_SERVER['DOCUMENT_ROOT']."/app/apps/tmp";

copiar_source2($_SERVER['DOCUMENT_ROOT']."/app/".$source."/", $patch_app);

$logo = WideImage::load($_FILES["logo"]["tmp_name"]);
$logo = $logo->resize(300, 300, 'fill');
$logo->saveToFile($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");

// Copia o ícone
$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(72, 72);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-hdpi/ic_launcher.png");
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-hdpi/ic_maskable.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(48, 48);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-mdpi/ic_launcher.png");
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-mdpi/ic_maskable.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(96, 96);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xhdpi/ic_launcher.png");
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-xhdpi/ic_maskable.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(144, 144);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xxhdpi/ic_launcher.png");
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-xxhdpi/ic_maskable.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(192, 192);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xxxhdpi/ic_launcher.png");
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-xxxhdpi/ic_maskable.png");

// Copia o Splash
$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(450, 450);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-hdpi/splash.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(300, 300);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-mdpi/splash.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(600, 600);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xhdpi/splash.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(900, 900);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xxhdpi/splash.png");

$icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$icone = $icone->resize(1200, 1200);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xxxhdpi/splash.png");

$fundo = WideImage::load($_FILES["fundo"]["tmp_name"]);
$fundo = $fundo->resize(640, 1136, 'fill');
$fundo->saveToFile($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/background-".$dados_stm["login"].".jpg");

// Cria icone para o Play
$play_icone = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$play_icone = $play_icone->resize(512, 512);
$play_icone->saveToFile("".$patch_app."/arquivos_google_play/img-play-logo.png");

// Cria a imagem de destaque para o Play com a logo da tv
$destaque = WideImage::load("".$patch_app."/arquivos_google_play/img-play-destaque.jpg");
$logo_destaque = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$play_destaque = $destaque->merge($logo_destaque, 'center', 'center', 100);
$play_destaque->saveToFile("".$patch_app."/arquivos_google_play/img-play-destaque.jpg");

// Cria o print do app para o Play com a logo da tv
$fundo_print_play = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/background-".$dados_stm["login"].".jpg");
$fundo_print_play = $fundo_print_play->resize(640, 1136);
$logo_print_play = WideImage::load($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
$print_play = $fundo_print_play->merge($logo_print_play, 'center', 'center', 100);
$print_play->saveToFile("".$patch_app."/arquivos_google_play/img-play-app.png");

// Cria tamanhos diferenciados par google play
$printapp_800x1280 = WideImage::load("".$patch_app."/arquivos_google_play/img-play-app.png");
$printapp_800x1280 = $printapp_800x1280->resize(800, 1280, 'fill');
$printapp_800x1280->saveToFile("".$patch_app."/arquivos_google_play/img-play-app-800x1280.png");

// Dados da tv
replace2("".$patch_app."/app/build.gradle","RADIO_NOME",utf8_encode(str_replace("&","&amp;",$_POST["nome"])));
replace2("".$patch_app."/app/build.gradle","URL_APP","playerv.".$dados_config["dominio_padrao"]."/player-app-multi-plataforma/".$dados_stm["login"]."?app-multi=android");
replace2("".$patch_app."/app/build.gradle","cor_menu_escuro",$_POST["cor_menu_escuro"]);
replace2("".$patch_app."/app/build.gradle","cor_splash",$_POST["cor_splash"]);

replace2("".$patch_app."/twa-manifest.json","RADIO_NOME",utf8_encode(str_replace("&","&amp;",$_POST["nome"])));
replace2("".$patch_app."/twa-manifest.json","URL_APP","playerv.".$dados_config["dominio_padrao"]."/player-app-multi-plataforma/".$dados_stm["login"]."?app-multi=android");
replace2("".$patch_app."/twa-manifest.json","cor_menu_escuro",$_POST["cor_menu_escuro"]);
replace2("".$patch_app."/twa-manifest.json","cor_splash",$_POST["cor_splash"]);
replace2("".$patch_app."/twa-manifest.json","URL_ICONE","playerv.".$dados_config["dominio_padrao"]."/app-multi-plataforma/logo-".$dados_stm["login"].".png");

replace2("".$patch_app."/app/build.gradle","com.shoutcast.stm.radio_nome",$package);
replace2("".$patch_app."/app/src/main/AndroidManifest.xml","com.shoutcast.stm.radio_nome",$package);


if($_POST["versao"] == '1.0') {
$codigo_versao = 1;
} elseif($_POST["versao"] == '1.1') {
$codigo_versao = 2;
} elseif($_POST["versao"] == '1.2') {
$codigo_versao = 3;
} elseif($_POST["versao"] == '1.3') {
$codigo_versao = 4;
} elseif($_POST["versao"] == '1.4') {
$codigo_versao = 5;
} elseif($_POST["versao"] == '1.5') {
$codigo_versao = 6;
} elseif($_POST["versao"] == '1.6') {
$codigo_versao = 7;
} elseif($_POST["versao"] == '1.7') {
$codigo_versao = 8;
} elseif($_POST["versao"] == '1.8') {
$codigo_versao = 9;
} elseif($_POST["versao"] == '1.9') {
$codigo_versao = 10;
} elseif($_POST["versao"] == '1.10') {
$codigo_versao = 11;
} elseif($_POST["versao"] == '1.11') {
$codigo_versao = 12;
} elseif($_POST["versao"] == '1.12') {
$codigo_versao = 13;
} elseif($_POST["versao"] == '1.13') {
$codigo_versao = 14;
} elseif($_POST["versao"] == '1.14') {
$codigo_versao = 15;
} elseif($_POST["versao"] == '1.15') {
$codigo_versao = 16;
} elseif($_POST["versao"] == '1.16') {
$codigo_versao = 17;
} elseif($_POST["versao"] == '1.17') {
$codigo_versao = 18;
} elseif($_POST["versao"] == '1.18') {
$codigo_versao = 19;
} elseif($_POST["versao"] == '1.19') {
$codigo_versao = 20;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '1.20') {
$codigo_versao = 21;
} elseif($_POST["versao"] == '2.0') {
$codigo_versao = 22;
} elseif($_POST["versao"] == '2.1') {
$codigo_versao = 23;
} elseif($_POST["versao"] == '2.2') {
$codigo_versao = 24;
} elseif($_POST["versao"] == '2.3') {
$codigo_versao = 25;
} elseif($_POST["versao"] == '2.4') {
$codigo_versao = 26;
} elseif($_POST["versao"] == '2.5') {
$codigo_versao = 27;
} elseif($_POST["versao"] == '2.6') {
$codigo_versao = 28;
} elseif($_POST["versao"] == '2.7') {
$codigo_versao = 29;
} elseif($_POST["versao"] == '2.8') {
$codigo_versao = 30;
} elseif($_POST["versao"] == '2.9') {
$codigo_versao = 31;
} elseif($_POST["versao"] == '3.0') {
$codigo_versao = 32;
} elseif($_POST["versao"] == '4.0') {
$codigo_versao = 33;
} elseif($_POST["versao"] == '5.0') {
$codigo_versao = 34;
} else {
$codigo_versao = 1;
}

replace2("".$patch_app."/app/build.gradle","codigo_versao",$codigo_versao);
replace2("".$patch_app."/app/build.gradle","numero_versao",$_POST["versao"]);

replace2("".$patch_app."/twa-manifest.json","codigo_versao",$codigo_versao);
replace2("".$patch_app."/twa-manifest.json","numero_versao",$_POST["versao"]);


$nome_apk = nome_app_apk2($_POST["nome"]);

// Compila o App
shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;cd ".$_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash.";./gradlew bundleRelease;./gradlew assembleRelease");

// Assina o app com certificado
if($dados_stm["app_certificado"] == "padrao") {

//Cria o certificado
$nome_certificado = md5($nome_apk);

shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;keytool -genkey -alias streaming -keyalg RSA -keystore /home/painelvideo/public_html/app/keys/".$nome_certificado.".jks -dname 'CN=Streaming, OU=Streaming, O=Streaming, L=Streaming, S=Brasil, C=BR' -storepass ".$nome_certificado." -keypass ".$nome_certificado." -validity 365000;cp -v /home/painelvideo/public_html/app/keys/".$nome_certificado.".jks /home/painelvideo/public_html/app/apps/".$hash."/".$nome_certificado.".jks");

replace2("".$patch_app."/twa-manifest.json","CERTIFICADO",$nome_certificado.".jks");
replace2("".$patch_app."/twa-manifest.json","CERTIFICADO_ALIAS","streaming");

// Assina o app com certificado
shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;/opt/android-sdk-linux/build-tools/28.0.3/apksigner sign --ks-pass pass:".$nome_certificado." --ks /home/painelvideo/public_html/app/keys/".$nome_certificado.".jks --out /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/apk/release/app-release-unsigned.apk;jarsigner -verbose -sigalg SHA256withRSA -digestalg SHA-256 -keystore /home/painelvideo/public_html/app/keys/".$nome_certificado.".jks -storepass ".$nome_certificado." -keypass ".$nome_certificado." /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab streaming;cp -f /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab");

$finger_print_certificado = shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;keytool -list -v -keystore /home/painelvideo/public_html/app/keys/".$nome_certificado.".jks -alias streaming -storepass ".$nome_certificado." 2> /dev/null |  grep 'SHA256:' | /bin/awk '{ print \$2;}'");

mysqli_query($conexao,"Update streamings set app_certificado = '".$nome_certificado."' where login = '".$dados_stm["login"]."'");

// Cria TXT com dados do certificado
$txt_certificado = "Certificado: certificado.jks\nKey: ".$nome_certificado."\nAlias: streaming";
@file_put_contents($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/Certificado.txt", $txt_certificado);
@copy($_SERVER['DOCUMENT_ROOT']."/app/keys/".$nome_certificado.".jks",$_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/certificado.jks");


} else {

shell_exec("cp -v /home/painelvideo/public_html/app/keys/".$dados_stm["app_certificado"].".jks /home/painelvideo/public_html/app/apps/".$hash."/".$dados_stm["app_certificado"].".jks");

replace2("".$patch_app."/twa-manifest.json","CERTIFICADO",$dados_stm["app_certificado"].".jks");
replace2("".$patch_app."/twa-manifest.json","CERTIFICADO_ALIAS","streaming");

shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;/opt/android-sdk-linux/build-tools/28.0.3/apksigner sign --ks-pass pass:".$dados_stm["app_certificado"]." --ks /home/painelvideo/public_html/app/keys/".$dados_stm["app_certificado"].".jks --out /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/apk/release/app-release-unsigned.apk;jarsigner -verbose -sigalg SHA256withRSA -digestalg SHA-256 -keystore /home/painelvideo/public_html/app/keys/".$dados_stm["app_certificado"].".jks -storepass ".$dados_stm["app_certificado"]." -keypass ".$dados_stm["app_certificado"]." /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab streaming;cp -f /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab");

$finger_print_certificado = shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;keytool -list -v -keystore /home/painelvideo/public_html/app/keys/".$dados_stm["app_certificado"].".jks -alias streaming -storepass ".$dados_stm["app_certificado"]." 2> /dev/null |  grep 'SHA256:' | /bin/awk '{ print \$2;}'");

// Cria TXT com dados do certificado
$txt_certificado = "Certificado: certificado.jks\nKey: ".$dados_stm["app_certificado"]."\nAlias: streaming";
@file_put_contents($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/Certificado.txt", $txt_certificado);
@copy($_SERVER['DOCUMENT_ROOT']."/app/keys/".$dados_stm["app_certificado"].".jks",$_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/certificado.jks");

}

if(!file_exists($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk") || !file_exists($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab")) {

// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao($lang['lang_info_pagina_app_multi_resultado_erro_compilar'],"erro");

header("Location: /app-multi-plataforma");
exit();
}

// Cria o zip com o conteudo para publicação no google play
$zip = new ZipArchive();
if ($zip->open($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash.".zip", ZIPARCHIVE::CREATE)!==TRUE) {
    die("Não foi possível criar o arquivo ZIP: ".$hash.".zip");
}

$zip->addEmptyDir("".$nome_apk."");
$zip->addFile($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk","".$nome_apk."/App-".$nome_apk.".apk");
$zip->addFile($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab","".$nome_apk."/App-".$nome_apk.".aab");
$zip->addFile($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/img-play-logo.png","".$nome_apk."/img-play-logo.png");
$zip->addFile($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/img-play-destaque.jpg","".$nome_apk."/img-play-destaque.jpg");
$zip->addFile($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/img-play-app.png","".$nome_apk."/img-play-app.png");
$zip->addFile($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."/arquivos_google_play/img-play-app-800x1280.png","".$nome_apk."/img-play-app-800x1280.png");
$status=$zip->getStatusString();
$zip->close();

if(!file_exists($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash.".zip")) {
shell_exec("cd ".$_SERVER['DOCUMENT_ROOT']."/app/apps/;/usr/bin/zip -1 ".$hash.".zip ".$hash.";/usr/bin/zip -1 ".$hash.".zip ".$hash."/arquivos_google_play/*");
}

// Remove source
if($hash != "") {
remover_source_app2($_SERVER['DOCUMENT_ROOT']."/app/apps/".$hash."");
}

$assetlinks_lista = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/player/.well-known/assetlinks.json"));
array_push($assetlinks_lista, array('relation' => array("delegate_permission/common.handle_all_urls"), 'target' => array('namespace' => 'android_app', 'package_name' => $package, 'sha256_cert_fingerprints' => array(trim(str_replace(PHP_EOL, '',$finger_print_certificado))))));
file_put_contents($_SERVER['DOCUMENT_ROOT']."/player/.well-known/assetlinks.json", json_encode($assetlinks_lista, JSON_UNESCAPED_SLASHES));

mysqli_query($conexao,"Update app_multi_plataforma set apk_package = '".$package."', apk_versao = '".$_POST["versao"]."', apk_criado = 'sim', apk_cert_sha256 = '".trim(str_replace(PHP_EOL, '',$finger_print_certificado))."', apk_zip = '".$hash.".zip' where codigo = '".$dados_app_multi_plataforma["codigo"]."'");

} else {

if($_FILES["logo"]["tmp_name"]) {
@copy($_FILES["logo"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/logo-".$dados_stm["login"].".png");
}
if($_FILES["fundo"]["tmp_name"]) {
@copy($_FILES["fundo"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/background-".$dados_stm["login"].".jpg");
}

}

$url_chat = ($_POST["ativar_chat"]) ? $_POST["url_chat"] : "";
$contador = ($_POST["contador"]) ? $_POST["contador"] : "";

$whatsapp = str_replace("+", "", $_POST["whatsapp"]);
$whatsapp = str_replace(" ", "", $_POST["whatsapp"]);
$whatsapp = str_replace("(", "", $_POST["whatsapp"]);
$whatsapp = str_replace(")", "", $_POST["whatsapp"]);

mysqli_query($conexao,"Update app_multi_plataforma set nome = '".$_POST["nome"]."', email = '".$_POST["email"]."', whatsapp = '".$whatsapp."', url_facebook = '".$_POST["url_facebook"]."', url_instagram = '".$_POST["url_instagram"]."', url_twitter = '".$_POST["url_twitter"]."', url_site = '".$_POST["url_site"]."', cor_texto = '".$_POST["cor_texto"]."', cor_menu_claro = '".$_POST["cor_menu_claro"]."', cor_menu_escuro = '".$_POST["cor_menu_escuro"]."', cor_splash = '".$_POST["cor_splash"]."', url_logo = '/app-multi-plataforma/logo-".$dados_stm["login"].".png', url_background = '/app-multi-plataforma/background-".$dados_stm["login"].".jpg', url_chat = '".$url_chat."', text_prog = '".$_POST["text_prog"]."', text_hist = '".$_POST["text_hist"]."', url_youtube = '".$_POST["url_youtube"]."', modelo = '".$_POST["modelo"]."', contador = '".$contador."' where codigo = '".$dados_app_multi_plataforma["codigo"]."'") or die(mysqli_error($conexao));

$_SESSION["status_acao"] = status_acao($lang['lang_info_config_painel_resultado_ok'],"ok");

header("Location: /app-multi-plataforma");
exit();
}

if(query_string('1') == "remover-anuncio") {

$codigo = code_decode(query_string('2'),"D");

$dados_anuncio = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM app_multi_plataforma_anuncios where codigo = '".$codigo."'"));

mysqli_query($conexao,"Delete From app_multi_plataforma_anuncios where codigo = '".$codigo."'");

@unlink($_SERVER['DOCUMENT_ROOT']."/player".$dados_anuncio["banner"]);

$_SESSION["status_acao"] = status_acao("Anúncio removido com sucesso.","ok");

header("Location: /app-multi-plataforma");
exit();
}


if($_POST["notificacao"] == "sim") {

if($_FILES["notificacao_url_icone"]["tmp_name"]) {

@unlink($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/notificacao/icone-".$dados_stm["login"].".png");

$notificacao_url_icone = WideImage::load($_FILES["notificacao_url_icone"]["tmp_name"]);
$notificacao_url_icone = $notificacao_url_icone->resize(192, 192, 'fill');
$notificacao_url_icone->saveToFile($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/notificacao/icone-".$dados_stm["login"].".png");

} else {
$_SESSION["status_acao"] = status_acao($lang['lang_info_pagina_push_resultado_notificacao_erro_icone'],"erro");

header("Location: /app-multi-plataforma");
exit();
}

if($_FILES["notificacao_url_img"]["tmp_name"]) {

@unlink($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/notificacao/img-".$dados_stm["login"].".png");

$notificacao_url_img = WideImage::load($_FILES["notificacao_url_img"]["tmp_name"]);
$notificacao_url_img = $notificacao_url_img->resize(192, 192, 'fill');
$notificacao_url_img->saveToFile($_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/notificacao/img-".$dados_stm["login"].".png");

} else {
$_SESSION["status_acao"] = status_acao($lang['lang_info_pagina_push_resultado_notificacao_erro_imagem'],"erro");

header("Location: /app-multi-plataforma");
exit();
}

mysqli_query($conexao,"INSERT INTO app_multi_plataforma_notificacoes (codigo_stm,codigo_app,titulo,url_icone,url_imagem,url_link,mensagem) VALUES ('".$dados_stm["codigo"]."','".$dados_app_multi_plataforma["codigo"]."','".utf8_encode($_POST["notificacao_titulo"])."','/app-multi-plataforma/notificacao/icone-".$dados_stm["login"].".png','/app-multi-plataforma/notificacao/img-".$dados_stm["login"].".png','".$_POST["notificacao_link"]."','".utf8_encode($_POST["notificacao_msg"])."')") or die("Error when add notify to data base: ".mysqli_error($conexao));

$_SESSION["status_acao"] = status_acao($lang['lang_info_pagina_push_resultado_notificacao_ok'],"ok");

header("Location: /app-multi-plataforma");
exit();
}

if($_POST["acao_form"] == "anuncio") {

if($_FILES["banner"]["tmp_name"]) {

$banner_ext = substr($_FILES['banner']['name'], strrpos($_FILES['banner']['name'], '.')+1);
$banner = "banner-".$dados_stm["login"]."-".time().".".$banner_ext;

@copy($_FILES["banner"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/player/app-multi-plataforma/banner/".$banner);

} else {
$_SESSION["status_acao"] = status_acao("A imagem do banner deve ser enviada e deve ter o tamanho de 400x60px.","erro");
header("Location: /app-multi-plataforma");
exit();
}

mysqli_query($conexao,"INSERT INTO app_multi_plataforma_anuncios (codigo_app,nome,banner,link,data_cadastro,exibicoes,cliques) VALUES ('".$dados_app_multi_plataforma["codigo"]."','".$_POST["anuncio_nome"]."','/app-multi-plataforma/banner/".$banner."','".$_POST["anuncio_link"]."',NOW(),'0','0')") or die("Error when add notify to data base: ".mysqli_error($conexao));

$_SESSION["status_acao"] = status_acao("Anúncio cadastrado com sucesso.","ok");

header("Location: /app-multi-plataforma");
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
<link href="/inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<link href="inc/estilo-streaming.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="inc/ajax-streaming.js"></script>
<script type="text/javascript" src="inc/javascript.js"></script>
<script type="text/javascript" src="inc/javascript-abas.js"></script>
<script type="text/javascript" src="/admin/inc/tinymce/tiny_mce.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"rel="stylesheet">
<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"rel="stylesheet"type="text/css">
<style>body{background: #939393;}.input-group-addon, .input-group-btn {width:0!important}.form-control {height:34px!important}small { font-size: 11px!important; }.tab {padding-top: 5px!important; height: 22px!important;}.dynamic-tab-pane-control .tab-row .tab.selected{padding-top: 5px!important; height: 22px!important;}</style>
<script type="text/javascript">
   window.onload = function() {
	fechar_log_sistema();
   };
   function abrir_log_sistema_app() {
	
	window.parent.document.getElementById('log-sistema-conteudo').innerHTML = "<img src='/img/ajax-loader.gif' />";
	window.parent.document.getElementById('log-sistema-fundo').style.display = "block";
	window.parent.document.getElementById('log-sistema').style.display = "block";

}
</script>
</head>

<body>
<div id="sub-conteudo">
  <?php
if($_SESSION['status_acao']) {

$status_acao = stripslashes($_SESSION['status_acao']);

echo '<table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px">'.$status_acao.'</table>';

unset($_SESSION['status_acao']);
}
?>
    <div id="quadro">
      <div id="quadro-topo"><strong>App Multi Plataforma</strong></div>
      <div class="texto_medio" id="quadro-conteudo">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td height="25"><div class="tab-pane" id="tabPane1">
            <?php if(!empty($dados_app_multi_plataforma["nome"]) && !empty($dados_app_multi_plataforma["url_logo"])) { ?>
              <div class="tab-page" id="tabPage1">
                <h2 class="tab"><?php echo $lang['lang_info_streaming_app_android_tab_app_pronto']; ?></h2>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="40%" height="400" align="center">
      <?php if($dados_app_multi_plataforma["modelo"] == 1) { ?>
      <iframe src="https://<?php echo $url_player; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>?app-multi=preview" style="width:300px; height:550px" frameborder="0" onmousewheel=""></iframe>
    <?php } else { ?>
      <iframe src="https://<?php echo $url_player; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>?app-multi=preview" style="width:300px; height:650px" frameborder="0" onmousewheel=""></iframe>
    <?php }?>
  </td>
    <td width="60%" align="center" style="padding:10px"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="50%" height="85" align="center"><img src="https://<?php echo $url_player; ?>/app-multi-plataforma/img-instalar-app-android.png" width="150" height="48" /></td>
        <td width="50%" align="center"><textarea name="textarea" readonly="readonly" style="width:95%; height:70px;font-size:10px" onmouseover="this.select()"><a href="https://<?php echo $url_player; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>" target="_blank"><img src="https://<?php echo $url_player; ?>/app-multi-plataforma/img-instalar-app-android.png" width="150" height="48" /></a></textarea></td>
      </tr>
      <tr>
        <td height="85" align="center"><img src="https://<?php echo $url_player; ?>/app-multi-plataforma/img-instalar-app-iphone.png" width="150" height="48" /></td>
        <td align="center"><textarea name="textarea" readonly="readonly" style="width:95%; height:70px;font-size:10px" onmouseover="this.select()"><a href="https://<?php echo $url_player; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>" target="_blank"><img src="https://<?php echo $url_player; ?>/app-multi-plataforma/img-instalar-app-iphone.png" width="150" height="48" /></a></textarea></td>
      </tr>
      <tr>
        <td height="85" align="center"><img src="https://<?php echo $url_player; ?>/app-multi-plataforma/img-instalar-app-windows.png" width="150" height="48" /></td>
        <td align="center"><textarea name="textarea" readonly="readonly" style="width:95%; height:70px;font-size:10px" onmouseover="this.select()"><a href="https://<?php echo $url_player; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>" target="_blank"><img src="https://<?php echo $url_player; ?>/app-multi-plataforma/img-instalar-app-windows.png" width="150" height="48" /></a></textarea></td>
      </tr>
      <tr>
        <td height="110" align="center"><img width="auto" height="100" src="https://qrcode.tec-it.com/API/QRCode?size=Small&data=https://<?php echo $url_player; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>" /></td>
        <td align="center"><textarea name="textarea" readonly="readonly" style="width:95%; height:60px;font-size:10px" onmouseover="this.select()"><img src="https://qrcode.tec-it.com/API/QRCode?size=Small&data=https://<?php echo $url_player; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>" width="auto" height="200" /></textarea></td>
      </tr>
      <tr>
        <td height="75" align="center" class="texto_padrao_pequeno"><i class="fa fa-external-link" style="font-size:32px"></i><br>Link Direto</td>
        <td align="center"><textarea name="textarea" readonly="readonly" style="width:95%; height:40px;font-size:10px" onmouseover="this.select()">https://<?php echo $url_player; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?></textarea></td>
      </tr>
      <?php if($dados_app_multi_plataforma["apk_criado"] == "sim") { ?>
      <tr>
        <td height="65" align="center" class="texto_padrao_pequeno"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: auto; height: 32px;margin-bottom: 3px;"><path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z"/></svg><br>Google Play</td>
        <td align="center"><button type="button" class="btn btn-sm btn-info" style=" font-size: 12px; " onclick="window.open('/app/apps/<?php echo $dados_app_multi_plataforma["apk_zip"]; ?>?<?php echo time(); ?>')"><i class="fa fa-download"></i> <?php echo $lang['lang_info_pagina_app_multi_botao_google_play']; ?></button></td>
      </tr>
      <?php } ?>
      <tr>
        <td height="60" colspan="2" align="center" class="texto_padrao"><?php echo $lang['lang_info_streaming_app_android_info_instalar_app2']; ?></td>
        </tr>
    </table></td>
  </tr>
</table></div>
<?php } ?>
<?php if(empty($dados_app_multi_plataforma["nome"]) && empty($dados_app_multi_plataforma["url_logo"])) { ?>
<div class="tab-page" id="tabPage2">
                <h2 class="tab"><?php echo $lang['lang_info_pagina_app_multi_tab_criar']; ?></h2>
  <form method="post" action="/app-multi-plataforma" style="padding:0px; margin:0px" enctype="multipart/form-data">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px; background-color: #C1E0FF; border: #006699 1px solid">
      <tr>
        <td width="35" height="60" align="center" scope="col"><img src="/img/icones/ajuda.gif" width="16" height="16" /></td>
        <td align="left" class="texto_padrao_pequeno" scope="col" style="color:#003366"><?php echo $lang['lang_info_streaming_app_android_info_configuracoes']; ?></td>
      </tr>
    </table>
     <table width="100%" border="0" cellpadding="0" cellspacing="0">
     <tr>
      <td width="20%" height="40" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_tv_nome']; ?></td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="nome" type="text" id="nome" class="form-control" value="<?php echo $dados_app_multi_plataforma["nome"]; ?>" /></td>
    </tr>
    <tr>
      <td width="21%" height="50" class="texto_padrao_destaque">Modelo Layout</td>
      <td width="79%" class="texto_padrao_pequeno">
        <select name="modelo" id="modelo" style="width:100px; height:30px">
          <option value="1"<?php if($dados_app_multi_plataforma["modelo"] == "1") { echo ' selected="selected"'; } ?>>Layout 1</option>
          <option value="2"<?php if($dados_app_multi_plataforma["modelo"] == "2") { echo ' selected="selected"'; } ?>>Layout 2</option>
        </select>
      </td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_texto']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="cor_texto" style="width:100px; height:30px" value="<?php echo $dados_app_multi_plataforma['cor_texto']; ?>" /></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_menu_claro']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="cor_menu_claro" style="width:100px; height:30px" value="<?php echo $dados_app_multi_plataforma['cor_menu_claro']; ?>" /></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_menu_escuro']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="cor_menu_escuro" style="width:100px; height:30px" value="<?php echo $dados_app_multi_plataforma['cor_menu_escuro']; ?>" /></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_splash']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="cor_splash" style="width:100px; height:30px" value="<?php echo $dados_app_multi_plataforma['cor_splash']; ?>" /></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_versao']; ?></td>
      <td class="texto_padrao_pequeno">
        <select name="versao" id="versao" style="width:100px; height:30px">
          <option value="1.0" selected="selected">1.0</option>
          <option value="1.1">1.1</option>
          <option value="1.2">1.2</option>
          <option value="1.3">1.3</option>
          <option value="1.4">1.4</option>
          <option value="1.5">1.5</option>
          <option value="1.6">1.6</option>
          <option value="1.7">1.7</option>
          <option value="1.8">1.8</option>
          <option value="1.9">1.9</option>
          <option value="1.10">1.10</option>
          <option value="1.11">1.11</option>
          <option value="1.12">1.12</option>
          <option value="1.13">1.13</option>
          <option value="1.14">1.14</option>
          <option value="1.15">1.15</option>
          <option value="1.16">1.16</option>
          <option value="1.17">1.17</option>
          <option value="1.18">1.18</option>
          <option value="1.19">1.19</option>
          <option value="1.20">1.20</option>
          <option value="2.0">2.0</option>
          <option value="2.1">2.1</option>
          <option value="2.2">2.2</option>
          <option value="2.3">2.3</option>
          <option value="2.4">2.4</option>
          <option value="2.5">2.5</option>
          <option value="2.6">2.6</option>
          <option value="2.7">2.7</option>
          <option value="2.8">2.8</option>
          <option value="2.9">2.9</option>
          <option value="3.0">3.0</option>
          <option value="4.0">4.0</option>
          <option value="5.0">5.0</option>
        </select></td>
    </tr>
    <tr id="row_package_personalizar">
      <td height="40" class="texto_padrao_destaque">Package</td>
      <td class="texto_padrao_pequeno"><div class="input-group"><div class="input-group-addon" style="font-size:12px">com.stmvideo.webtv.</div><input class="form-control" name="pkg_personalizado" type="text" id="pkg_personalizado" pattern="[a-z0-9.]+" style=" width:270px;height:30px;border-right: none;" onkeyup="bloquear_caracteres(this.id);" maxlength="36" value="<?php echo $package_atual; ?>" /><span class="input-group-addon" onclick="gerar_pkg();" style="cursor:pointer" title="Gerar Automaticamente/Auto Generate"><i class="fa fa-retweet"></i></span><span class="input-group-addon" id="botao_historico" style="cursor:pointer" title="Carregar Hist&oacute;rico/Load History"><i class="fa fa-history"></i></span></div></td>
    </tr>
    <tr id="row_package_historico" style="display:none">
      <td height="40" class="texto_padrao_destaque">Package</td>
      <td class="texto_padrao_pequeno"><div class="input-group"><div class="input-group-addon" style="font-size:12px">com.stmvideo.webtv.</div>
      <select name="pkg_personalizado_historico" id="pkg_personalizado_historico" style="width:290px;height:30px;font-size: 14px;">
      <option value="" selected="selected"></option>
<?php
$sql = mysqli_query($conexao,"SELECT * FROM app_packages where codigo_stm = '".$dados_stm["codigo"]."' ORDER by codigo DESC");
while ($dados_pkg = mysqli_fetch_array($sql)) {
echo '<option value="' . str_replace("com.stmvideo.webtv.","",$dados_pkg["package"]) . '">' . str_replace("com.stmvideo.webtv.","",$dados_pkg["package"]) . '</option>';
}

?>
  </select>
      <span class="input-group-addon" id="botao_personalizar" style="cursor:pointer" title="Digitar Manualmente/Type Manually"><i class="fa fa-pencil-square-o"></i></span></div></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_tv_logo']; ?></td>
      <td width="80%" class="texto_padrao_pequeno"><div class="input-group"><input name="logo" type="file" id="logo" class="form-control" style=" width: 361px; " /><span class="input-group-addon">PNG / 300x300</span></div></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque">Background</td>
      <td class="texto_padrao_pequeno"><div class="input-group"><input name="fundo" type="file" id="fundo" class="form-control" style=" width: 356px; " /><span class="input-group-addon">JPG / 640x1136</span></div></td>
    </tr>
          <tr>
            <td height="40" colspan="2" align="center"><input type="submit" class="botao" value="<?php echo $lang['lang_info_streaming_app_android_botao_submit']; ?>" onclick="abrir_log_sistema_app();" /><input name="acao_form" type="hidden" id="acao_form" value="criar" /></td>
          </tr>
  </table>
  </form>
                </div>
<?php } else { ?>
                <div class="tab-page" id="tabPage2">
                <h2 class="tab"><?php echo $lang['lang_info_streaming_app_android_tab_configurar_app']; ?></h2>
  <form method="post" action="/app-multi-plataforma" style="padding:0px; margin:0px" enctype="multipart/form-data">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px; background-color: #C1E0FF; border: #006699 1px solid">
      <tr>
        <td width="35" height="60" align="center" scope="col"><img src="/img/icones/ajuda.gif" width="16" height="16" /></td>
        <td align="left" class="texto_padrao_pequeno" scope="col" style="color:#003366"><?php echo $lang['lang_info_streaming_app_android_info_configuracoes']; ?></td>
      </tr>
    </table>
     <table width="100%" border="0" cellpadding="0" cellspacing="0">
     <tr>
      <td width="20%" height="40" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_tv_nome']; ?></td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="nome" type="text" id="nome" style="width:350px" value="<?php echo $dados_app_multi_plataforma["nome"]; ?>" /></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">E-mail</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="email" type="text" id="email" style="width:350px" value="<?php echo $dados_app_multi_plataforma["email"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?>
        </td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">Site</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="url_site" type="text" id="url_site" style="width:350px" value="<?php echo $dados_app_multi_plataforma["url_site"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">FaceBook</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="url_facebook" type="text" id="url_facebook" style="width:350px" value="<?php echo $dados_app_multi_plataforma["url_facebook"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">Twitter</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="url_twitter" type="text" id="url_twitter" style="width:350px" value="<?php echo $dados_app_multi_plataforma["url_twitter"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>    
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">Instagram</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="url_instagram" type="text" id="url_instagram" style="width:350px" value="<?php echo $dados_app_multi_plataforma["url_instagram"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr> 
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">Canal YouTube</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="url_youtube" type="text" id="url_youtube" style="width:350px" value="<?php echo $dados_app_multi_plataforma["url_youtube"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">WhatsApp</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="whatsapp" type="text" id="whatsapp" style="width:350px" value="<?php echo $dados_app_multi_plataforma["whatsapp"]; ?>" />
        <br />
        +00 00000000000 <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>
    <tr>
      <td width="20%" height="100" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_programacao']; ?></td>
      <td width="80%" class="texto_padrao_pequeno"><textarea name="text_prog" id="text_prog" style="width:350px" rows="5"><?php echo $dados_app_multi_plataforma["text_prog"]; ?></textarea></td>
    </tr>
    <tr>
      <td width="20%" height="100" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_historia']; ?></td>
      <td width="80%" class="texto_padrao_pequeno"><textarea name="text_hist" id="text_hist" style="width:350px" rows="5"><?php echo $dados_app_multi_plataforma["text_hist"]; ?></textarea></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">Modulo Chat</td>
      <td width="80%" class="texto_padrao_pequeno"><input name="ativar_chat" type="checkbox" value="sim" <?php if($dados_app_multi_plataforma["url_chat"]) { echo ' checked="checked"'; } ?> />
        <input name="url_chat" type="hidden" id="url_chat" value="<?php echo "/app-multi-plataforma/chat/".$dados_stm["login"].""; ?>" /></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">Contador Espectadores</td>
      <td width="80%" class="texto_padrao_pequeno"><input name="contador" type="checkbox" value="sim" <?php if($dados_app_multi_plataforma["contador"]) { echo ' checked="checked"'; } ?> />
    </tr>
    <tr>
      <td width="21%" height="50" class="texto_padrao_destaque">Modelo Layout <span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></td>
      <td width="79%" class="texto_padrao_pequeno">
        <select name="modelo" id="modelo" style="width:100px; height:30px">
          <option value="1"<?php if($dados_app_multi_plataforma["modelo"] == "1") { echo ' selected="selected"'; } ?>>Layout 1</option>
          <option value="2"<?php if($dados_app_multi_plataforma["modelo"] == "2") { echo ' selected="selected"'; } ?>>Layout 2</option>
        </select>
      </td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_texto']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="cor_texto" style="width:100px; height:30px" value="<?php echo $dados_app_multi_plataforma['cor_texto']; ?>" /></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_menu_claro']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="cor_menu_claro" style="width:100px; height:30px" value="<?php echo $dados_app_multi_plataforma['cor_menu_claro']; ?>" /></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_menu_escuro']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="cor_menu_escuro" style="width:100px; height:30px" value="<?php echo $dados_app_multi_plataforma['cor_menu_escuro']; ?>" /></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_splash']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="cor_splash" style="width:100px; height:30px" value="<?php echo $dados_app_multi_plataforma['cor_splash']; ?>" /></td>
    </tr>
    <tr>
      <td width="21%" height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_pagina_app_multi_botao_recompilar']; ?></td>
      <td width="79%" class="texto_padrao_pequeno"><input name="apk_criado" id="apk_criado" type="checkbox" value="nao" /> <?php echo $lang['lang_info_pagina_app_multi_recompilar_info']; ?></td>
    </tr>   
    <tr id="campo_versao" style="<?php if($dados_app_multi_plataforma["apk_criado"] == "nao") { ?>display: table-row; <?php } else { ?>display: none<?php } ?>">
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_versao']; ?></td>
      <td class="texto_padrao_pequeno">
        <select name="versao" id="versao" style="width:100px; height:30px">
          <option value="1.0" selected="selected">1.0</option>
          <option value="1.1">1.1</option>
          <option value="1.2">1.2</option>
          <option value="1.3">1.3</option>
          <option value="1.4">1.4</option>
          <option value="1.5">1.5</option>
          <option value="1.6">1.6</option>
          <option value="1.7">1.7</option>
          <option value="1.8">1.8</option>
          <option value="1.9">1.9</option>
          <option value="1.10">1.10</option>
          <option value="1.11">1.11</option>
          <option value="1.12">1.12</option>
          <option value="1.13">1.13</option>
          <option value="1.14">1.14</option>
          <option value="1.15">1.15</option>
          <option value="1.16">1.16</option>
          <option value="1.17">1.17</option>
          <option value="1.18">1.18</option>
          <option value="1.19">1.19</option>
          <option value="1.20">1.20</option>
          <option value="2.0">2.0</option>
          <option value="2.1">2.1</option>
          <option value="2.2">2.2</option>
          <option value="2.3">2.3</option>
          <option value="2.4">2.4</option>
          <option value="2.5">2.5</option>
          <option value="2.6">2.6</option>
          <option value="2.7">2.7</option>
          <option value="2.8">2.8</option>
          <option value="2.9">2.9</option>
          <option value="3.0">3.0</option>
          <option value="4.0">4.0</option>
          <option value="5.0">5.0</option>
        </select></td>
    </tr>
    <tr id="row_package_personalizar" style="<?php if($dados_app_multi_plataforma["apk_criado"] == "nao") { ?>display: table-row; <?php } else { ?>display: none<?php } ?>">
      <td height="40" class="texto_padrao_destaque">Package</td>
      <td class="texto_padrao_pequeno"><div class="input-group"><div class="input-group-addon" style="font-size:12px">com.stmvideo.webtv.</div><input class="form-control" name="pkg_personalizado" type="text" id="pkg_personalizado" pattern="[a-z0-9.]+" style=" width:270px;height:30px;border-right: none;" onkeyup="bloquear_caracteres(this.id);" maxlength="36" value="<?php echo $package_atual; ?>" /><span class="input-group-addon" onclick="gerar_pkg();" style="cursor:pointer" title="Gerar Automaticamente/Auto Generate"><i class="fa fa-retweet"></i></span><span class="input-group-addon" id="botao_historico" style="cursor:pointer" title="Carregar Hist&oacute;rico/Load History"><i class="fa fa-history"></i></span></div></td>
    </tr>
    <tr id="row_package_historico" style="display:none">
      <td height="40" class="texto_padrao_destaque">Package</td>
      <td class="texto_padrao_pequeno"><div class="input-group"><div class="input-group-addon" style="font-size:12px">com.stmvideo.webtv.</div>
      <select name="pkg_personalizado_historico" id="pkg_personalizado_historico" style="width:290px;height:30px;font-size: 14px;">
      <option value="" selected="selected"></option>
<?php
$sql = mysqli_query($conexao,"SELECT * FROM app_packages where codigo_stm = '".$dados_stm["codigo"]."' ORDER by codigo DESC");
while ($dados_pkg = mysqli_fetch_array($sql)) {
echo '<option value="' . str_replace("com.stmvideo.webtv.","",$dados_pkg["package"]) . '">' . str_replace("com.stmvideo.webtv.","",$dados_pkg["package"]) . '</option>';
}

?>
  </select>
      <span class="input-group-addon" id="botao_personalizar" style="cursor:pointer" title="Digitar Manualmente/Type Manually"><i class="fa fa-pencil-square-o"></i></span></div></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_tv_logo']; ?></td>
      <td width="80%" class="texto_padrao_pequeno"><div class="input-group"><input name="logo" type="file" id="logo" class="form-control" style=" width: 361px; " /><span class="input-group-addon">PNG / 300x300</span></div></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque">Background</td>
      <td class="texto_padrao_pequeno"><div class="input-group"><input name="fundo" type="file" id="fundo" class="form-control" style=" width: 356px; " /><span class="input-group-addon">JPG / 640x1136</span></div></td>
    </tr>
    <tr>
        <td height="40" colspan="2" align="center"><input type="submit" class="botao" value="<?php echo $lang['lang_botao_titulo_alterar_config']; ?>" onclick="abrir_log_sistema_app();" /><input name="acao_form" type="hidden" id="acao_form" value="configurar" /></td>
    </tr>
  </table>
  </form>
                </div>		 
    <div class="tab-page" id="tabPage4">
                <h2 class="tab"><?php echo $lang['lang_info_pagina_anuncio_tab_titulo_cadastrar']; ?>&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></h2>
                <form action="/app-multi-plataforma" method="post" enctype="multipart/form-data">
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid; border-left:#CCCCCC 1px solid; border-right:#CCCCCC 1px solid;">
                      <tr>
                        <td width="150" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_pagina_anuncio_nome']; ?></td>
                        <td width="720" align="left"><input type="text" name="anuncio_nome" style="width:300px" value="" required="required" /></td>
                      </tr>
                      <tr>
                        <td width="150" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_pagina_anuncio_banner']; ?></td>
                        <td width="720" align="left"><input name="banner" type="file" id="banner" style="width:300px" />
        <br />400x60px</td>
                      </tr>
                      <tr>
                        <td height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">Link Click</td>
                        <td align="left" class="texto_padrao_pequeno"><input type="text" name="anuncio_link" style="width:300px" value="" placeholder="https://" />
                        <br />(N&atilde;o &eacute; obrigat&oacute;rio)</td>
                      </tr>
                      <tr>
                        <td height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;">&nbsp;</td>
                        <td align="left"><input type="submit" class="botao" value="<?php echo $lang['lang_botao_titulo_cadastrar']; ?>" /><input name="acao_form" type="hidden" id="acao_form" value="anuncio" /></td>
                      </tr>
                    </table>
                    </form>
                </div>
                <div class="tab-page" id="tabPage5">
                <h2 class="tab"><?php echo $lang['lang_info_pagina_anuncio_tab_titulo']; ?>&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></h2>
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#FFFF66; border:#DFDF00 1px solid">
                      <tr>
                        <td width="30" height="25" align="center" scope="col"><img src="/img/icones/atencao.png" width="16" height="16" /></td>
                        <td width="840" align="left" class="texto_pequeno_erro" scope="col">Os an&uacute;ncios s&atilde;o exibidos aleatoriamente a cada 1 minuto.</td>
                      </tr>
                    </table>
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style=" border:#D5D5D5 1px solid; " id="tab" class="sortable">
    <tr style="background:url(/img/img-fundo-titulo-tabela.png) repeat-x; cursor:pointer">
      <td width="500" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;Nome</td>
      <td width="150" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;Data Cadastro</td>
      <td width="120" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;Exibi&ccedil;&otilde;es</td>
      <td width="120" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid; border-right:#D5D5D5 1px solid;">&nbsp;Cliques</td>
      <td width="100" height="23" align="left" class="texto_padrao_destaque" style="border-bottom:#D5D5D5 1px solid;">&nbsp;A&ccedil;&otilde;es</td>
    </tr>
<?php
$sql = mysqli_query($conexao,"SELECT * FROM app_multi_plataforma_anuncios WHERE codigo_app = '".$dados_app_multi_plataforma["codigo"]."' ORDER by exibicoes DESC");
while ($dados_anuncio = mysqli_fetch_array($sql)) {

echo "<tr>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>&nbsp;".$dados_anuncio["nome"]."<br>&nbsp;<small><a href='".$dados_anuncio["link"]."' target='_blank'>".$dados_anuncio["link"]."</a></small></td>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>&nbsp;".formatar_data($dados_stm["formato_data"], $dados_anuncio["data_cadastro"], $dados_stm["timezone"])."</td>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>&nbsp;".$dados_anuncio["exibicoes"]."</td>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>&nbsp;".$dados_anuncio["cliques"]."</td>
<td height='25' align='left' scope='col' class='texto_padrao_pequeno'>&nbsp;<a href='/app-multi-plataforma/remover-anuncio/".code_decode($dados_anuncio["codigo"],"E")."'>[Remover]</a></td>
</tr>";

}
?>
  </table>
                </div>
                <div class="tab-page" id="tabPage1">
                <h2 class="tab"><?php echo $lang['lang_info_pagina_push_tab_titulo']; ?></h2>
                <form method="post" action="/app-multi-plataforma" style="padding:0px; margin:0px" enctype="multipart/form-data">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border-left:#CCCCCC 1px solid; border-right:#CCCCCC 1px solid; border-bottom:#CCCCCC 1px solid;">
        <tr>
                  <td width="20%" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_pagina_push_tab_enviar_titulo']; ?></td>
                  <td width="80%" align="left" class="texto_padrao_pequeno"><input name="notificacao_titulo" type="text" class="input" id="notificacao_titulo" style="width:350px;" value="" required="required" /></td>
                </tr>
                <tr>
                  <td width="20%" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_pagina_push_tab_enviar_url_icone']; ?></td>
                  <td width="80%" align="left" class="texto_padrao_pequeno"><input name="notificacao_url_icone" type="text" class="input" id="notificacao_url_icone" style="width:350px;" value="" placeholder="https://" required="required" /><br />Min. 192x192 px</td>
                </tr>
                <tr>
                  <td width="20%" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_pagina_push_tab_enviar_url_imagem']; ?></td>
                  <td width="80%" align="left" class="texto_padrao_pequeno"><input name="notificacao_url_img" type="text" class="input" id="notificacao_url_img" style="width:350px;" value="" placeholder="https://" /><br />Min. 360x180 px</td>
                </tr>
                <tr>
                  <td width="20%" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_pagina_push_tab_enviar_link']; ?></td>
                  <td width="80%" align="left" class="texto_padrao_pequeno"><input name="notificacao_link" type="text" class="input" id="notificacao_link" style="width:350px;" value="" placeholder="https://" /></td>
                </tr>
                <tr>
                  <td width="20%" height="30" align="left" class="texto_padrao_destaque" style="padding-left:5px;"><?php echo $lang['lang_info_pagina_push_tab_enviar_mensagem']; ?></td>
                  <td width="80%" align="left" class="texto_padrao_pequeno"><textarea name="notificacao_msg" id="notificacao_msg" rows="10" onkeyup="contar_caracteres(this.id,'430');" style="width:350px;" required="required"></textarea><br /><span id="total_caracteres" class="texto_padrao_pequeno">160</span>&nbsp;(Max 160)</td>
                </tr>
       <tr>
            <td height="40" colspan="2" align="center"><input type="submit" class="botao" value="<?php echo $lang['lang_info_pagina_push_tab_enviar_botao']; ?>" onclick="abrir_log_sistema_app();" /><input name="notificacao" type="hidden" id="notificacao" value="sim" /></td>
          </tr>
    </table>
</form></div>      
                <?php } ?>       
            </div></td>
          </tr>
        </table>
      </div>
    </div>
</div>
<br><br><br><br>
<!-- Início div log do sistema -->
<div id="log-sistema-fundo"></div>
<div id="log-sistema">
<div id="log-sistema-botao"><img src="img/icones/img-icone-fechar.png" onclick="document.getElementById('log-sistema-fundo').style.display = 'none';document.getElementById('log-sistema').style.display = 'none';" style="cursor:pointer" title="<?php echo $lang['lang_titulo_fechar']; ?>" /></div>
<div id="log-sistema-conteudo"><img src='/img/ajax-loader.gif' /></div>
</div>
<!-- Fim div log do sistema -->
<script language='JavaScript' type='text/javascript'>
tinyMCE.init({
  mode : 'exact',
  elements : 'text_prog,text_hist',
  theme : "advanced",
  skin : "o2k7",
  skin_variant : "silver",
  plugins : "table,inlinepopups,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking",
  dialog_type : 'modal',
  force_br_newlines : true,
  force_p_newlines : false,
  theme_advanced_toolbar_location : 'top',
  theme_advanced_toolbar_align : 'left',
  theme_advanced_path_location : 'bottom',
  theme_advanced_buttons1 : 'newdocument,|,bold,italic,underline,|,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,justifyfull,|,undo,redo,|,link,unlink,image,media,|,code',
  theme_advanced_buttons2 : '',
  theme_advanced_buttons3 : '',
  theme_advanced_resize_horizontal : false,
  theme_advanced_resizing : false,
  valid_elements : "*[*]"
});


function bloquear_caracteres(id) {
var novo = document.getElementById(id).value.replace(/[^a-z0-9]/gi,'');
var final = novo.toLowerCase();
document.getElementById(id).value = final;
}
function gerar_pkg() {

var nome_app = document.getElementById("nome").value;

if(nome_app == "") {
alert("Ooops! Primeiro deve informar o nome da sua webtv.");
document.getElementById("nome").focus();
} else {

var parte_nome_app = nome_app.replace(/[^a-z0-9]/gi,'').substring(0,15).toLowerCase();
var parte_rnd = Array.from(Array(10), () => Math.floor(Math.random() * 36).toString(36)).join('');
var pkg_gerado = parte_nome_app+parte_rnd;
document.getElementById("pkg_personalizado").value = pkg_gerado;

}

}
$('#botao_historico, #botao_personalizar').click(function() {
  $('#row_package_personalizar').toggle();
  $('#row_package_historico').toggle();
  $('#pkg_personalizado').val('');
});

var recompilar = document.getElementById("apk_criado");
recompilar.addEventListener("click",function(e){
    
    if (recompilar.checked == true) {
      document.getElementById("campo_versao").style.display = "table-row";
      document.getElementById("row_package_personalizar").style.display = "table-row";
    } else {
      document.getElementById("campo_versao").style.display = "none";
      document.getElementById("row_package_personalizar").style.display = "none";
    }

},false);

</script>
</body>
</html>
