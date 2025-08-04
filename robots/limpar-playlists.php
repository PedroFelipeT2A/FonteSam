<?php
ini_set("memory_limit", "128M");
ini_set("max_execution_time", 3600);

require_once("../admin/inc/conecta.php");

$query_playlists = mysqli_query($conexao,"SELECT * FROM playlists");
while ($dados_playlist = mysqli_fetch_array($query_playlists)) {
	
$verifica_stm = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where codigo = '".$dados_playlist["codigo_stm"]."'"));

if($verifica_stm == 0) {
mysqli_query($conexao,"Delete From playlists where codigo = '".$dados_playlist["codigo"]."'");
mysqli_query($conexao,"Delete From playlists_videos where codigo_playlist = '".$dados_playlist["codigo"]."'");

}

}

$query_playlists_musica = mysqli_query($conexao,"SELECT * FROM playlists_videos");
while ($dados_playlist_musica = mysqli_fetch_array($query_playlists_musica)) {

$verifica_playlist = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM playlists where codigo = '".$dados_playlist_musica["codigo_playlist"]."'"));

if($verifica_playlist == 0) {
mysqli_query($conexao,"Delete From playlists_videos where codigo = '".$dados_playlist_musica["codigo"]."'");
}

}

echo "[".date("d/m/Y H:i:s")."] Processo Concluído."
?>