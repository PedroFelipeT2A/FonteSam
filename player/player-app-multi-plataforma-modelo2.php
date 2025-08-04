<!DOCTYPE html>
<html lang="pt-BR" translate="no">
<head>
<meta charset="UTF-8">
<title><?php echo $dados_app_multi_plataforma["nome"];?></title>
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<meta name="Description" content="Radio App">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="<?php echo $dados_app_multi_plataforma["nome"];?>">
<meta name="msapplication-TileColor" content="<?php echo $dados_app_multi_plataforma["cor_splash"]; ?>">
<meta name="theme-color" content="<?php echo $dados_app_multi_plataforma["cor_splash"]; ?>">
<link rel="icon" type="image/png" href="<?php echo $dados_app_multi_plataforma["url_logo"];?>" sizes="300x300" />
<link rel="apple-touch-icon" sizes="300x300" href="<?php echo $dados_app_multi_plataforma["url_logo"];?>">
<link rel="manifest" href="/player-app-multi-plataforma/<?php echo $dados_stm["login"];?>/manifest.webmanifest?<?php echo time();?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.6.1/sweetalert2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
<?php if(!empty($_GET["app-multi"])) {?>
<script src="https://cdn.jsdelivr.net/npm/jquery.cookie@1.4.1/jquery.cookie.min.js"></script>
<?php } ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.6.1/sweetalert2.css">
<link rel="stylesheet" href="/app-multi-plataforma/modelo3/css/slick.css" crossorigin="anonymous">
<style type="text/css">@import url('https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700');@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css');:root{--corSite:<?php echo $dados_app_multi_plataforma["cor_menu_claro"]; ?>;--corTexto:<?php echo $dados_app_multi_plataforma["cor_texto"]; ?>;--corTitulo:<?php echo $dados_app_multi_plataforma["cor_menu_escuro"]; ?>;--corModulo:<?php echo $dados_app_multi_plataforma["cor_menu_claro"]; ?>}*{margin:0;padding:0;text-decoration:none;color:var(--corTexto);font-family:'Open Sans',arial,verdana;font-size:0.9rem}html{height:100%}<?php if(!empty($_GET["app-multi"])) {?>body{background-image:url('<?php echo $dados_app_multi_plataforma["url_background"]; ?>');background-size:cover;background-position:center center;height:100%}<?php } ?>.sg-mascara{position:absolute;height:100%;width:100%;z-index:-1;background-image:radial-gradient(#31013f99 30%, #31013fcc 70%)}.sg-conteudo{max-width:2000px;width:100%}.sg-cabecalho{display:flex;align-items:center;height:calc(100% - 380px);width:100%}.sg-cabecalho1{width:50%}.sg-logo{max-width:240px;width:90%;display:table;margin:6px auto}.sg-bottom{position:fixed;bottom:0;width:100%}.sg-bottom1{background:var(--corSite);width:100%;border-top:2px solid var(--corTitulo);padding:15px 0;padding-left:80px;width:96%}.sg-bottom1 h4{color:var(--corTitulo);font-size:1.2rem;margin-bottom:15px}.sg-bottom2{text-align: center; z-index: 9999;position: relative;background:var(--corTitulo);width:100%;padding:15px 0;color:var(--corTexto);font-size:13px}.sg-bottom3{max-width:3000px;width:100%;padding:7px 0;padding-left:80px}.sg-bottom-icones{position:absolute;z-index:1000;left:0;bottom:40px;background:var(--corSite);border-top:2px solid var(--corTitulo);border-right:2px solid var(--corTitulo);border-bottom:2px solid var(--corTitulo);padding:10px;border-top-right-radius:15px;border-bottom-right-radius:15px;box-shadow:5px 5px 8px #000000;padding-left:10px}.sg-bottom-icones i{font-size:2.4rem;color:var(--corTitulo);margin:23px 3px;cursor:pointer;display:block;cursor:pointer}.sg-player1{width:220px;height:220px;border-radius:10px;border:1px solid var(--corTitulo);background:var(--corModulo);transform:translate(-50%,-50%);-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-ms-transform:rotate(45deg);-o-transform:rotate(45deg);transform:translate(-50%,-0%) rotate(45deg);z-index:0;position:absolute;top:0%;left:50%;box-shadow:4px 4px 8px #000000}.sg-player{max-width:350px;width:98%;position:absolute;right:0;bottom:-30px;height:245px}.sg-player2{width:100%;height:0px;border-bottom:75px solid var(--corTitulo);border-left:45px solid transparent;border-right:0px solid transparent;position:absolute;bottom:0;right:0}.sg-player-icones{width:240px;display:table;margin:0 auto;position:relative;background:#33333355}.sg-player-controles{max-width:350px;width:100%;position:absolute;z-index:1;top:15px;left:0%}.sg-player-controles div.play{font-size:4.5rem;color:var(--corTitulo);display:table;margin:0 auto;margin-bottom:20px;cursor:pointer}.sg-player-sociais{display:flex;position:relative;align-items:center;justify-content:center}.sg-player-sociais i.sociais{background:var(--corTitulo);font-size:1.5rem;margin:7px;color:var(--corModulo);width:40px;height:40px;align-items:center;justify-content:center;border-radius:100%;display:flex;float:left;cursor:pointer}.sg-programas{display:flex;align-items:center}.sg-programas1{flex:none;width:100px;border-radius:100%;border:2px solid var(--corTitulo);height:100px;background-color:var(--corSite);background-size:cover;background-position:center}.sg-programas2{flex-wrap:1;padding:2%;width:96%}.sg-programas2 h3{font-weight:500;color:var(--corTexto)}.sg-programas2 h4{font-weight:700;color:var(--corTitulo);text-transform:uppercase;font-size:1rem;margin:0}.sg-programas2 h2{font-weight:500;color:var(--corTexto)}.sg-col{display:flex;align-items:center;width:calc(100% - 380px)}.sg-col1{width:50%}.sg-noar{display:flex;align-items:center;position:relative}.sg-noar .sg-noar-fundo{position:absolute;width:calc(100% - 250px);background:var(--corTitulo);height:120px;right:0;z-index:-1}.sg-noar1{width:100px;flex:none}.sg-noar1 h4{font-size:14pt;text-align:center}.sg-noar2{width:200px;flex:none;border-radius:100%;height:200px;border:3px solid var(--corTitulo);background-color:var(--corSite);background-size:cover;background-position:center}.sg-noar3{flex-wrap:1;width:96%;padding:2%}.sg-noar3 h4{font-size:12pt;color:var(--corTexto);text-transform:uppercase;letter-spacing:-1px}.sg-noar3 h2{font-size:10pt;color:var(--corTexto);letter-spacing:-0.5px;font-weight:500}.sg-noar3 h3{font-size:9pt;color:var(--corTexto);letter-spacing:-0.5px;font-weight:500}.volume-icon{font-size:28px;margin-right:8px}.volume-icon i{color:var(--corModulo);font-size:1.4rem}.volume-slide{line-height:35px}.volume-control{display:block;display:flex;align-items:center;justify-content:center;max-width:200px;width:100%;margin:0 auto;margin-top:20px;margin-bottom:40px}input[type=range]{-webkit-appearance:none;background:transparent;width:100%}input[type=range]:focus{outline:none}input[type=range]::-webkit-slider-runnable-track{width:100%;height:5px;cursor:pointer;animate:0.2s;box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d;background:var(--corModulo)}input[type=range]::-webkit-slider-thumb{box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d;height:20px;width:20px;border-radius:50%;background:var(--corModulo);cursor:pointer;-webkit-appearance:none;margin-top:-8px;border:1px solid var(--corTitulo)}input[type=range]::-moz-range-track{width:100%;height:5px;cursor:pointer;animate:0.2s;box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d;background:var(--corModulo)}input[type=range]::-moz-range-thumb{box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d;border:0px solid #000000;height:20px;width:20px;border-radius:50%;background:var(--corModulo);cursor:pointer}input[type=range]::-ms-track{width:100%;height:5px;cursor:pointer;animate:0.2s;background:transparent;border-color:transparent;border-width:10px 0;top:-10px;color:transparent}input[type=range]::-ms-fill-lower{background:var(--corModulo);border:0px solid #000101;box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d}input[type=range]::-ms-fill-upper{background:var(--corModulo);border:0px solid #000101;box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d}input[type=range]::-ms-thumb{box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d;border:0px solid #000000;height:20px;width:20px;border-radius:50%;background:var(--corModulo);cursor:pointer}.sg-copy{color:var(--corModulo);font-size:0.8rem;display:block;text-align:center;margin-bottom:3px;display:none}.sg-bottom-logo img{max-width:80px;width:100%;display:table;margin:0 auto}.sg-pubmeioroll{width:calc(100% - 440px);display:flex;margin-bottom:10px}.sg-pubmeio1{margin:0 10px}.sg-pubmeio1 img{width:96%}.sg-modalfundo{position:fixed;background:rgb(0 0 0 / 90%);;width:100%;height:100%;top:0;left:0;display:none;z-index:100;}.sg-modalfundo .playertv{max-width:800px;width:98%;margin:0 auto;position:relative;text-align:center;padding-bottom:54.90%;padding-top:0;height:0}.sg-modalfundo .bt-fechar{top: 15px; right: 15px; float: right;display:table;background:none;border:none;cursor:pointer;z-index: 999;position: absolute;}.sg-modalfundo .bt-fechar i{font-size:24pt;color:var(--corTexto)}.sg-carrega-load{display:flex;margin:0 auto;width:60px;height:60px;border:solid 4px var(--corTitulo);border-radius:50%;border-right-color:transparent;border-bottom-color:transparent;-webkit-transition:all 0.5s ease-in;-webkit-animation-name:rotatecarrega;-webkit-animation-duration:0.5s;-webkit-animation-iteration-count:infinite;-webkit-animation-timing-function:linear;transition:all 0.5s ease-in;animation-name:rotatecarrega;animation-duration:0.5s;animation-iteration-count:infinite;animation-timing-function:linear;z-index:1;margin-bottom:10px}@keyframes rotatecarrega{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}@-webkit-keyframes rotate{from{-webkit-transform:rotate(0deg)}to{-webkit-transform:rotate(360deg)}}@media screen and (max-width:767px) and (max-height:700px){}@media(max-width:820px){.sg-copy{display:block}.volume-control{margin-bottom:10px}.sg-bottom-icones{bottom:200px}.sg-player{max-width:1000px;width:100%;height:245px}.sg-player2{border-bottom:75px solid var(--corTitulo);border-left:0px solid transparent;border-right:0px solid transparent;position:absolute;bottom:0;right:0}.sg-player-controles i.play{margin-bottom:10px}.sg-cabecalho{height:auto}.sg-pubmeioroll{position:absolute;width:96%;right:35px;bottom:240px}.sg-bottom3{margin:0 0 0 auto;margin-bottom:0px;padding-left:0px;position:relative;width:calc(100% - 75px)}.sg-bottom1{display:none}.sg-bottom-icones i{font-size:1.8rem;color:var(--corTitulo);width:23px;height:25px;margin:20px 0}.sg-cabecalho{display:table}.sg-cabecalho1{width:100%}.sg-logo{max-width:200px;width:90%}.sg-noar .sg-noar-fundo{width:calc(100% - 180px);height:80px}.sg-noar2{width:100px;height:100px}.sg-noar3 h4{font-size:10pt}.sg-noar3 h2{font-size:9pt}.sg-noar1 h4{font-size:10pt}}@media screen and (max-device-height:700px){.sg-logo{max-width:120px}.sg-bottom-icones{bottom:120px}.sg-pubmeioroll{position:absolute;width:96%;right:5;bottom:220px}.sg-player{height:200px}.sg-player1{width:170px;height:170px;border-radius:10px;border:1px solid var(--corTitulo);background:var(--corModulo);transform:translate(-50%,-50%);-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-ms-transform:rotate(45deg);-o-transform:rotate(45deg);transform:translate(-50%,-0%) rotate(45deg);z-index:0;position:absolute;top:0%;left:50%;box-shadow:4px 4px 8px #000000}.sg-player2{width:100%;height:0px;border-bottom:75px solid var(--corTitulo);border-left:45px solid transparent;border-right:0px solid transparent;position:absolute;bottom:0;right:0}.sg-player-controles div.play{margin-bottom:7px}.sg-player-icones{width:240px;display:table;margin:0 auto;position:relative;background:#33333355}.sg-player-controles{top:5px}.sg-player-controles i.play{font-size:3.5rem;margin-bottom:10px}.sg-player-sociais{display:flex;position:relative;align-items:center;justify-content:center}.sg-player-sociais i.sociais{background:var(--corTitulo);font-size:1rem;margin:7px;color:var(--corModulo);width:30px;height:30px;align-items:center;justify-content:center;border-radius:100%;display:flex;float:left;cursor:pointer}}@media screen and (max-device-height:520px){.sg-logo{max-width:90px}.sg-bottom-icones{bottom:100px}.sg-pubmeioroll{position:absolute;width:96%;right:5;bottom:160px}.sg-player2{width:100%;height:0px;border-bottom:53px solid var(--corTitulo);border-left:45px solid transparent;border-right:0px solid transparent;position:absolute;bottom:0;right:0}.sg-player{height:175px}.sg-player1{width:170px;height:170px;border-radius:10px;border:1px solid var(--corTitulo);background:var(--corModulo);transform:translate(-50%,-50%);-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-ms-transform:rotate(45deg);-o-transform:rotate(45deg);transform:translate(-50%,-0%) rotate(45deg);z-index:0;position:absolute;top:0%;left:50%;box-shadow:4px 4px 8px #000000}.sg-player-icones{width:240px;display:table;margin:0 auto;position:relative;background:#33333355}.sg-player-controles{top:5px}.sg-player-controles i.play{font-size:3rem;margin-bottom:5px}.sg-player-sociais{display:flex;position:relative;align-items:center;justify-content:center}.sg-player-sociais i.sociais{background:var(--corTitulo);font-size:1rem;margin:7px;color:var(--corModulo);width:30px;height:30px;align-items:center;justify-content:center;border-radius:100%;display:flex;float:left;cursor:pointer}.volume-icon{font-size:22px;margin-right:8px}.volume-icon i{color:var(--corModulo);font-size:1.2rem}.volume-slide{line-height:25px}.volume-control{display:block;display:flex;align-items:center;justify-content:center;max-width:200px;width:100%;margin:0 auto;margin-top:10px;margin-bottom:5px}input[type=range]{-webkit-appearance:none;background:transparent;width:100%}input[type=range]:focus{outline:none}input[type=range]::-webkit-slider-runnable-track{width:100%;height:5px;cursor:pointer;animate:0.2s;box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d;background:var(--corModulo)}input[type=range]::-webkit-slider-thumb{box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d;height:18px;width:18px;border-radius:50%;background:var(--corModulo);cursor:pointer;-webkit-appearance:none;margin-top:-6px;border:1px solid var(--corTitulo)}input[type=range]::-moz-range-track{width:100%;height:5px;cursor:pointer;animate:0.2s;box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d;background:var(--corModulo)}input[type=range]::-moz-range-thumb{box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d;border:0px solid #000000;height:20px;width:20px;border-radius:50%;background:var(--corModulo);cursor:pointer}input[type=range]::-ms-track{width:100%;height:5px;cursor:pointer;animate:0.2s;background:transparent;border-color:transparent;border-width:10px 0;top:-10px;color:transparent}input[type=range]::-ms-fill-lower{background:var(--corModulo);border:0px solid #000101;box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d}input[type=range]::-ms-fill-upper{background:var(--corModulo);border:0px solid #000101;box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d}input[type=range]::-ms-thumb{box-shadow:0px 0px 0px #000000, 0px 0px 0px #0d0d0d;border:0px solid #000000;height:20px;width:20px;border-radius:50%;background:var(--corModulo);cursor:pointer}.sg-copy{color:var(--corModulo);font-size:0.7rem;display:block;text-align:center;margin-bottom:3px}.sg-bottom-logo img{max-width:60px;width:100%;display:table;margin:0 auto}.sg-noar1{width:70px;flex:none}.sg-noar .sg-noar-fundo{width:calc(100% - 100px);height:80px}.sg-noar2{width:100px;height:100px}.sg-noar3 h4{font-size:10pt}.sg-noar3 h2{font-size:9pt}.sg-noar1 h4{font-size:10pt}}#tabela_historico tr > *:nth-child(1) {display: none;}#tabela_historico tr:first-child {display: none;}td{color: <?php echo $dados_app_multi_plataforma["cor_texto"]; ?>}</style>
</head>
<body oncontextmenu="return false">
<?php if(empty($_GET["app-multi"])) {?>

<?php  $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android"); $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone"); $Windows  = stripos($_SERVER['HTTP_USER_AGENT'],"Windows"); ?>

