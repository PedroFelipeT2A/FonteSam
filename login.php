<?php
if(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == "pt") {
  $lang_titulo = "Gerenciamento";
  $lang_login = "Login";
  $lang_senha = "Senha";
  $lang_versao_movel = "Versão para Celular";
} elseif(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == "en") {
  $lang_titulo = "Management";
  $lang_login = "Login";
  $lang_senha = "Password";
  $lang_versao_movel = "Mobile Version";
} elseif(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == "es") {
  $lang_titulo = "Administración";
  $lang_login = "Login";
  $lang_senha = "Contraseña";
  $lang_versao_movel = "Versión Móvil";
} else {
  $lang_titulo = "Gerenciamento";
  $lang_login = "Login";
  $lang_senha = "Senha";
  $lang_versao_movel = "Versão para Celular";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Gerenciamento de Streaming</title>
  <meta http-equiv="cache-control" content="no-cache">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<style>
  body{
    background:url(https://i.pinimg.com/originals/d6/68/ab/d668abc72809303852c27275e6a56775.gif) no-repeat;
    background-size: cover;
    background-position: center;
    height: 100vh;
}
.card-login{
    width: 400px;
    margin: auto; 
    padding-top:10%;
    height: 550px;   
}
 
.card-login .card{  
    background: rgba(255,255,255,0.7); 
    position: relative;
    border-bottom:4px solid #3c6aca;
}
.card-login .logotipo{ 
  text-align: center;
    padding: auto 1.3em; 
    color:#777;
}
.card-login .logotipo img{ 
  height: 120px;
}
.card-login .footer{ 
  text-align: center;
    font-size: 12px;
    position: absolute;
    bottom: 0;
    width:100%;
    left:0;        
}
.card-login .footer a{ 
   color:#777;
}
.card-login input{ 
 border-radius: 0;
}
.copyright{
    position: fixed;
    bottom:0;
    left:0;
    width: 100%;
    padding: 3px;
    text-align: center;
}

</style>
</head>

<body style="" onload="document.login.login.focus();">
   
 <div class="container">
   <div class="card-login">
     <div class="card">
    <div class="logotipo"><img src="img/img-login-streaming.png" height="100"><br><strong><?php echo $lang_titulo; ?></strong></div>
    <form method="post" action="/login-autentica" style="margin:0px; " name="login" class="p-4">
      <div class="form-group">
        <input placeholder="<?php echo $lang_login; ?>" class="form-control" name="login" type="text" id="login">
      </div>
      <div class="form-group">
        <input placeholder="<?php echo $lang_senha; ?>" class="form-control" name="senha" type="password" id="senha">
      </div>
      <div class="form-group">
        <button class="btn btn-success btn-block">ENTRAR</button>
      </div>
    </form>

    <div class="alert"><?php echo $_SESSION["status_login"]; unset($_SESSION["status_login"]); ?>
    <div class="footer"><a href="/movel" class="texto_padrao_pequeno_branco"><?php echo $lang_versao_movel; ?></a></div>
    </div>
  </div>
  </div>
  <div class="copyright">&copy; <?=date("Y")?> -Streaming TV</div>
</div>
  
 
</body>
</html>