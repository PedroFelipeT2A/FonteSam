<?php
require_once("admin/inc/protecao-final.php");
require_once("app/wideimage/WideImage.php");

//Funções
function formatar_nome_radio2($nome){$characteres=array('S'=>'S','s'=>'s','Ð'=>'Dj','Z'=>'Z','z'=>'z','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','f'=>'f','¹'=>'','²'=>'','&'=>'e','³'=>'','£'=>'','$'=>'','%'=>'','¨'=>'','§'=>'','º'=>'','ª'=>'','©'=>'','Ã£'=>'','('=>'',')'=>'',"'"=>'','@'=>'','='=>'',':'=>'','!'=>'','?'=>'','...'=>'','®'=>'','/'=>'','´'=>'','+'=>'','*'=>'','['=>'',']'=>'');return strtr($nome,$characteres);}function nome_app_play2($texto){$characteres=array('S'=>'S','s'=>'s','Ð'=>'Dj','Z'=>'Z','z'=>'z','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','f'=>'f','¹'=>'','²'=>'','&'=>'e','³'=>'','£'=>'','$'=>'','%'=>'','¨'=>'','§'=>'','º'=>'','ª'=>'','©'=>'','Ã£'=>'','('=>'',')'=>'',"'"=>'','@'=>'','='=>'',':'=>'','!'=>'','?'=>'','...'=>'',' '=>'','-'=>'','^'=>'','~'=>'','.'=>'','|'=>'',','=>'','<'=>'','>'=>'','{'=>'','}'=>'','®'=>'','/'=>'','´'=>'','+'=>'','*'=>'','['=>'',']'=>'');return strtolower(strtr($texto,$characteres));}function nome_app_apk2($texto){$characteres=array('S'=>'S','s'=>'s','Ð'=>'Dj','Z'=>'Z','z'=>'z','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','ý'=>'y','þ'=>'b','ÿ'=>'y','f'=>'f','¹'=>'','²'=>'','&'=>'e','³'=>'','£'=>'','$'=>'','%'=>'','¨'=>'','§'=>'','º'=>'','ª'=>'','©'=>'','Ã£'=>'','('=>'',')'=>'',"'"=>'','@'=>'','='=>'',':'=>'','!'=>'','?'=>'','...'=>'',' '=>'','-'=>'','^'=>'','~'=>'','.'=>'','|'=>'',','=>'','<'=>'','>'=>'','{'=>'','}'=>'',' '=>'','®'=>'','/'=>'','´'=>'','+'=>'','*'=>'','['=>'',']'=>'');return strtr($texto,$characteres);}function copiar_source2($DirFont,$DirDest){mkdir($DirDest);if($dd=opendir($DirFont)){while(false!==($Arq=readdir($dd))){if($Arq!="."&&$Arq!=".."){$PathIn="$DirFont/$Arq";$PathOut="$DirDest/$Arq";if(is_dir($PathIn)){copiar_source2($PathIn,$PathOut);chmod($PathOut,0777);}elseif(is_file($PathIn)){copy($PathIn,$PathOut);chmod($PathOut,0777);}}}closedir($dd);}}function criar_arquivo_config2($arquivo,$conteudo){$fd=fopen($arquivo,"w");fputs($fd,$conteudo);fclose($fd);}function browse2($dir){global $filenames;if($handle=opendir($dir)){while(false!==($file=readdir($handle))){if($file!="."&&$file!=".."&&is_file($dir.'/'.$file)){$filenames[]=$dir.'/'.$file;}else if($file!="."&&$file!=".."&&is_dir($dir.'/'.$file)){browse2($dir.'/'.$file);}}closedir($handle);}return $filenames;}function replace2($arquivo,$string_atual,$string_nova){$str=file_get_contents($arquivo);$str=str_replace($string_atual,$string_nova,$str);file_put_contents($arquivo,$str);}function remover_source_app2($Dir){if($dd=@opendir($Dir)){while(false!==($Arq=@readdir($dd))){if($Arq!="."&&$Arq!=".."){$Path="$Dir/$Arq";if(is_dir($Path)){remover_source_app2($Path);}elseif(is_file($Path)){@unlink($Path);}}}@closedir($dd);}@rmdir($Dir);}function mudar_permissao2($Dir){if($dd=opendir($Dir)){while(false!==($Arq=readdir($dd))){if($Arq!="."&&$Arq!=".."){$Path="$Dir/$Arq";@chmod($Path,0777);}}closedir($dd);}}

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where login = '".$_SESSION["login_logado"]."'"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores where codigo = '".$dados_stm["codigo_servidor"]."'"));

$url_player = (!empty($dados_revenda["dominio_padrao"])) ? "playerv.".$dados_revenda["dominio_padrao"]."" : "playerv.".$dados_config["dominio_padrao"]."";

$dados_app_criado = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM apps where codigo_stm = '".$dados_stm["codigo"]."'"));