<?php if($Android) { ?>

<div style="width:100%;text-align:center;margin-top:20px;font-size: 18px;font-family: Geneva, Arial, Helvetica, sans-serif;color:#333333;padding-left: 10px; padding-right: 10px"><img width="256" height="256" src="<?php echo $dados_app_multi_plataforma["url_logo"]; ?>" alt="<?php echo $dados_app_multi_plataforma["nome"]; ?>" title="<?php echo $dados_app_multi_plataforma["nome"]; ?>"><br><br><br><?php echo $array_lang[$dados_stm["idioma_painel"]]["aviso_instalar"]; ?><br><br><br><button id="instalar" type="button" class="btn btn-success" style="font-size: inherit;"><i class='fa fa-android'></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["btn_instalar"]; ?></button></div>

<?php } ?>

<?php if($iPhone) { ?>

<div style="width:100%;text-align:center;margin-top:20px;font-size: 18px;font-family: Geneva, Arial, Helvetica, sans-serif;color:#333333;padding-left: 10px; padding-right: 10px"><img width="256" height="256" src="<?php echo $dados_app_multi_plataforma["url_logo"]; ?>" alt="<?php echo $dados_app_multi_plataforma["nome"]; ?>" title="<?php echo $dados_app_multi_plataforma["nome"]; ?>"><br><br><br><?php echo $array_lang[$dados_stm["idioma_painel"]]["aviso_instalar"]; ?><br><br><br><button type="button" class="btn btn-success" style="font-size: inherit;" onClick="instalar_app_iphone();"><i class='fa fa-apple'></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["btn_instalar"]; ?></button></div>

<?php } ?>

