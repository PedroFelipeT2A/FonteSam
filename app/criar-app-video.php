<?php
// Proteção contra acesso direto
if(empty($_SERVER['HTTP_REFERER'])) {
die("0x001 - Atenção! Acesso não autorizado, favor entrar em contato com nosso atendimento para maiores informações!");
}

require_once("wideimage/WideImage.php");

// Funcções
// Função para formatar o nome da webtv retirando acentos e caracteres especiais
function formatar_nome_webtv($nome) {

$characteres = array(
    'S'=>'S', 's'=>'s', 'Ð'=>'Dj','Z'=>'Z', 'z'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
    'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
    'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'f'=>'f', '¹'=> '', '²'=> '', '&'=> 'e',
	'³'=> '', '£'=> '', '$'=> '', '%'=> '', '¨'=> '', '§'=> '', 'º'=> '', 'ª'=> '', '©'=> '', 'Ã£'=> '',
	'('=> '', ')'=> '', "'"=> '', '@'=> '', '='=> '', ':'=> '', '!'=> '', '?'=> '', '...'=> '', '®'=> '',
	'/'=> '', '´'=> '', '+'=> '', '*'=> '', '['=> '', ']'=> ''
);

return strtr($nome, $characteres);

}

// Função para formatar o nome do app para o google play retirando acentos e caracteres especiais
function nome_app_play($texto) {

$characteres = array(
    'S'=>'S', 's'=>'s', 'Ð'=>'Dj','Z'=>'Z', 'z'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
    'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
    'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'f'=>'f', '¹'=> '', '²'=> '', '&'=> 'e',
	'³'=> '', '£'=> '', '$'=> '', '%'=> '', '¨'=> '', '§'=> '', 'º'=> '', 'ª'=> '', '©'=> '', 'Ã£'=> '',
	'('=> '', ')'=> '', "'"=> '', '@'=> '', '='=> '', ':'=> '', '!'=> '', '?'=> '', '...'=> '', ' '=> '',
	'-'=> '', '^'=> '', '~'=> '', '.'=> '', '|'=> '', ','=> '', '<'=> '', '>'=> '', '{'=> '', '}'=> '',
	'®'=> '', '/'=> '', '´'=> '', '+'=> '', '*'=> '', '['=> '', ']'=> ''
);

return strtolower(strtr($texto, $characteres));

}

// Função para formatar o nome do apk do app retirando acentos e caracteres especiais
function nome_app_apk($texto) {

$characteres = array(
    'S'=>'S', 's'=>'s', 'Ð'=>'Dj','Z'=>'Z', 'z'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
    'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
    'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'f'=>'f', '¹'=> '', '²'=> '', '&'=> 'e',
	'³'=> '', '£'=> '', '$'=> '', '%'=> '', '¨'=> '', '§'=> '', 'º'=> '', 'ª'=> '', '©'=> '', 'Ã£'=> '',
	'('=> '', ')'=> '', "'"=> '', '@'=> '', '='=> '', ':'=> '', '!'=> '', '?'=> '', '...'=> '', ' '=> '',
	'-'=> '', '^'=> '', '~'=> '', '.'=> '', '|'=> '', ','=> '', '<'=> '', '>'=> '', '{'=> '', '}'=> '',
	' '=> '', '®'=> '', '/'=> '', '´'=> '', '+'=> '', '*'=> '', '['=> '', ']'=> ''
);

return strtr($texto, $characteres);

}

// Função para copiar o source para o novo app
function copiar_source($DirFont, $DirDest) {
    
    mkdir($DirDest);
    if ($dd = opendir($DirFont)) {
        while (false !== ($Arq = readdir($dd))) {
            if($Arq != "." && $Arq != ".."){
                $PathIn = "$DirFont/$Arq";
                $PathOut = "$DirDest/$Arq";
                if(is_dir($PathIn)){
                    copiar_source($PathIn, $PathOut);
					chmod($PathOut,0777);
                }elseif(is_file($PathIn)){
                    copy($PathIn, $PathOut);
					chmod($PathOut,0777);
                }
            }
        }
        closedir($dd);
	}

}

// Função para criar arquivos de configuração do app
function criar_arquivo_config($arquivo,$conteudo) {

$fd = fopen ($arquivo, "w");
fputs($fd, $conteudo);
fclose($fd);

}