if($_POST["acao_form"] == "criar") {

// Valida extensão
if($_FILES["logo"]["type"] != "image/png") {
die ("<script> alert(\"Logo deve ser PNG\\n\\nLogo must be PNG\");
       window.location = 'javascript:history.back(-1)'; </script>");
}

if($_FILES["icone"]["type"] != "image/png") {
die ("<script> alert(\"Icone deve ser PNG\\n\\nIcon must be PNG\");
       window.location = 'javascript:history.back(-1)'; </script>");
}

if($_FILES["fundo"]["type"] != "image/jpeg") {
die ("<script> alert(\"Fundo deve ser JPG\\n\\nBackground must be JPG\");
       window.location = 'javascript:history.back(-1)'; </script>");
}

if(strlen($_POST["webtv_nome"]) > 30) {
die ("<script> alert(\"Nome da TV deve ter maximo 30 caracteres.\\n\\nTV name max 30 character.\");
       window.location = 'javascript:history.back(-1)'; </script>");
}

if(empty($_POST["webtv_nome"])) {
die ("<script> alert(\"Nome da TV esta vazio.\\n\\nTV name is empty.\");
       window.location = 'javascript:history.back(-1)'; </script>");
}

// Verifica se o primeiro caracter é numérico
if(preg_match('/^\d/',$_POST["webtv_nome"])) {
die ("<script> alert(\"Nome da TV não pode iniciar com numeros.\\n\\nTV name cant stat with number.\");
       window.location = 'javascript:history.back(-1)'; </script>");
}

$webtv_nome = $_POST["webtv_nome"];
$login = $_POST["login"];

$source = "source-app-webrtc";

$hash = nome_app_play2($webtv_nome)."_".md5($webtv_nome);
$package = "com.stmvideo.webtv.".nome_app_play2($webtv_nome)."";
$package_path = str_replace(".","/",$package);

$patch_dir_apps = "/home/painelvideo/public_html/app/apps";
$patch_app = "/home/painelvideo/public_html/app/apps/".$hash."";
$patch_tmp = "/home/painelvideo/public_html/app/apps/tmp";
$patch_player = "/home/painelvideo/public_html/player/app";


//Bug fix - Remove pasta do app caso exista
remover_source_app2("/home/painelvideo/public_html/app/apps/".$hash."/");
@unlink("/home/painelvideo/public_html/app/apps/".$hash.".zip");
@unlink("/home/painelvideo/public_html/player/app/logo-".$dados_stm["login"].".png");
@unlink("/home/painelvideo/public_html/player/app/icone-".$dados_stm["login"].".png");
@unlink("/home/painelvideo/public_html/player/app/background-".$dados_stm["login"].".jpg");


@copy($_FILES["logo"]["tmp_name"],"".$patch_tmp."/logo_".$hash.".png");
@copy($_FILES["icone"]["tmp_name"],"".$patch_tmp."/icone_".$hash.".png");
@copy($_FILES["fundo"]["tmp_name"],"".$patch_tmp."/fundo_".$hash.".jpg");

// Valida a dimensão(largura x altura) das imagens
list($logo_width, $logo_height, $logo_type, $logo_attr) = getimagesize("".$patch_tmp."/logo_".$hash.".png");
list($icone_width, $icone_height, $icone_type, $icone_attr) = getimagesize("".$patch_tmp."/icone_".$hash.".png");
list($fundo_width, $fundo_height, $fundo_type, $fundo_attr) = getimagesize("".$patch_tmp."/fundo_".$hash.".jpg");

if($logo_width != 300 || $logo_height != 300) {
die ("<script> alert(\"Ooops!\\n\\nA logomarca esta com dimensão inválida!\\n\\nEnvie uma logomarca com 300 pixels de largura e 300 pixels de altura.\");
       window.location = 'javascript:history.back(-1)'; </script>");
}

if($icone_width != 144 || $icone_height != 144) {
die ("<script> alert(\"Ooops!\\n\\nO ícone esta com dimensão inválida!\\n\\nEnvie um ícone com 144 pixels de largura e 144 pixels de altura.\");
       window.location = 'javascript:history.back(-1)'; </script>");
}

if($fundo_width != 640 || $fundo_height != 1136) {
die ("<script> alert(\"Ooops!\\n\\nO fundo esta com dimensão inválida!\\n\\nEnvie um fundo com 640 pixels de largura e 1136 pixels de altura.\");
       window.location = 'javascript:history.back(-1)'; </script>");
}

copiar_source2("/home/painelvideo/public_html/app/".$source."/", $patch_app);

@rename("".$patch_app."/app/src/main/java/com/stmvideo/webtv/webtv_nome","".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play2($webtv_nome)."");
@rename("".$patch_app."/app/src/androidTest/java/com/stmvideo/webtv/webtv_nome","".$patch_app."/app/src/androidTest/java/com/stmvideo/webtv/".nome_app_play2($webtv_nome)."");
@rename("".$patch_app."/app/src/test/java/com/stmvideo/webtv/webtv_nome","".$patch_app."/app/src/test/java/com/stmvideo/webtv/".nome_app_play2($webtv_nome)."");

// Copia o ícone
$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(72, 72);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-hdpi/icone.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(72, 72);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-mdpi/icone.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(96, 96);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xhdpi/icone.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(144, 144);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xxhdpi/icone.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(192, 192);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-xxxhdpi/icone.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(32, 32);
$icone->saveToFile("".$patch_player."/icone-".$dados_stm["login"].".png");

// Copia a logo
$logo = WideImage::load("".$patch_tmp."/logo_".$hash.".png");
$logo = $logo->resize(300, 300);
$logo->saveToFile("".$patch_player."/logo-".$dados_stm["login"].".png");

// Copia o fundo
$fundo = WideImage::load("".$patch_tmp."/fundo_".$hash.".jpg");
$fundo = $fundo->resize(640, 1136);
$fundo->saveToFile("".$patch_player."/background-".$dados_stm["login"].".jpg");

// Cria icone para o Play
$play_icone = WideImage::load("".$patch_tmp."/logo_".$hash.".png");
$play_icone = $play_icone->resize(512, 512);
$play_icone->saveToFile("".$patch_app."/arquivos_google_play/img-play-logo.png");

// Cria a imagem de destaque para o Play com a logo da radio
$destaque = WideImage::load("".$patch_app."/arquivos_google_play/img-play-destaque.jpg");
$logo_destaque = WideImage::load("".$patch_tmp."/logo_".$hash.".png");
$play_destaque = $destaque->merge($logo_destaque, 'center', 'center', 100);
$play_destaque->saveToFile("".$patch_app."/arquivos_google_play/img-play-destaque.jpg");

// Cria o print do app para o Play com a logo da radio
$printapp_base = WideImage::load("".$patch_app."/arquivos_google_play/img-play-app.png");
$printapp_fundo = WideImage::load("".$patch_tmp."/fundo_".$hash.".jpg");
$printapp_logo = WideImage::load("".$patch_tmp."/logo_".$hash.".png");

$play_printapp = $printapp_fundo->merge($printapp_base, 'center', 'center', 100);
$play_printapp = $play_printapp->merge($printapp_logo, 'center', 'top+120', 100);
$play_printapp->saveToFile("".$patch_app."/arquivos_google_play/img-play-app.png");

// Escreve nome da radio no print do app
$printapp = WideImage::load("".$patch_app."/arquivos_google_play/img-play-app.png");
$printapp_canvas = $printapp->getCanvas();
$printapp_canvas->useFont("".$patch_app."/Roboto-Regular.ttf", 25, $printapp->allocateColor(255, 255, 255));
$printapp_canvas->writeText("center", "top+620", formatar_nome_radio2($webtv_nome));
$printapp->saveToFile("".$patch_app."/arquivos_google_play/img-play-app.png");

// Cria tamanhos diferenciados par google play
$printapp_800x1280 = WideImage::load("".$patch_app."/arquivos_google_play/img-play-app.png");
$printapp_800x1280 = $printapp_800x1280->resize(800, 1280, 'fill');
$printapp_800x1280->saveToFile("".$patch_app."/arquivos_google_play/img-play-app-800x1280.png");

// Muda o package
replace2("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play2($webtv_nome)."/MainActivity.java","com.stmvideo.webtv.webtv_nome",$package);
replace2("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play2($webtv_nome)."/Player.java","com.stmvideo.webtv.webtv_nome",$package);

// Dados da Radio
replace2("".$patch_app."/app/src/main/res/values/strings.xml","WEBTV_NOME",utf8_encode(str_replace("&","&amp;",$webtv_nome)));
replace2("".$patch_app."/app/src/main/res/values/strings.xml","URL_PLAYER","https://".$url_player."/player-app-webrtc/".$dados_stm["login"]."");

replace2("".$patch_app."/app/build.gradle","com.stmvideo.webtv.webtv_nome",$package);
replace2("".$patch_app."/app/src/main/AndroidManifest.xml","com.stmvideo.webtv.webtv_nome",$package);

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

// Muda o idioma do app conforme o idioma do painel
if($_POST["idioma_painel"] == "pt-br") {

replace2("".$patch_app."/app/src/main/res/values/strings.xml","MSG_SHARE","Gostaria de compartilhar meu app favorito com você.");

} elseif($_POST["idioma_painel"] == "en-us") {

replace2("".$patch_app."/app/src/main/res/values/strings.xml","MSG_SHARE","I want to share my favorite app with you.");

} else {

replace2("".$patch_app."/app/src/main/res/values/strings.xml","MSG_SHARE","Me gustaria compartir mi app favorito con tu.");

}

// Remove o source do app
@unlink("".$patch_tmp."/logo_".$hash.".png");
@unlink("".$patch_tmp."/icone_".$hash.".png");
@unlink("".$patch_tmp."/fundo_".$hash.".jpg");

// Compila o app
$nome_apk = nome_app_apk2($_POST["webtv_nome"]);

//Bug fix
remover_source_app2("/home/painelvideo/public_html/app/apps/".$hash."/app/src/main/java/com/stmvideo/webtv/webtv_nome");

// Compila o App
shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;cd /home/painelvideo/public_html/app/apps/".$hash.";./gradlew bundleRelease;./gradlew assembleRelease");

// Assina o app com certificado
if($_POST["certificado"] == "padrao") {

shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;/opt/android-sdk-linux/build-tools/28.0.3/apksigner sign --ks-pass pass:EE4F5AD2D81078B62EBA6EA5E8 --ks /home/painelvideo/public_html/app/apps/".$hash."/certificado.jks --out /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/apk/release/app-release-unsigned.apk;jarsigner -verbose -sigalg SHA256withRSA -digestalg SHA-256 -keystore /home/painelvideo/public_html/app/apps/".$hash."/certificado.jks -storepass EE4F5AD2D81078B62EBA6EA5E8 -keypass EE4F5AD2D81078B62EBA6EA5E8 /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab chave;cp -f /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab");

}

if($_POST["certificado"] == "novo") {

//Cria o certificado
$nome_certificado = md5($nome_apk);

shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;keytool -genkey -alias streaming -keyalg RSA -keystore /home/painelvideo/public_html/app/keys/".$nome_certificado.".jks -dname 'CN=Streaming, OU=Streaming, O=Streaming, L=Streaming, S=Brasil, C=BR' -storepass ".$nome_certificado." -keypass ".$nome_certificado." -validity 365000");

// Assina o app com certificado
shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;/opt/android-sdk-linux/build-tools/28.0.3/apksigner sign --ks-pass pass:".$nome_certificado." --ks /home/painelvideo/public_html/app/keys/".$nome_certificado.".jks --out /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/apk/release/app-release-unsigned.apk;jarsigner -verbose -sigalg SHA256withRSA -digestalg SHA-256 -keystore /home/painelvideo/public_html/app/keys/".$nome_certificado.".jks -storepass ".$nome_certificado." -keypass ".$nome_certificado." /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab streaming;cp -f /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab");

mysqli_query($conexao,"Update streamings set app_certificado = '".$nome_certificado."' where login = '".$_POST["login"]."'");

}

if($_POST["certificado"] == "personalizado") {

shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;/opt/android-sdk-linux/build-tools/28.0.3/apksigner sign --ks-pass pass:".$dados_stm["app_certificado"]." --ks /home/painelvideo/public_html/app/keys/".$dados_stm["app_certificado"].".jks --out /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/apk/release/app-release-unsigned.apk;jarsigner -verbose -sigalg SHA256withRSA -digestalg SHA-256 -keystore /home/painelvideo/public_html/app/keys/".$dados_stm["app_certificado"].".jks -storepass ".$dados_stm["app_certificado"]." -keypass ".$dados_stm["app_certificado"]." /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab streaming;cp -f /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab");

}

if(file_exists("/home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk")) {

// Cria o zip com o conteudo para publicação no google play
$zip = new ZipArchive();
if ($zip->open("/home/painelvideo/public_html/app/apps/".$hash.".zip", ZIPARCHIVE::CREATE)!==TRUE) {
    die("Não foi possível criar o arquivo ZIP: ".$hash.".zip");
}

$zip->addEmptyDir("".$nome_apk."");
$zip->addFile("/home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk","".$nome_apk."/App-".$nome_apk.".apk");
$zip->addFile("/home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/img-play-logo.png","".$nome_apk."/img-play-logo.png");
$zip->addFile("/home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/img-play-destaque.jpg","".$nome_apk."/img-play-destaque.jpg");
$zip->addFile("/home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/img-play-app.png","".$nome_apk."/img-play-app.png");
$zip->addFile("/home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/img-play-app-800x1280.png","".$nome_apk."/img-play-app-800x1280.png");
$status=$zip->getStatusString();
$zip->close();

if(!file_exists("/home/painelvideo/public_html/app/apps/".$hash.".zip")) {
shell_exec("cd "."/home/painelvideo/public_html/app/apps/;/usr/bin/zip -1 ".$hash.".zip ".$hash.";/usr/bin/zip -1 ".$hash.".zip ".$hash."/arquivos_google_play/*");
}

// Remove source
if($hash != "") {
remover_source_app2("/home/painelvideo/public_html/app/apps/".$hash."");
}

// Insere os dados no banco de dados
mysqli_query($conexao,"INSERT INTO apps (codigo_stm,package,data,hash,zip,compilado,status) VALUES ('".$dados_stm["codigo"]."','".$package."',NOW(),'".$hash."','".$hash.".zip','sim','1')");

// Atualiza configuracoes do app com logo e fundo
mysqli_query($conexao,"Update streamings set app_nome = '".$_POST["webtv_nome"]."', app_url_logo = '/app/logo-".$dados_stm["login"].".png', app_url_icone = '/app/icone-".$dados_stm["login"].".jpg', app_url_background = '/app/background-".$dados_stm["login"].".jpg' where codigo = '".$dados_stm["codigo"]."'");

} else {
// Cria o sessão do status das ações executadas e redireciona.
$_SESSION["status_acao"] = status_acao($lang['lang_info_streaming_app_android_requisicao_em_andamento'],"erro");
}

header("Location: /app-android-webrtc");
exit();

}

if($_POST["acao_form"] == "configurar") {

if($_FILES["logo"]["tmp_name"]) {
@copy($_FILES["logo"]["tmp_name"],"/home/painelvideo/public_html/player/app/logo-".$dados_stm["login"].".png");
}
if($_FILES["fundo"]["tmp_name"]) {
@copy($_FILES["fundo"]["tmp_name"],"/home/painelvideo/public_html/player/app/background-".$dados_stm["login"].".jpg");
}

$app_url_chat = ($_POST["ativar_chat"]) ? $_POST["app_url_chat"] : "";
$app_url_camera_studio = ($_POST["ativar_camera_studio"]) ? $_POST["app_url_camera_studio"] : "";
$app_url_pedir_musica = ($_POST["ativar_pedido_musica"]) ? $_POST["app_url_pedir_musica"] : "";

$app_whatsapp = str_replace("+", "", $_POST["app_whatsapp"]);
$app_whatsapp = str_replace(" ", "", $_POST["app_whatsapp"]);
$app_whatsapp = str_replace("(", "", $_POST["app_whatsapp"]);
$app_whatsapp = str_replace(")", "", $_POST["app_whatsapp"]);

// Atualiza configuracoes do app com logo e fundo
mysqli_query($conexao,"Update streamings set app_email = '".$_POST["app_email"]."', app_whatsapp = '".$app_whatsapp."', app_url_facebook = '".$_POST["app_url_facebook"]."', app_url_instagram = '".$_POST["app_url_instagram"]."', app_url_twitter = '".$_POST["app_url_twitter"]."', app_url_site = '".$_POST["app_url_site"]."', app_cor_texto = '".$_POST["app_cor_texto"]."', app_cor_menu_claro = '".$_POST["app_cor_menu_claro"]."', app_cor_menu_escuro = '".$_POST["app_cor_menu_escuro"]."', app_url_logo = '/app/logo-".$dados_stm["login"].".png', app_url_icone = '/app/icone-".$dados_stm["login"].".jpg', app_url_background = '/app/background-".$dados_stm["login"].".jpg', app_url_chat = '".$app_url_chat."' where codigo = '".$dados_stm["codigo"]."'");

$_SESSION["status_acao"] = status_acao($lang['lang_info_config_painel_resultado_ok'],"ok");

header("Location: /app-android-webrtc");
exit();
}

/////////////////////////////////////////////////
/////////////////// Idioma //////////////////////
/////////////////////////////////////////////////

if($dados_stm["idioma_painel"] == "pt-br") {

$lang[ 'lang_info_streaming_app_android_tab_app_pronto' ] = 'App Pronto' ;
$lang[ 'lang_info_streaming_app_android_tab_criar_app' ] = 'Criar App' ;
$lang[ 'lang_info_streaming_app_android_tab_configurar_app' ] = 'Configurar App' ;
$lang[ 'lang_info_streaming_app_android_info_configuracoes' ] = 'As demais configurações do app, como site, redes sociais e outros serão configurados aqui após criar o app.' ;
$lang[ 'lang_info_streaming_app_android_info_configuracoes2' ] = 'Ao alterar as configurações abaixo, as mesams serão atualizadas automaticamente no app sem precisar criar um novo.' ;
$lang[ 'lang_info_streaming_app_android_info_desativar_campo' ] = '(deixe este campo em branco para desativa-lo)';
$lang[ 'lang_info_streaming_app_android_app_cor_texto' ] = 'Cor Texto';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_claro' ] = 'Cor Menu Claro';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_escuro' ] = 'Cor Menu Escuro';

} else if($dados_stm["idioma_painel"] == "en") {

$lang[ 'lang_info_streaming_app_android_tab_app_pronto' ] = 'App Created' ;
$lang[ 'lang_info_streaming_app_android_tab_criar_app' ] = 'Create App' ;
$lang[ 'lang_info_streaming_app_android_tab_configurar_app' ] = 'Configure App' ;
$lang[ 'lang_info_streaming_app_android_info_configuracoes' ] = 'The other settings of the app, such as website, social networks and others will be configured here after creating the app.' ;
$lang[ 'lang_info_streaming_app_android_info_configuracoes2' ] = 'When changing the settings below, they will be updated automatically in the app without having to create a new one.' ;
$lang[ 'lang_info_streaming_app_android_info_desativar_campo' ] = '(leave empty to disable displaying)';
$lang[ 'lang_info_streaming_app_android_app_cor_texto' ] = 'Text Color';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_claro' ] = 'Menu Color Light';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_escuro' ] = 'Menu Color Dark';

} else {

$lang[ 'lang_info_streaming_app_android_tab_app_pronto' ] = 'App Listo' ;
$lang[ 'lang_info_streaming_app_android_tab_criar_app' ] = 'Crear App' ;
$lang[ 'lang_info_streaming_app_android_tab_configurar_app' ] = 'Configurar App' ;
$lang[ 'lang_info_streaming_app_android_info_configuracoes' ] = 'Las otras configuraciones de la aplicación, como sitio web, redes sociales y otras, se configurarán aquí después de crear la aplicación.' ;
$lang[ 'lang_info_streaming_app_android_info_configuracoes2' ] = 'Al cambiar la configuración a continuación, los datos se actualizarán automáticamente en la aplicación sin tener que crear uno nuevo.' ;
$lang[ 'lang_info_streaming_app_android_info_desativar_campo' ] = '(deje este campo en blanco para deshabilitarlo)';
$lang[ 'lang_info_streaming_app_android_app_cor_texto' ] = 'Color Texto';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_claro' ] = 'Color Menu Claro';
$lang[ 'lang_info_streaming_app_android_app_cor_menu_escuro' ] = 'Color Menu Oscuro';

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
      <div id="quadro-topo"><strong><?php echo $lang['lang_info_streaming_app_android_tab_titulo']; ?></strong></div>
      <div class="texto_medio" id="quadro-conteudo">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td height="25"><div class="tab-pane" id="tabPane1">
            <?php if($dados_app_criado["codigo"] > 0) { ?>
              <div class="tab-page" id="tabPage1">
                <h2 class="tab"><?php echo $lang['lang_info_streaming_app_android_tab_app_pronto']; ?></h2>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="50%" height="500" align="center"><iframe src="https://<?php echo $url_player; ?>/player-app-webrtc/<?php echo $dados_stm["login"]; ?>" style="width:70%; height:550px" frameborder="0" onmousewheel="" scrolling="no"></iframe></td>
    <td width="50%" align="center" class="texto_padrao_verde"><?php echo $lang['lang_info_streaming_app_android_requisicao_concluida']; ?><br />
      <br />
        <a href="/app/apps/<?php echo $dados_app_criado["zip"]; ?>?<?php echo time(); ?>" target="_blank"><img src="data:image/png;base64,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" alt="Download" width="96" height="96" border="0" /></a><br>
    <br>
    <a href="javascript:remover_app_android('<?php echo code_decode($dados_app_criado["codigo"],"E"); ?>');"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAFgklEQVR42sVXW0xdRRRdB+6j2Is8bUVQCgQ1amJJizH+WFqlxZpqY/2z0dRXQIxI2lCjaPFHsRVbJFCtNtEY+4N+tApCtNjERyJYEp+phiKxLbby7OVC79s158xczrmcy0ObuJOVmXPP3Flr79mzZ46G/9m0JY7PJtYRa4gC4ko5xyQxSPQTPcTfl1OAGLOZqHYAG0oAh2AvlmqSiBHid6KPOAmEQoaIFuIYEf0vAsiH1jzg9ofYuZ/IdQOe5YCTzMlSQJgIRoBpH3DOD3zM5/eJP4Hv2FQR3/8bATXkenUH4N5OprwMIFUQkiBEokjEcE1BzJTsYcM/zfBxeIIiGIpDQIB/eY4/NS1FQNNKCmjg+zszgRwyRMathAvBmQ5MMDxfjQH1/GkYaBZOLUbAi9cAexod0Mo4iWdsrrea6Y/qNw5BSLaxqFBAhA58y2jsDCF6DqBPOhIKKOfyduxzInkrO+4J02RxxIlMiPDLNvZfOvIFl60miLDPSOguOwEpxC/VGlbVcXN5Jq3kS7UgjFxQEQmnMZu9wIEI/uDjTfK1RcAz1wP7j5C88KI1tJaQuVyIBgK2pPHvxH99MHaJmGucc2/n3L8ZuXDALEC0p+rdKK7l6GhIqo6LwBW7dsG9bRsmN25EZGLCQp6Uno60ri7429sxvXevRcSUbDUWkg+4o172Cw24UUyvBKyhuN4TKdDyZwxSlVDKlpPc09gITdMQ7O3FuBAxPm6QZ2Qgg+TO0lJEo1FM1dXBZxIhlmNaOjPGhd40g6jXqKb9SkDtHUl4/SizVgsZxOYge0ieKsmVBShirLxc72d2d8NFcmVChJcipkwifNIpMAqPkeCbCJ7l034147vVTuxoCBoqAzL8unepqVjR3w9HUdGcNRcihJnJlYUGBnChpAQRr9eSlGL+ZifwdhDvsPu4EvDJvmRsfiRsDFADlTny87Hi+HE4CguxGAudPo0L69cjNDQ0GxWZC6L9iHmwJ6yfE1uUgM6DGjY9EDXCP20zqRCxsqcHzoKCecmDg4M4X1ZmIVc2LSPbQdbdUXzGbkVMwFvMja1ywHSCyYWIHEbCmSASQXo+HOe52Wbk/J3EblgFHOPZee+DcoAvgYBkZnsOE27Z2rW27y/19WGYiRmWu2POezm/iH09rEtw6AUmZzWMJfDakXOf5wpym4SzkDAxzwoRcXVCCRDzHybe1A9KPKEE1PCsf6MNRpJ4Ya0BwvM87vOUBchjoaaIM6wT5kiIef2yZfjRzcrLplkJWM3bzUnep7QkuVZ++SLJ40E+192OPDA4qLcum8QUIoaYjBGfsaAi9CEJVo8o47Oa3R/MpfjX94AbNkjvL0q1ovhc3dKCzMpKayFiwg0y4YQVUKDLlJiiEI21teGv6mq9D8xW1hNELQ89NjcrYmVP38aQtJvWa0a9IXEuRWRVVemPfkkekNnu4u4QItxSxGhrK86SHJI8YsKjwm2gks3BeAHLiJ+ZHIVbpPcieIGYBkNEakUFBhjaQNxWEyKKWCe8nZ06ufJcCRBPnxIvGffXW9TU8feLu3lsdx5l3l2XQERyVhZCIyO2yefIzkZ4dDRGrmH2WBdyH+ZKsBreJVcCdgKE1TOlGo7w3VWm7FVbaDEWf2U7TzzJ7hljA7wWP9bOmq7l1mR11IoxezEJwHpQxVuShNnzU8RO41L6CrvP24lNZDVMikZeXVzim8AB6y0pjLn3RfPFVQj9kKATlwJ64qMtUbTmM3FpaGM+lHL9cA+Mb7H5ruOi/nVIct6Cv4aR8T/Ot1yLWdL7iKcYhXW3MhgihblE8EhSUTP4FYSfDKYgo/M59GqrnzsLTr4U4/eK/nEqPtlWEWlSg/o45achvoTxubgoW6qAy27/ABy1Jj/tibxvAAAAAElFTkSuQmCC" alt="Remover" width="32" height="32" border="0" /></a></td>
  </tr>
</table></div>
                <?php } ?>
              <?php if($dados_app_criado["codigo"] == 0 || empty($dados_app_criado["codigo"])) { ?>             
              <div class="tab-page" id="tabPage2">
                <h2 class="tab"><?php echo $lang['lang_info_streaming_app_android_tab_criar_app']; ?></h2>
  <form method="post" action="/app-android-webrtc" style="padding:0px; margin:0px" enctype="multipart/form-data"> 
                <table width="890" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:10px; background-color:#FFFF66; border:#DFDF00 1px solid">
  	  <tr>
        <td width="30" height="25" align="center" scope="col"><img src="/admin/img/icones/dica.png" width="16" height="16" /></td>
        <td width="860" align="left" class="texto_pequeno_erro" scope="col"><?php echo $lang['lang_info_streaming_app_android_info_configuracoes']; ?></td>
     </tr>
    </table>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_tv_nome']; ?></td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="webtv_nome" type="text" id="webtv_nome" style="width:350px" value="" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_webtv_nome']; ?></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_versao']; ?></td>
      <td class="texto_padrao_pequeno">
        <select name="versao" id="versao">
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
        </select>
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_versao']; ?></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque">Certificado Assinatura</td>
      <td class="texto_padrao_pequeno"><?php if($dados_stm["app_certificado"] == "padrao") { ?>
        <input name="certificado" type="radio" id="radio" value="padrao" checked="checked" />
        &nbsp;Usar Padr&atilde;o&nbsp;<input type="radio" name="certificado" id="radio" value="novo" />&nbsp;Criar Certificado Pr&oacute;prio(somente se estiver criando o app pela primeira vez)&nbsp;<?php } ?><?php if($dados_stm["app_certificado"] != "padrao") { ?><input name="certificado" type="radio" id="certificado" value="personalizado" checked="checked" />
        &nbsp;Usar Certificado Pr&oacute;prio<?php } ?></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_tv_logo']; ?></td>
      <td width="80%" class="texto_padrao_pequeno"><input name="logo" type="file" id="logo" style="width:350px" />
        <br />PNG / 300x300</td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_tv_icone']; ?></td>
      <td class="texto_padrao_pequeno"><input name="icone" type="file" id="icone" style="width:350px" />
        <br />PNG / 144x144</td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque">Background</td>
      <td class="texto_padrao_pequeno"><input name="fundo" type="file" id="fundo" style="width:350px" />
        <br />JPG / 640x1136</td>
    </tr>
    <tr>
            <td height="40" colspan="2" align="center"><input type="submit" class="botao" value="<?php echo $lang['lang_info_streaming_app_android_botao_submit']; ?>" onclick="abrir_log_sistema_app();" /><input name="acao_form" type="hidden" id="acao_form" value="criar" /><input name="idioma_painel" type="hidden" value="<?php echo $dados_stm["idioma_painel"]; ?>" /></td>
          </tr>
  </table>
  </form>
  </div>
  	<?php } ?>
                <div class="tab-page" id="tabPage3">
                <h2 class="tab"><?php echo $lang['lang_info_streaming_app_android_tab_configurar_app']; ?>&nbsp;<span class="label label-verde"><?php echo $lang['lang_label_novo']; ?></span></h2>
                <?php if($dados_app_criado["codigo"] == 0 || empty($dados_app_criado["codigo"])) { ?> 
                <table width="890" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:10px; background-color:#FFFF66; border:#DFDF00 1px solid">
  	  <tr>
        <td width="30" height="25" align="center" scope="col"><img src="/admin/img/icones/dica.png" width="16" height="16" /></td>
        <td width="860" align="left" class="texto_pequeno_erro" scope="col"><?php echo $lang['lang_info_streaming_app_android_info_configuracoes']; ?></td>
     </tr>
    </table>
                <?php } else { ?>
  <form method="post" action="/app-android-webrtc" style="padding:0px; margin:0px" enctype="multipart/form-data">
      <table width="890" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px; background-color: #C1E0FF; border: #006699 1px solid">
      <tr>
        <td width="30" height="25" align="center" scope="col"><img src="/img/icones/ajuda.gif" width="16" height="16" /></td>
        <td width="860" align="left" class="texto_padrao" scope="col"><?php echo $lang['lang_info_streaming_app_android_info_configuracoes2']; ?></td>
      </tr>
    </table>
     <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">E-mail</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_email" type="text" id="app_email" style="width:350px" value="<?php echo $dados_stm["app_email"]; ?>" /></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">Site</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_url_site" type="text" id="app_url_site" style="width:350px" value="<?php echo $dados_stm["app_url_site"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">FaceBook</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_url_facebook" type="text" id="app_url_facebook" style="width:350px" value="<?php echo $dados_stm["app_url_facebook"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">Twitter</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_url_twitter" type="text" id="app_url_twitter" style="width:350px" value="<?php echo $dados_stm["app_url_twitter"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>    
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">Instagram</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_url_instagram" type="text" id="app_url_instagram" style="width:350px" value="<?php echo $dados_stm["app_url_instagram"]; ?>" />
        <br />
        <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">WhatsApp</td>
      <td width="80%" class="texto_padrao_pequeno">
        <input name="app_whatsapp" type="text" id="app_whatsapp" style="width:350px" value="<?php echo $dados_stm["app_whatsapp"]; ?>" />
        <br />
        +00 00000000000 <?php echo $lang['lang_info_streaming_app_android_info_desativar_campo']; ?></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque">Modulo Chat</td>
      <td width="80%" class="texto_padrao_pequeno"><input name="ativar_chat" type="checkbox" value="sim" <?php if($dados_stm["app_url_chat"]) { echo ' checked="checked"'; } ?> />
        <input name="app_url_chat" type="hidden" id="app_url_chat" value="<?php echo "/app/chat/".$dados_stm["login"].""; ?>" /></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_texto']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="app_cor_texto" style="width:100px; height:30px" value="<?php echo $dados_stm['app_cor_texto']; ?>" /></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_menu_claro']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="app_cor_menu_claro" style="width:100px; height:30px" value="<?php echo $dados_stm['app_cor_menu_claro']; ?>" /></td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_app_cor_menu_escuro']; ?></td>
      <td class="texto_padrao_pequeno">
        <input type="color" name="app_cor_menu_escuro" style="width:100px; height:30px" value="<?php echo $dados_stm['app_cor_menu_escuro']; ?>" /></td>
    </tr>
    <tr>
      <td width="20%" height="50" class="texto_padrao_destaque"><?php echo $lang['lang_info_streaming_app_android_tv_logo']; ?></td>
      <td width="80%" class="texto_padrao_pequeno"><input name="logo" type="file" id="logo" style="width:350px" />
        <br />PNG / 300x300</td>
    </tr>
    <tr>
      <td height="50" class="texto_padrao_destaque">Background</td>
      <td class="texto_padrao_pequeno"><input name="fundo" type="file" id="fundo" style="width:350px" />
        <br />JPG / 640x1136</td>
    </tr>
          <tr>
            <td height="40" colspan="2" align="center"><input type="submit" class="botao" value="<?php echo $lang['lang_botao_titulo_alterar_config']; ?>" onclick="abrir_log_sistema_app();" /><input name="acao_form" type="hidden" id="acao_form" value="configurar" /></td>
          </tr>
  </table>
  </form>
                <?php } ?>
                </div>		      
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
</body>
</html>