<?php if($Windows) { ?>

<div style="width:100%;text-align:center;margin-top:20px;font-size: 18px;font-family: Geneva, Arial, Helvetica, sans-serif;color:#333333;padding-left: 10px; padding-right: 10px"><img width="256" height="256" src="<?php echo $dados_app_multi_plataforma["url_logo"]; ?>" alt="<?php echo $dados_app_multi_plataforma["nome"]; ?>" title="<?php echo $dados_app_multi_plataforma["nome"]; ?>"><br><br><br><?php echo $array_lang[$dados_stm["idioma_painel"]]["aviso_instalar_windows"]; ?><br><br><br><button id="instalar" type="button" class="btn btn-success" style="font-size: inherit;"><i class='fa fa-windows'></i> <?php echo $array_lang[$dados_stm["idioma_painel"]]["btn_instalar"]; ?></button></div>

<?php } ?>

<?php } else {?>
<div id='sg-video-resultado'></div><div class='sg-conteudo'><div class='sg-cabecalho'>
    <div class='sg-cabecalho1'><img src='<?php echo $dados_app_multi_plataforma["url_logo"];?>' class='sg-logo' alt='<?php echo $dados_app_multi_plataforma["nome"]; ?>' title='<?php echo $dados_app_multi_plataforma["nome"]; ?>'></div>
</div>
<div class='sg-bottom'>
    <div class='sg-bottom-icones'>
        <?php if($dados_app_multi_plataforma["whatsapp"]) { ?>
        <i class="fab fa-whatsapp" onclick="window.open('https://wa.me/<?php echo $dados_app_multi_plataforma["whatsapp"]; ?>', '_blank')"></i>
                <?php } ?>
        <?php if($dados_app_multi_plataforma["text_prog"]) { ?>
        <i class="fas fa-calendar" onclick="carregaurl('sg-video-resultado','/app-multi-plataforma/texto/<?php echo $dados_stm["login"]; ?>/programacao')"></i>
                <?php } ?>
        <?php if($dados_app_multi_plataforma["text_hist"]) { ?>
        <i class="fas fa-info-circle" onclick="carregaurl('sg-video-resultado','/app-multi-plataforma/texto/<?php echo $dados_stm["login"]; ?>/historia')"></i>
                <?php } ?>
        <?php if($dados_app_multi_plataforma["url_chat"]) { ?>
        <i class="fas fa-comments" onclick="carregaurl('sg-video-resultado','<?php echo $dados_app_multi_plataforma["url_chat"]; ?>')"></i>
                <?php } ?>
    </div>
    <div class='sg-bottom3'>
        <div class="sg-pubmeioroll">
                <?php
                $sql_banners = mysqli_query($conexao,"SELECT * FROM app_multi_plataforma_anuncios where codigo_app = '".$dados_app_multi_plataforma["codigo"]."' ORDER by RAND()");
                while ($dados_banner = mysqli_fetch_array($sql_banners)) {

                mysqli_query($conexao,"UPDATE app_multi_plataforma_anuncios SET exibicoes = exibicoes+1 WHERE codigo = '".$dados_banner["codigo"]."'");

                $anuncio_link = (empty($dados_banner["link"])) ? "#" : "https://playerv.".$dados_config["dominio_padrao"]."/player-app-multi-plataforma/".$dados_stm["login"]."/abrir-anuncio/".$dados_banner["codigo"]."";

                ?>
                <div class="sg-pubmeio1">
                    <a href="<?php echo $anuncio_link; ?>" target="_blank">
                        <img src="<?php echo $dados_banner["banner"]; ?>" title="<?php echo $dados_banner["nome"]; ?>" alt="<?php echo $dados_banner["nome"]; ?>"/>
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
    <div class='sg-bottom1'>
        <h4 id='tituloQueVem'></h4>
        <div class='sg-col' id='OutrosProgramas'></div>
    </div>
    <div class='sg-bottom2'><?php echo $dados_app_multi_plataforma["nome"]; ?></div><div class='sg-player'>
  <div class='sg-player-icones' id='sociaisPlayer'>
    <div class='sg-player-controles'>
      <div id="playerButton" class="fas fa-play play" onclick="window.location = 'https://<?php echo $_SERVER["HTTP_HOST"]; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>/player?<?php echo time();?>'"></div>
      <div class='sg-player-sociais'>
                <?php if($dados_app_multi_plataforma["url_facebook"]) { ?>
                <i class="fab fa-facebook-f sociais" onclick="window.open('<?php echo $dados_app_multi_plataforma["url_facebook"]; ?>','_blank')"></i>
                <?php } ?>
                <?php if($dados_app_multi_plataforma["url_instagram"]) { ?>
                <i class="fab fa-instagram sociais" onclick="window.open('<?php echo $dados_app_multi_plataforma["url_instagram"]; ?>','_blank')"></i>
                <?php } ?>
                <?php if($dados_app_multi_plataforma["url_youtube"]) { ?>
                <i class="fab fa-youtube sociais" onclick="window.open('<?php echo $dados_app_multi_plataforma["url_youtube"]; ?>','_blank')"></i>
                <?php } ?>
                <?php if($dados_app_multi_plataforma["url_twitter"]) { ?>
                <i class="fab fa-twitter sociais" onclick="window.open('<?php echo $dados_app_multi_plataforma["url_twitter"]; ?>','_blank')"></i>
                <?php } ?>
            </div></div></div>
<div class='sg-player1'></div>
</div></div></div>
<script language="JavaScript" type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
<script language="JavaScript" src='/app-multi-plataforma/modelo3/js/slick.js'></script>
<script type="text/javascript">

function carregaTV(iddiv) {
    $("#" + iddiv).html("<div class='sg-modalfundo'><div id='modalResultado'></div><button class='bt-fechar' title='Fechar'><i class='fas fa-times' onclick='fecharModal(\"" + iddiv + "\");'></i></button></div>");
    setTimeout(function () {
        $(".sg-modalfundo").toggle(150)
    }, 300);
    $("#modalResultado").html('<iframe id="iframe_cs" style="width:100%; height:100%;z-index: 800; position: absolute;" scrolling="No" frameborder="0" allowfullscreen="allowfullscreen">Carregando...</iframe>');
    setTimeout(function () {
    $("#iframe_cs").attr("src", "<?php echo $dados_app_multi_plataforma["url_camera_studio"]; ?>");
    }, 300);
}

function carregapedidos(iddiv) {
    $("#" + iddiv).html("<div class='sg-modalfundo'><div id='modalResultado'></div><button class='bt-fechar' title='Fechar'><i class='fas fa-times' onclick='fecharModal(\"" + iddiv + "\");'></i></button></div>");
    setTimeout(function () {
        $(".sg-modalfundo").toggle(150)
    }, 300);
    $("#modalResultado").html('<iframe style="width:100%; height:100%;z-index: 800; position: absolute;margin: 50px auto;" src="<?php echo $dados_app_multi_plataforma["url_pedir_musica"]; ?>" scrolling="No" frameborder="0" allowfullscreen="allowfullscreen"></iframe>');
}
function carregaurl(iddiv,url) {
    $("#" + iddiv).html("<div class='sg-modalfundo'><div id='modalResultado'></div><button class='bt-fechar' title='Fechar'><i class='fas fa-times' onclick='fecharModal(\"" + iddiv + "\");'></i></button></div>");
    setTimeout(function () {
        $(".sg-modalfundo").toggle(150)
    }, 300);
    $("#modalResultado").html('<iframe style="width:100%; height:100%;z-index: 800; position: absolute;margin: 50px auto;" src="'+url+'" scrolling="No" frameborder="0" allowfullscreen="allowfullscreen"></iframe>');
}


function carregatexto(iddiv,url) {
    $("#" + iddiv).html("<div class='sg-modalfundo'><div id='modalResultado'></div><button class='bt-fechar' title='Fechar'><i class='fas fa-times' onclick='fecharModal(\"" + iddiv + "\");'></i></button></div>");
    setTimeout(function () {
        $(".sg-modalfundo").toggle(150)
    }, 300);

    $.get(url).done(function( resultado ) {
            $('#modalResultado').hide().html(resultado).fadeIn(1500);
          });
}

function fecharModal(iddiv) {
    $(".sg-modalfundo").toggle(150);
    setTimeout(function () {
        $("#" + iddiv).html("")
    }, 300)
}


setTimeout(function(){ 

if (Notification.permission !== 'granted') {

  if (!$.cookie('permissao_<?php echo $dados_stm["login"]; ?>')) {
    Swal.fire({
      title: '<?php echo $array_lang[$dados_stm["idioma_painel"]]["notificacao_permissao_titulo"]; ?>',
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText: '<?php echo $array_lang[$dados_stm["idioma_painel"]]["notificacao_permissao_sim"]; ?>',
      denyButtonText: '<?php echo $array_lang[$dados_stm["idioma_painel"]]["notificacao_permissao_nao"]; ?>',
    }).then((result) => {
      if (result.isConfirmed) {

        Notification.requestPermission().then(function (permission) { 
          if (permission === "granted") {
            <?php if($Windows) { ?>
            var notification = new Notification("<?php echo $array_lang[$dados_stm["idioma_painel"]]["notificacao_ativada"]; ?>");
            <?php } else { ?>
              var opcoes = {
                icon: 'https://<?php echo $_SERVER["HTTP_HOST"]; ?><?php echo $dados_app_multi_plataforma["url_logo"];?>',
                body: '<?php echo $array_lang[$dados_stm["idioma_painel"]]["notificacao_ativada"]; ?>'
              }
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
              registrations[0].showNotification('<?php echo $dados_app_multi_plataforma["nome"];?>', opcoes);
            });
            <?php } ?>
          }
        });
      } else if (result.isDenied) {
        $.cookie('permissao_<?php echo $dados_stm["login"]; ?>', 'negada', { expires: 15, path: '/' });
      }
    })
  }
}

}, 5000);