// Função para carregar todos os arquivos e pastas de um diretorio
function browse($dir) {
global $filenames;
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && is_file($dir.'/'.$file)) {
                $filenames[] = $dir.'/'.$file;
            }
            else if ($file != "." && $file != ".." && is_dir($dir.'/'.$file)) {
                browse($dir.'/'.$file);
            }
        }
        closedir($handle);
    }
    return $filenames;
}

// Função para substituir uma string dentro de um arquivo de texto
function replace($arquivo,$string_atual,$string_nova) {

//$str = implode("\n",file($arquivo));
//$fp = fopen($arquivo,'w');
//$str = str_replace($string_atual,$string_nova,$str);

//fwrite($fp,$str,strlen($str));

$str = file_get_contents($arquivo);
$str = str_replace($string_atual,$string_nova,$str);
file_put_contents($arquivo,$str);

}

// Função para remover o source do novo app
function remover_source_app($Dir){
    
    if ($dd = @opendir($Dir)) {
        while (false !== ($Arq = @readdir($dd))) {
            if($Arq != "." && $Arq != ".."){
                $Path = "$Dir/$Arq";
                if(is_dir($Path)){
                    remover_source_app($Path);
                }elseif(is_file($Path)){
                    @unlink($Path);
                }
            }
        }
        @closedir($dd);
    }
    @rmdir($Dir);
}

// Função para mudar a permissão de todos os arquivos e pasta no source do app
function mudar_permissao($Dir){

    if ($dd = opendir($Dir)) {
        while (false !== ($Arq = readdir($dd))) {
            if($Arq != "." && $Arq != ".."){
                $Path = "$Dir/$Arq";
                @chmod($Path,0777);
            }
        }
        closedir($dd);
    }

}

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
die ("<script> alert(\"Nome da webtv deve ter maximo 30 caracteres.\\n\\nwebtv name max 30 character.\");
 			 window.location = 'javascript:history.back(-1)'; </script>");
}

