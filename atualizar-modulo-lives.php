<?php
require_once("admin/inc/conecta.php");

mysqli_query($conexao,"ALTER TABLE `lives` ADD `servidor_stm` VARCHAR(255) NOT NULL AFTER `tipo`, ADD `servidor_live` VARCHAR(255) NOT NULL AFTER `servidor_stm`;") or die("<strong>Erro ao processar query.</strong><br><br>Execute a query manualmente via phpmyadmin:<br><br><em>ALTER TABLE `lives` ADD `servidor_stm` VARCHAR(255) NOT NULL AFTER `tipo`, ADD `servidor_live` VARCHAR(255) NOT NULL AFTER `servidor_stm`;</em><br><br>");


echo "OK";
?>