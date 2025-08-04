<?php
require_once("admin/inc/conecta.php");

mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_nome` VARCHAR(255) NOT NULL;");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_email` VARCHAR(255) NOT NULL;");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_whatsapp` VARCHAR(255) NOT NULL;");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_url_logo` VARCHAR(255) NOT NULL;");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_url_icone` VARCHAR(255) NOT NULL;");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_url_background` VARCHAR(255) NOT NULL;");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_url_facebook` VARCHAR(255) NOT NULL;");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_url_instagram` VARCHAR(255) NOT NULL;");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_url_twitter` VARCHAR(255) NOT NULL;");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_url_site` VARCHAR(255) NOT NULL;");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_url_chat` VARCHAR(255) NOT NULL;");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_cor_texto` CHAR(7) NOT NULL DEFAULT '#FFFFFF';");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_cor_menu_claro` CHAR(7) NOT NULL DEFAULT '#7386d5';");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_cor_menu_escuro` CHAR(7) NOT NULL DEFAULT '#6d7fcc';");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_url_youtube` VARCHAR(255) NOT NULL;");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_text_prog` LONGTEXT NOT NULL;");
mysqli_query($conexao,"ALTER TABLE `streamings` ADD `app_win_text_hist` LONGTEXT NOT NULL;");

echo "OK";
?>