if(empty($_POST["webtv_nome"])) {
die ("<script> alert(\"Nome da webtv esta vazio.\\n\\nwebtv name is empty.\");
 			 window.location = 'javascript:history.back(-1)'; </script>");
}

// Verifica se o primeiro caracter é numérico
if(preg_match('/^\d/',$_POST["webtv_nome"])) {
die ("<script> alert(\"Nome da webtv não pode iniciar com numeros.\\n\\nwebtv name cant stat with number.\");
 			 window.location = 'javascript:history.back(-1)'; </script>");
}

// Dados webtv

$webtv_nome = $_POST["webtv_nome"];
$webtv_facebook = $_POST["webtv_facebook"];
$webtv_twitter = $_POST["webtv_twitter"];
$webtv_site = $_POST["webtv_site"];
$webtv_descricao = $_POST["webtv_descricao"];

// Dados Streaming
$servidor = $_POST["servidor"];
$login = $_POST["login"];

$source = "sourcewebtv";

$package = "com.stmvideo.webtv.".nome_app_play($webtv_nome)."";
$hash = nome_app_play($webtv_nome)."_".md5($package);
$package_path = str_replace(".","/",$package);

//Bug fix - Remove pasta do app caso exista
remover_source_app("/home/painelvideo/public_html/app/apps/".$hash."/");

if(!file_exists("apps/".$hash.".zip")) {
@unlink("apps/".$hash.".zip");
}

$patch_dir_apps = "apps";
$patch_app = "apps/".$hash."";
$patch_tmp = "apps/tmp";

@copy($_FILES["logo"]["tmp_name"],"".$patch_tmp."/logo_".$hash.".png");
@copy($_FILES["icone"]["tmp_name"],"".$patch_tmp."/icone_".$hash.".png");
@copy($_FILES["fundo"]["tmp_name"],"".$patch_tmp."/fundo_".$hash.".jpg");

// Valida a dimensão(largura x altura) das imagens
list($logo_width, $logo_height, $logo_type, $logo_attr) = getimagesize("".$patch_tmp."/logo_".$hash.".png");
list($icone_width, $icone_height, $icone_type, $icone_attr) = getimagesize("".$patch_tmp."/icone_".$hash.".png");
list($fundo_width, $fundo_height, $fundo_type, $fundo_attr) = getimagesize("".$patch_tmp."/fundo_".$hash.".jpg");

if($logo_width != 500 || $logo_height != 500) {
die ("<script> alert(\"Ooops!\\n\\nA logomarca esta com dimensão inválida!\\n\\nEnvie uma logomarca com 500 pixels de largura e 500 pixels de altura.\");
 			 window.location = 'javascript:history.back(-1)'; </script>");
}

if($icone_width != 144 || $icone_height != 144) {
die ("<script> alert(\"Ooops!\\n\\nO ícone esta com dimensão inválida!\\n\\nEnvie um ícone com 144 pixels de largura e 144 pixels de altura.\");
 			 window.location = 'javascript:history.back(-1)'; </script>");
}

if($fundo_width != 720 || $fundo_height != 1280) {
die ("<script> alert(\"Ooops!\\n\\nO fundo esta com dimensão inválida!\\n\\nEnvie um fundo com 720 pixels de largura e 1280 pixels de altura.\");
 			 window.location = 'javascript:history.back(-1)'; </script>");
}

// Copia o source do app para o novo app
copiar_source("".$source."/", $patch_app);

// Muda nome do package do source para o nome do package da webtv
@rename("".$patch_app."/app/src/main/java/com/stmvideo/webtv/webtv_nome","".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."");

@rename("".$patch_app."/app/src/androidTest/java/com/stmvideo/webtv/webtv_nome","".$patch_app."/app/src/androidTest/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."");

// Copia o ícone
$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(96, 96);
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-hdpi/app_icon.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(72, 72);
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-hdpi/ic_launcher.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(72, 72);
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-hdpi/app_icon_round.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(72, 72);
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-mdpi/app_icon.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(48, 48);
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-mdpi/ic_launcher.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(48, 48);
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-mdpi/app_icon_round.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(144, 144);
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-xhdpi/app_icon.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(96, 96);
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-xhdpi/ic_launcher.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(96, 96);
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-xhdpi/app_icon_round.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(144, 144);
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-xxhdpi/app_icon.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(144, 144);
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-xxhdpi/ic_launcher.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(144, 144);
$icone->saveToFile("".$patch_app."/app/src/main/res/mipmap-xxhdpi/app_icon_round.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(100, 100);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable/ic_stat_onesignal_default.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(256, 256);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable/ic_onesignal_large_icon_default.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(48, 48);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-hdpi/ic_tv.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(48, 48);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-hdpi/ic_tv.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(48, 48);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-hdpi/ic_tv.png");

$icone = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$icone = $icone->resize(100, 100);
$icone->saveToFile("".$patch_app."/app/src/main/res/drawable-mdpi/top_logo.png");

// Cria a imagem de splash com fundo e logo
$splash_fundo = WideImage::load("".$patch_tmp."/fundo_".$hash.".jpg");
$splash_logo = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$splash_logo = $splash_logo->resize(500, 500);

$splash_fundo = $splash_fundo->resize(720, 1280);
$splash = $splash_fundo->merge($splash_logo, 'center', 'center', 100);
$splash->saveToFile("".$patch_app."/app/src/main/res/drawable/splash_screen.png");

// Copia o fundo
$fundo = WideImage::load("".$patch_tmp."/fundo_".$hash.".jpg");
$fundo = $fundo->resize(720, 1280);
$fundo->saveToFile("".$patch_app."/app/src/main/res/drawable-nodpi/splash_screen.png");

// Cria icone para o Play
$play_icone = WideImage::load("".$patch_tmp."/logo_".$hash.".png");
$play_icone = $play_icone->resize(512, 512);
$play_icone->saveToFile("".$patch_app."/arquivos_google_play/img-play-logo.png");

// Cria a imagem de destaque para o Play com a logo da webtv
$destaque = WideImage::load("".$patch_app."/arquivos_google_play/img-play-destaque.jpg");
$logo_destaque = WideImage::load("".$patch_tmp."/logo_".$hash.".png");
$play_destaque = $destaque->merge($logo_destaque, 'center', 'center', 100);
$play_destaque->saveToFile("".$patch_app."/arquivos_google_play/img-play-destaque.jpg");

// Cria o print do app para o Play com a logo da webtv
$printapp_fundo = WideImage::load("".$patch_tmp."/fundo_".$hash.".jpg");
$printapp_logo = WideImage::load("".$patch_tmp."/icone_".$hash.".png");
$printapp_logo = $printapp_logo->resize(500, 500);
$printappfundo = $printapp_fundo->resize(720, 1280);
$printapp = $printapp_fundo->merge($printapp_logo, 'center', 'center', 100);
$printapp->saveToFile("".$patch_app."/arquivos_google_play/img-play-app.png");

// Dados da webtv

replace("".$patch_app."/app/src/main/res/values/strings.xml","webtv_source_url","rtmp://".$servidor."/".$login."/".$login."");
replace("".$patch_app."/app/src/main/res/values/strings.xml","webtv_nome",utf8_encode(str_replace("&","&amp;",$webtv_nome)));
replace("".$patch_app."/app/src/main/res/values/strings.xml","webtv_facebook",$webtv_facebook);
replace("".$patch_app."/app/src/main/res/values/strings.xml","webtv_twitter",$webtv_twitter);
replace("".$patch_app."/app/src/main/res/values/strings.xml","webtv_site",$webtv_site);
replace("".$patch_app."/app/src/main/res/values/strings.xml","webtv_descricao",utf8_encode($webtv_descricao));
replace("".$patch_app."/app/src/main/res/values/strings.xml","webtv_package",$package);
replace("".$patch_app."/app/src/main/res/values/strings.xml","webtv_app_versao",$_POST["versao"]);

list($cor1, $cor2) = explode("|",$_POST["tema"]);

replace("".$patch_app."/app/src/main/res/layout/nav_header.xml","COR_APP",$cor1);
replace("".$patch_app."/app/src/main/res/drawable/toolbar_bg.xml","COR_APP",$cor1);
replace("".$patch_app."/app/src/main/res/values/colors.xml","COR_APP",$cor2);

// Muda o package

replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/Privacy_Activity.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/FacebookFragment.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/HomeFragment.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/TwitterFragment.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/SiteFragment.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/AboutUsActivity.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/SplashActivity.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/MainActivity.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/MyApplication.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/TvPlay.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/androidTest/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/ApplicationTest.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/util/AlertDialogManager.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/util/Constant.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/util/JsonUtils.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/util/NetCheck.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/stmvideo/util/StatusBarView.java","com.stmvideo.webtv.webtv_nome",$package);

replace("".$patch_app."/app/src/main/java/com/cast/Casty.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/cast/CastyActivity.java","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/java/com/cast/ExpandedControlsActivity.java","com.stmvideo.webtv.webtv_nome",$package);

replace("".$patch_app."/app/build.gradle","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/google-services.json","com.stmvideo.webtv.webtv_nome",$package);
replace("".$patch_app."/app/src/main/AndroidManifest.xml","com.stmvideo.webtv.webtv_nome",$package);

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
} else {
$codigo_versao = 1;
}

replace("".$patch_app."/app/src/main/AndroidManifest.xml","codigo_versao",$codigo_versao);
replace("".$patch_app."/app/src/main/AndroidManifest.xml","numero_versao",$_POST["versao"]);
replace("".$patch_app."/app/build.gradle","codigo_versao",$codigo_versao);
replace("".$patch_app."/app/build.gradle","numero_versao",$_POST["versao"]);
replace("".$patch_app."/gradlew","HASH_GRADLEW_APP",$hash);

// Muda o idioma do app conforme o idioma do painel
if($_POST["idioma_painel"] == "pt-br") {

replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_SHARE","Compartilhar");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_SHARE",utf8_encode("Gostaria de compartilhar meu app favorito com você."));
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_RATE","Avaliar App");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_DESCRICAO",utf8_encode("Descrição"));
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_PLAYER1","Abrir Player Interno");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_PLAYER2","Abrir Player Externo");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_PLAYER","Clique no play para assistir");
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/MainActivity.java","MSG_SAIR_SIM","Sim");
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/MainActivity.java","MSG_SAIR_NAO",utf8_encode("Não"));
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/MainActivity.java","MSG_SAIR","Deseja realmente sair?");

} elseif($_POST["idioma_painel"] == "en-us") {

replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_SHARE","Share");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_SHARE","I want to share my favorite app with you.");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_RATE","Rate App");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_DESCRICAO","Description");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_PLAYER1","Open Internal Player");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_PLAYER2","Open External Player");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_PLAYER","Click on the play to warch");
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/MainActivity.java","MSG_SAIR_SIM","Yes");
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/MainActivity.java","MSG_SAIR_NAO","No");
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/MainActivity.java","MSG_SAIR","Are You Sure You Want To Quit?");

} else {

replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_SHARE","Compartir");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_SHARE","Me gustaria compartir mi app favorito con tu.");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_RATE","Avaliar App");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_DESCRICAO","Descripcion");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_PLAYER1","Abrir Player Interno");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_PLAYER2","Abrir Player Externo");
replace("".$patch_app."/app/src/main/res/values/strings.xml","MSG_BTN_PLAYER","Clic en play para mirar");
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/MainActivity.java","MSG_SAIR_SIM","Si");
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/MainActivity.java","MSG_SAIR_NAO","No");
replace("".$patch_app."/app/src/main/java/com/stmvideo/webtv/".nome_app_play($webtv_nome)."/MainActivity.java","MSG_SAIR","Desea salir?");

}