setInterval(function(){ 
  $.get("/player-app-multi-plataforma/<?php echo $dados_stm["login"];?>/carregar-notificacao", function( resposta ) {
  var JSON_VAR = JSON.stringify(resposta, null, 2);
  var dados = JSON.parse(JSON_VAR);
  exibir_notificacao(dados[0].codigo, dados[0].titulo, dados[0].url_icone, dados[0].url_imagem, dados[0].url_link, dados[0].mensagem);
  }, 'json');
}, 10000);

function exibir_notificacao(codigo, titulo, icone, imagem, link, mensagem) {
  var opcoes = {
    <?php if($Android || $iPhone) { ?>
    vibrate: [300, 100, 400],
    tag: codigo,
    data: {
     url: link
    },
    <?php } ?>  
    body: mensagem,
    icon: icone,
    image: imagem
  }

  <?php if($Windows) { ?>
  var notification = new Notification(titulo, opcoes);
  if(link) {
    notification.onclick = function(event) {event.preventDefault();window.open(link, '_self');};
  }
  notification.onshow = function() {$.cookie('app_<?php echo $dados_stm["login"]; ?>_notify_'+codigo+'', 'vizualizada', { expires: 360, path: '/' });};
  <?php } else { ?>
  navigator.serviceWorker.getRegistrations().then(function(registrations) {
  registrations[0].showNotification(titulo, opcoes);
  $.cookie('app_<?php echo $dados_stm["login"]; ?>_notify_'+codigo+'', 'vizualizada', { expires: 360, path: '/' });
  });
  <?php } ?>  
}
</script>
<?php } ?>
<script type="module">!function(){"use strict";const i={isOpen:!1,orientation:void 0},e=(i,e)=>{window.dispatchEvent(new CustomEvent("devtoolschange",{detail:{isOpen:i,orientation:e}}))},n=({emitEvents:n=!0}={})=>{const o=window.outerWidth-window.innerWidth>160,t=window.outerHeight-window.innerHeight>160,d=o?"vertical":"horizontal";t&&o||!(window.Firebug&&window.Firebug.chrome&&window.Firebug.chrome.isInitialized||o||t)?(i.isOpen&&n&&e(!1,void 0),i.isOpen=!1,i.orientation=void 0):(i.isOpen&&i.orientation===d||!n||e(!0,d),i.isOpen=!0,i.orientation=d)};n({emitEvents:!1}),setInterval(n,500),"undefined"!=typeof module&&module.exports?module.exports=i:window.devtools=i}();window.addEventListener('devtoolschange', event => {if(event.detail.isOpen){window.location.replace("https://<?php echo $_SERVER["HTTP_HOST"]; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>");}});</script>
<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js', {useCache: false, scope: '/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>'})
      .then(function(registration) {

    registration.addEventListener('updatefound', () => {
      newWorker = registration.installing;
      <?php if(!empty($_GET["app-multi"])) {?>
      newWorker.addEventListener('statechange', () => {
        switch (newWorker.state) {
          case 'installed':
            if (navigator.serviceWorker.controller) {
              Swal.fire({title: '',icon: 'warning',html: "<?php echo $array_lang[$dados_stm["idioma_painel"]]["aviso_atualizar"]; ?>"}).then((result) => { if (result.isConfirmed) { newWorker.postMessage({ action: 'skipWaiting' }); window.location.reload(); }})
            }
            break;
        }
      });
      <?php } ?>
    });
  });
}
if (('standalone' in navigator) && (!navigator.standalone)) {
    import('https://unpkg.com/pwacompat');
}
$(window).on("keydown",function(e){return 123==e.keyCode?!1:e.ctrlKey&&e.shiftKey&&73==e.keyCode?!1:e.ctrlKey&&73==e.keyCode?!1:void 0}),$(document).on("contextmenu",function(e){e.preventDefault()});
$(document).keydown(function(e){var o=String.fromCharCode(e.keyCode).toLowerCase();return!e.ctrlKey||"c"!=o&&"u"!=o?void 0:!1});</script>
<?php if(empty($_GET["app-multi"])) {?>
<script>
window.onload=function(){

let installPromptEvent;
window.addEventListener('beforeinstallprompt', (event) => {
  event.preventDefault();
  installPromptEvent = event;
});
window.addEventListener('appinstalled', () => {
  installPromptEvent = null;
  window.location = 'https://<?php echo $_SERVER["HTTP_HOST"]; ?>/player-app-multi-plataforma/<?php echo $dados_stm["login"]; ?>?app-multi=<?php echo time(); ?>';
});
var buttonInstall = document.getElementById("instalar");
buttonInstall.addEventListener('click', (e) => {
  installPromptEvent.prompt();    
  installPromptEvent.userChoice.then((choice) => {}); 
});
}
function instalar_app_iphone() {
  Swal.fire({title: '',icon: 'info',html: "<?php echo $array_lang[$idioma_usuario]["aviso_instalar_iphone"]; ?>"})
}
</script>
<?php } ?>
</body>
</html>