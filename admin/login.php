
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Gerenciamento</title>
	<link rel="stylesheet" href="/admin/inc/form-login/vendor_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="/admin/inc/form-login/css/style.css">
	<link rel="stylesheet" href="/admin/inc/form-login/css/skin_color.css">	
</head>
<body class="hold-transition theme-fruit bg-img" style="background-image: url(/admin/inc/form-login/images/auth-bg/bg-3rtl.jpg);">
	
	<div class="h-p100">
		<div class="row align-items-center justify-content-md-center h-p100">
			<div class="col-lg-8 col-12">
				<div class="row justify-content-center no-gutters">
					<div class="col-xl-4 col-lg-7 col-md-6 col-12">
						<div class="content-top-agile p-10">
							<div class="logo">
								<a href="#" class="aut-logo my-40 d-block">
									<img src="/admin/inc/form-login/images/lock.png" width="55" height="55" alt="">
								</a>
							</div>
							<h2 class="text-white">Gerenciamento</h2>	
                             					
						</div>
						<div class="p-30">
						<form action="/admin/login-autentica" method="post" name="login">
								<div class="form-group">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text text-white bg-transparent"><i class="ti-user"></i></span>
										</div>
										<input type="text" name="email" class="form-control pl-15 bg-transparent text-white plc-white" placeholder="E-mail">
									</div>
								</div>
								<div class="form-group">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text text-white bg-transparent"><i class="ti-lock"></i></span>
										</div>
										<input name="senha" type="password" class="form-control pl-15 bg-transparent text-white plc-white" placeholder="Senha">
									</div>
								</div>
								  <div class="row">
									
									<div class="col-12 text-center">
									  <button type="submit" class="btn btn-warning btn-outline mt-10">Entrar</button>
									</div>
                                   
									<!-- /.col -->
								  </div>
							</form>														

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="/admin/inc/form-login/vendor_components/jquery-3.3.1/jquery-3.3.1.js"></script>
	<script src="/admin/inc/form-login/vendor_components/screenfull/screenfull.js"></script>
	<script src="/admin/inc/form-login/vendor_components/popper/dist/popper.min.js"></script>
	<script src="/admin/inc/form-login/vendor_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