// Remove o source do app
@unlink("".$patch_tmp."/logo_".$hash.".png");
@unlink("".$patch_tmp."/icone_".$hash.".png");
@unlink("".$patch_tmp."/fundo_".$hash.".jpg");

// Compila o app
$nome_apk = nome_app_apk($_POST["webtv_nome"]);

//Bug fix
remover_source_app("apps/".$hash."/app/src/main/java/com/stmvideo/webtv/webtv_nome");

// Compila o App
shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;cd /home/painelvideo/public_html/app/apps/".$hash.";./gradlew bundleRelease;./gradlew assembleRelease");

// Assina o app com certificado
shell_exec("export JAVA_HOME=/usr;export PATH=\$JAVA_HOME/bin:\$PATH;/opt/android-sdk-linux/build-tools/28.0.3/apksigner sign --ks-pass pass:EE4F5AD2D81078B62EBA6EA5E8 --ks /home/painelvideo/public_html/app/apps/".$hash."/certificado.jks --out /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/apk/release/app-release-unsigned.apk;jarsigner -verbose -sigalg SHA256withRSA -digestalg SHA-256 -keystore /home/painelvideo/public_html/app/apps/".$hash."/certificado.jks -storepass EE4F5AD2D81078B62EBA6EA5E8 -keypass EE4F5AD2D81078B62EBA6EA5E8 /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab chave;cp -f /home/painelvideo/public_html/app/apps/".$hash."/app/build/outputs/bundle/release/app-release.aab /home/painelvideo/public_html/app/apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab");

