<?php
$dados_stm = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM streamings where porta = '".query_string('2')."'"));

if(query_string('3') == "programacao") {
$texto = $dados_stm["app_text_prog"];
} else {
$texto = $dados_stm["app_text_hist"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
<style>
body {
	background-color:#eaeaea;
	padding:10px;
	margin: 0px auto;
	overflow: hidden;
}
</style>
</head>

<body>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
		<div class="panel-body">
			<?php echo $texto; ?>
		</div>
		</div>
	</div>					 
</div>
</body>
</html>
