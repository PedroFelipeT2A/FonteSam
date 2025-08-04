<?php
require_once("admin/inc/conecta.php");

@mysqli_query($conexao,"CREATE TABLE `playlists_agendamentos_logs`( `codigo` int(10) NOT NULL, `codigo_agendamento` int(10) NOT NULL DEFAULT 0, `codigo_stm` int(10) NOT NULL DEFAULT 0, `data` datetime DEFAULT NULL, `playlist` varchar(255) DEFAULT NULL) ENGINE=MyISAM;");

@mysqli_query($conexao,"ALTER TABLE `playlists_agendamentos` ADD `servidor_relay` VARCHAR(255) NOT NULL AFTER `codigo_playlist`;");

@mysqli_query($conexao,"ALTER TABLE `playlists_agendamentos` ADD `frequencia` INT(1) NOT NULL DEFAULT '1' AFTER `servidor_relay`;");

@mysqli_query($conexao,"ALTER TABLE `playlists_agendamentos` ADD `dias` VARCHAR(50) NOT NULL AFTER `minuto`;");

@mysqli_query($conexao,"ALTER TABLE `playlists_agendamentos` ADD `tipo` VARCHAR(50) NOT NULL DEFAULT 'playlist' AFTER `dias`;");

@mysqli_query($conexao,"ALTER TABLE `streamings` ADD `ultima_playlist` INT(10) NOT NULL;");

@mysqli_query($conexao,"ALTER TABLE `playlists_agendamentos` ADD `shuffle` CHAR(3) NOT NULL DEFAULT 'nao';");

@mysqli_query($conexao,"ALTER TABLE `playlists_agendamentos` ADD `finalizacao` CHAR(20) NOT NULL DEFAULT 'repetir';");
@mysqli_query($conexao,"ALTER TABLE `playlists_agendamentos` ADD `codigo_playlist_finalizacao` INT(10) NOT NULL DEFAULT '0';");


echo "OK";
?>