if(file_exists("apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk") && file_exists("apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab")) {

// Cria o zip com o conteudo para publicação no google play
$zip = new ZipArchive();
if ($zip->open("apps/".$hash.".zip", ZIPARCHIVE::CREATE)!==TRUE) {
    die("Não foi possível criar o arquivo ZIP: ".$hash.".zip");
}

$zip->addEmptyDir("".$nome_apk."");
$zip->addFile("apps/".$hash."/arquivos_google_play/App-".$nome_apk.".apk","".$nome_apk."/App-".$nome_apk.".apk");
$zip->addFile("apps/".$hash."/arquivos_google_play/App-".$nome_apk.".aab","".$nome_apk."/App-".$nome_apk.".aab");
$zip->addFile("apps/".$hash."/arquivos_google_play/img-play-logo.png","".$nome_apk."/img-play-logo.png");
$zip->addFile("apps/".$hash."/arquivos_google_play/img-play-destaque.jpg","".$nome_apk."/img-play-destaque.jpg");
$zip->addFile("apps/".$hash."/arquivos_google_play/img-play-app.png","".$nome_apk."/img-play-app.png");
$status=$zip->getStatusString();
$zip->close();

if(!file_exists("apps/".$hash.".zip")) {
shell_exec("cd apps/;/usr/bin/zip -1 ".$hash.".zip ".$hash.";/usr/bin/zip -1 ".$hash.".zip ".$hash."/arquivos_google_play/*");
}

// Remove source
if($hash != "") {
remover_source_app("apps/".$hash."");
}

header("Location: ".$_SERVER['HTTP_REFERER']."/".$package."|".$hash."");
exit();

} else {
header("Location: ".$_SERVER['HTTP_REFERER']."/erro|compilar");
exit();
}
?>