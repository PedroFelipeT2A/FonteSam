<?php
require_once("admin/inc/conecta.php");

@mysqli_query($conexao,"ALTER TABLE `app_multi_plataforma` ADD `modelo` INT(1) NOT NULL DEFAULT '1';");

@mysqli_query($conexao,"CREATE TABLE IF NOT EXISTS `app_multi_plataforma_notificacoes` (
  `codigo` int(10) NOT NULL AUTO_INCREMENT,
  `codigo_stm` int(10) NOT NULL DEFAULT '0',
  `codigo_app` int(10) NOT NULL DEFAULT '0',
  `titulo` VARCHAR(255) DEFAULT NULL,
  `url_icone` VARCHAR(255) DEFAULT NULL,
  `url_imagem` VARCHAR(255) DEFAULT NULL,
  `url_link` VARCHAR(255) DEFAULT NULL,
  `mensagem` VARCHAR(255) DEFAULT NULL,
  `vizualizacoes` int(10) NOT NULL DEFAULT '0',
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`codigo`)
) ENGINE=MyISAM;");

@mysqli_query($conexao,"CREATE TABLE `app_multi_plataforma_anuncios` ( `codigo` INT(10) NOT NULL AUTO_INCREMENT , `codigo_app` INT(10) NOT NULL , `nome` VARCHAR(255) NOT NULL , `banner` VARCHAR(255) NOT NULL , `link` VARCHAR(255) NOT NULL  , `data_cadastro` DATE NOT NULL , `exibicoes` INT(10) NOT NULL DEFAULT '0', `cliques` INT(10) NOT NULL DEFAULT '0', PRIMARY KEY (`codigo`)) ENGINE = MyISAM;");

@mysqli_query($conexao,"ALTER TABLE `app_multi_plataforma` ADD `apk_package` VARCHAR(255) DEFAULT NULL;");
@mysqli_query($conexao,"ALTER TABLE `app_multi_plataforma` ADD `apk_versao` VARCHAR(255) NOT NULL DEFAULT '1.0';");
@mysqli_query($conexao,"ALTER TABLE `app_multi_plataforma` ADD `apk_criado` VARCHAR(255) NOT NULL DEFAULT 'nao';");
@mysqli_query($conexao,"ALTER TABLE `app_multi_plataforma` ADD `apk_cert_sha256` VARCHAR(255) DEFAULT NULL;");
@mysqli_query($conexao,"ALTER TABLE `app_multi_plataforma` ADD `apk_zip` VARCHAR(255) DEFAULT NULL;");

echo "Instalado!";
?>