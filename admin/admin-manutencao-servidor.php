<?php
require_once("inc/protecao-admin.php");

$dados_config = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM configuracoes"));
$dados_servidor = mysqli_fetch_array(mysqli_query($conexao,"SELECT * FROM servidores WHERE codigo = '".code_decode(query_string('2'),"D")."'"));
$total_stm = mysqli_num_rows(mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".$dados_servidor["codigo"]."'"));

// Conexão SSH
$ssh = new SSH();
$ssh->conectar($dados_servidor["ip"],$dados_servidor["porta_ssh"]);
$ssh->autenticar("root",code_decode($dados_servidor["senha"],"D"));

if(empty(query_string('3'))) {

$load_atual = $ssh->executar("cat /proc/loadavg | awk {'print $1'} | tr '\n' ' '");
$hora_atual = $ssh->executar('/bin/date "+%T %:z"');

$uso_hd_atual = $ssh->executar("df -H | grep -vE '^Filesystem|tmpfs|cdrom|boot' | awk '{ print \$3 \" / \" \$2 }'");
$porcentagem_hd = $ssh->executar("df -H | grep -vE '^Filesystem|tmpfs|cdrom|boot' | awk '{ print \$5 \" \" $1 }' | cut -d % -f 1");

$uso_memoria_atual = $ssh->executar("free -g | grep Mem | awk '{ print \$3 \"G / \" \$2 \"G\"}'");
$porcentagem_memoria = $ssh->executar("free | awk '/Mem/{printf(\"%.0f\"), $3/($2+.000000001)*100} /buffers\/cache/{printf(\", buffers: %.1f%\"), $4/($3+$4)+.000000001*100}'");

$porcentagem_uso_streamings = $total_stm*100/$dados_servidor["limite_streamings"];
$porcentagem_load = intval($load_atual)*100/100;

}

// Funções
if(query_string('3') == "reiniciar-apache") {
	
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl stop httpd || /etc/init.d/httpd stop");
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl start httpd || /etc/init.d/httpd start");

	$_SESSION["status_acao"] = status_acao("Apache reiniciado com sucesso.","ok");
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
	
}
if(query_string('3') == "reiniciar-ftp") {
	
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl stop pure-ftpd || /etc/init.d/pure-ftpd stop");
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl start pure-ftpd || /etc/init.d/pure-ftpd start");

	$_SESSION["status_acao"] = status_acao("FTP reiniciado com sucesso.","ok");
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
	
}
if(query_string('3') == "reiniciar-cron") {
	
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl stop crond || /etc/init.d/crond stop");
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl start crond || /etc/init.d/crond start");

	$_SESSION["status_acao"] = status_acao("Cron reiniciado com sucesso.","ok");
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
	
}
if(query_string('3') == "ajustar-hora") {
	
	$ssh->executar("rm -Rf /etc/localtime;ln -s /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime;date +%T -s \"".date("H:i:s")."\";echo OK");

	$_SESSION["status_acao"] = status_acao("Hora do servidor de streaming ajustada para mesma hora do painel de controle: ".date("H:i:s")."","ok");
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
	
}
if($_POST["cron_editar"] == "sim") {

	file_put_contents("../temp/".$dados_servidor["nome"].".cron",$_POST["cron_nova"]);
	
	$ssh->enviar_arquivo("../temp/".$dados_servidor["nome"].".cron","/var/spool/cron/root",0600);
	
	$ssh->executar("sed -i -e 's/\r$//' /var/spool/cron/root");
	
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl reload crond || /etc/init.d/crond reload");
	
	unlink("../temp/".$dados_servidor["nome"].".cron");
	
	$_SESSION["status_acao"] = status_acao("Novas tarefas cron do servidor de streaming salvas com sucesso.","ok");
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
}
if($_POST["ftp_config_editar"] == "sim") {

	file_put_contents("../temp/".$dados_servidor["nome"].".ftp",$_POST["ftp_config_nova"]);
	
	$ssh->enviar_arquivo("../temp/".$dados_servidor["nome"].".ftp","/etc/pure-ftpd/pureftpd-mysql.conf",0644);
	
	$ssh->executar("sed -i -e 's/\r$//' /etc/pure-ftpd/pureftpd-mysql.conf");
	
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl stop pure-ftpd || /etc/init.d/pure-ftpd stop");
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl start pure-ftpd || /etc/init.d/pure-ftpd start");
	
	unlink("../temp/".$dados_servidor["nome"].".ftp");
	
	$_SESSION["status_acao"] = status_acao("Novas configurações do FTP do servidor de streaming salvas com sucesso.","ok");
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
}
if(query_string('3') == "resolver-problemas") {
	
	$ssh->executar("chown streaming.streaming /home/streaming -Rf;echo OK");
	$ssh->executar("echo 'nameserver 8.8.8.8' > /etc/resolv.conf;echo 'nameserver 8.8.4.4' >> /etc/resolv.conf;echo OK");
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl restart httpd || /etc/init.d/httpd restart");
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl restart pure-ftpd || /etc/init.d/pure-ftpd restart");
	$ssh->executar("/bin/sync;echo 3 > /proc/sys/vm/drop_caches;echo OK");
	$ssh->executar("iptables -F");
	
	$_SESSION["status_acao"] .= status_acao("Configurado DNS do Google.","ok");
	$_SESSION["status_acao"] .= status_acao("Corrigido permissões do /home/streaming.","ok");
	$_SESSION["status_acao"] .= status_acao("Reiniciado apache e FTP.","ok");
	$_SESSION["status_acao"] .= status_acao("Memória Ram em cache liberada.","ok");
	$_SESSION["status_acao"] .= status_acao("Regras iptables removidas.","ok");
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
	
}
if(query_string('3') == "atualizar-ffmpeg") {
	
	$ssh->executar("nohup cd /root;wget https://downloads.sourceforge.net/lame/lame-3.99.5.tar.gz;tar -zxf lame-3.99.5.tar.gz;cd /root/lame-3.99.5;./configure --enable-shared --enable-nasm;make;make install;make distclean;echo '/usr/local/lib' >> /etc/ld.so.conf;echo '/usr/lib' >> /etc/ld.so.conf;cd /root;git clone http://git.videolan.org/git/x264.git;cd x264;./configure --enable-shared --enable-pic --disable-asm && make && make install;cd /root;git clone https://git.ffmpeg.org/ffmpeg.git;cd ffmpeg;./configure --enable-nonfree --enable-openssl --disable-yasm --enable-libx264 --enable-pic --enable-pic --enable-gpl --enable-shared --enable-decoder=aac --enable-filter=aformat --enable-filter=volume --enable-filter=aresample && make && make install;ldconfig 2>&1");

	$_SESSION["status_acao"] = status_acao("Ffmpeg atualizando em segundo plano, deve levar 30 minutos para concluir.","alerta");
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
	
}
if(query_string('3') == "atualizar-youtubedl") {
	
	$ssh->executar("wget https://yt-dl.org/latest/youtube-dl -O /usr/local/bin/youtube-dl --no-check-certificate;chmod a+x /usr/local/bin/youtube-dl;hash -r");

	$_SESSION["status_acao"] = status_acao("Youtube-dl atualizado com sucesso.","ok");
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
	
}
if(query_string('3') == "ssl-instalar") {

	$dominio_ssl = strtolower($dados_servidor["nome"]).".".$dados_config["dominio_padrao"];

	$ssh->executar("[ -d /usr/local/WowzaStreamingEngine-4.5.0 ] && rm -fv /etc/init.d/WowzaMediaServer /usr/bin/WowzaMediaServerd;echo OK");
	$ssh->executar("[ -f /etc/init.d/WowzaStreamingEngine ] && /etc/init.d/WowzaStreamingEngine stop;echo OK");
	$ssh->executar("[ -f /etc/init.d/WowzaMediaServer ] && /etc/init.d/WowzaMediaServer stop;echo OK");
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl stop WowzaStreamingEngine;echo OK");
	
	$ssh->executar("service httpd stop;wget -O /etc/certbot-auto https://dl.eff.org/certbot-auto;chmod a+x /etc/certbot-auto;/etc/certbot-auto -n --agree-tos --register-unsafely-without-email certonly --standalone -d ".$dominio_ssl."");
	
	$resultado_ssl = $ssh->executar("[ -f /etc/letsencrypt/live/".$dominio_ssl."/cert.pem ] && echo -n OK || echo -n ERRO");
	
	if($resultado_ssl == "OK") {
	
	$config_ssl = 'LoadModule ssl_module modules/mod_ssl.so
Listen 1443
SSLPassPhraseDialog  builtin
SSLSessionCache         shmcb:/var/cache/mod_ssl/scache(512000)
SSLSessionCacheTimeout  300
SSLRandomSeed startup file:/dev/urandom  256
SSLRandomSeed connect builtin
SSLCryptoDevice builtin';
	
	$vhost_ssl = '
NameVirtualHost *:1443

<VirtualHost *:1443>
    DocumentRoot /home/streaming/web
    ServerName '.$dominio_ssl.'

    SSLEngine on
    SSLProtocol all -SSLv2
    SSLCipherSuite DEFAULT:!EXP:!SSLv2:!DES:!IDEA:!SEED:+3DES
    SSLCertificateFile /etc/letsencrypt/live/$dominio_servidor/cert.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/$dominio_servidor/privkey.pem
    SSLCertificateChainFile /etc/letsencrypt/live/$dominio_servidor/chain.pem
</VirtualHost>

';
	
	file_put_contents("../temp/".$dados_servidor["nome"].".ssl",$config_ssl);
	$ssh->enviar_arquivo("../temp/".$dados_servidor["nome"].".ssl","/etc/httpd/conf.d/ssl.conf",0644);
	unlink("../temp/".$dados_servidor["nome"].".ssl");

	file_put_contents("../temp/".$dados_servidor["nome"].".ssl",$vhost_ssl);
	$ssh->enviar_arquivo("../temp/".$dados_servidor["nome"].".ssl","/etc/httpd/conf.d/ssl-".$dados_servidor["nome"].".conf",0644);
	unlink("../temp/".$dados_servidor["nome"].".ssl");
	
	$ssh->executar("mkdir /usr/local/WowzaMediaServer/ssl; rm -f /usr/local/WowzaMediaServer/ssl/*;openssl pkcs12 -export -in /etc/letsencrypt/live/".$dominio_ssl."/fullchain.pem -inkey /etc/letsencrypt/live/".$dominio_ssl."/privkey.pem -name ".$dominio_ssl." -out /usr/local/WowzaMediaServer/ssl/certificado.p12 -password pass:pGePkkuZ7HeMM922aU97;keytool -importkeystore -noprompt -keypass pGePkkuZ7HeMM922aU97 -srcstorepass pGePkkuZ7HeMM922aU97 -deststorepass pGePkkuZ7HeMM922aU97 -destkeystore /usr/local/WowzaMediaServer/ssl/certificado.jks -srckeystore /usr/local/WowzaMediaServer/ssl/certificado.p12 -srcstoretype PKCS12;keytool -import -noprompt -keypass pGePkkuZ7HeMM922aU97 -storepass pGePkkuZ7HeMM922aU97 -alias bundle -trustcacerts -file /etc/letsencrypt/archive/".$dominio_ssl."/chain1.pem -keystore /usr/local/WowzaMediaServer/ssl/certificado.jks");

	$ssh->executar("[ -f /etc/init.d/WowzaStreamingEngine ] && /etc/init.d/WowzaStreamingEngine start;echo OK");
	$ssh->executar("[ -f /etc/init.d/WowzaMediaServer ] && /etc/init.d/WowzaMediaServer start;echo OK");
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl start WowzaStreamingEngine;echo OK");

	$ssh->executar("service httpd restart;echo OK");
	
	$_SESSION["status_acao"] = status_acao("SSL instalado com sucesso para ".$dominio_ssl."","ok");
	} else {
	$_SESSION["status_acao"] = status_acao("Erro ao gerar SSL para ".$dominio_ssl." verifique se DNS esta configurado e propagado.","erro");
	}
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
	
}
if(query_string('3') == "ssl-renovar") {
	
	$dominio_ssl = strtolower($dados_servidor["nome"]).".".$dados_config["dominio_padrao"];
	
	$ssh->executar("[ -d /usr/local/WowzaStreamingEngine-4.5.0 ] && rm -fv /etc/init.d/WowzaMediaServer /usr/bin/WowzaMediaServerd;echo OK");
	$ssh->executar("[ -f /etc/init.d/WowzaStreamingEngine ] && /etc/init.d/WowzaStreamingEngine stop;echo OK");
	$ssh->executar("[ -f /etc/init.d/WowzaMediaServer ] && /etc/init.d/WowzaMediaServer stop;echo OK");
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl stop WowzaStreamingEngine;echo OK");
	
	$ssh->executar("service httpd stop;wget -O /etc/certbot-auto https://dl.eff.org/certbot-auto;chmod a+x /etc/certbot-auto;/etc/certbot-auto -n --agree-tos --register-unsafely-without-email certonly --standalone --force-renew -d ".$dominio_ssl."");
	
	$resultado_ssl = $ssh->executar("[ -f /etc/letsencrypt/live/".$dominio_ssl."/cert.pem ] && echo -n OK || echo -n ERRO");
	
	if($resultado_ssl == "OK") {
	
	$ssh->executar("mkdir /usr/local/WowzaMediaServer/ssl; rm -f /usr/local/WowzaMediaServer/ssl/*;openssl pkcs12 -export -in /etc/letsencrypt/live/".$dominio_ssl."/fullchain.pem -inkey /etc/letsencrypt/live/".$dominio_ssl."/privkey.pem -name ".$dominio_ssl." -out /usr/local/WowzaMediaServer/ssl/certificado.p12 -password pass:pGePkkuZ7HeMM922aU97;keytool -importkeystore -noprompt -keypass pGePkkuZ7HeMM922aU97 -srcstorepass pGePkkuZ7HeMM922aU97 -deststorepass pGePkkuZ7HeMM922aU97 -destkeystore /usr/local/WowzaMediaServer/ssl/certificado.jks -srckeystore /usr/local/WowzaMediaServer/ssl/certificado.p12 -srcstoretype PKCS12;keytool -import -noprompt -keypass pGePkkuZ7HeMM922aU97 -storepass pGePkkuZ7HeMM922aU97 -alias bundle -trustcacerts -file /etc/letsencrypt/archive/".$dominio_ssl."/chain1.pem -keystore /usr/local/WowzaMediaServer/ssl/certificado.jks");

	$ssh->executar("[ -f /etc/init.d/WowzaStreamingEngine ] && /etc/init.d/WowzaStreamingEngine start;echo OK");
	$ssh->executar("[ -f /etc/init.d/WowzaMediaServer ] && /etc/init.d/WowzaMediaServer start;echo OK");
	$ssh->executar("[ -f /usr/bin/systemctl ] && /usr/bin/systemctl start WowzaStreamingEngine;echo OK");
	
	$ssh->executar("service httpd restart;echo OK");
	
	$_SESSION["status_acao"] = status_acao("SSL renovado com sucesso para ".$dominio_ssl."","ok");
	} else {
	$_SESSION["status_acao"] = status_acao("Erro ao renovar SSL para ".$dominio_ssl." verifique se DNS esta configurado e propagado.","erro");
	}
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
	
}
if(query_string('3') == "atualizar-servidor") {
	
	$ssh->executar("nohup yum update -y &");

	$_SESSION["status_acao"] = status_acao("Atualizando servidor em segundo plano, deve levar 30 minutos para concluir.","alerta");
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
	
}
if(query_string('3') == "liberar-espaco-hd") {
	
	$ssh->executar("/bin/rm -rf /var/log/httpd/*-*;/bin/rm -rf /var/log/*-20*;/bin/rm -rf /var/spool/clientmqueue/*;/bin/echo -n > /var/spool/mail/root;/bin/cp -f /dev/null /etc/httpd/logs/deflate_log;/bin/cp -f /dev/null /etc/httpd/logs/access_log;/bin/cp -f /dev/null /etc/httpd/logs/error_log;echo OK");	
	$ssh->executar("rm -fv /home/streaming/*.gz /home/streaming/core.* /home/streaming/web/*.mp3 /root/core.*");	
	$ssh->executar("/bin/echo -n > /usr/local/WowzaMediaServer/logs/wowzastreamingengine_access.log;/bin/echo -n > /usr/local/WowzaMediaServer/logs/wowzastreamingengine_error.log;rm -f /usr/local/WowzaMediaServer/logs/wowzastreamingengine_*.log.*;echo OK");	

	$_SESSION["status_acao"] = status_acao("Liberação de espaço efetuada com sucesso com remoção de logs antigos e arquivos de despejo de memória.","ok");
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
	
}
if(query_string('3') == "otimizar-servidor") {
	
	$ssh->executar("echo 'modprobe ip_conntrack' >> /etc/rc.local;echo 'modprobe ip_conntrack' >> /etc/rc.d/rc.local;modprobe ip_conntrack;echo OK");		
	$ssh->executar("sed -i '/max_execution_time/d' /etc/php.ini;sed -i '/max_input_time/d' /etc/php.ini;sed -i '/max_input_vars/d' /etc/php.ini;sed -i '/post_max_size/d' /etc/php.ini;sed -i '/upload_max_filesize/d' /etc/php.ini;sed -i '/memory_limit/d' /etc/php.ini;sed -i '/max_file_uploads/d' /etc/php.ini;echo '' >> /etc/php.ini;echo ';Tunning Cesar - cesarlwh@gmail.com' >> /etc/php.ini;echo 'max_execution_time = 1800' >> /etc/php.ini;echo 'max_input_time = 1800' >> /etc/php.ini;echo 'max_input_vars = 5000' >> /etc/php.ini;echo 'post_max_size = 200M' >> /etc/php.ini;echo 'upload_max_filesize = 200M' >> /etc/php.ini;echo 'memory_limit = 1024M' >> /etc/php.ini;echo 'max_file_uploads = 200' >> /etc/php.ini;service httpd reload;echo OK");	
	$ssh->executar("echo 30 > /proc/sys/net/ipv4/tcp_fin_timeout;echo 30 > /proc/sys/net/ipv4/tcp_keepalive_intvl;echo 5 > /proc/sys/net/ipv4/tcp_keepalive_probes;echo 5 > /proc/sys/net/ipv4/tcp_keepalive_probes;echo 1 > /proc/sys/net/ipv4/tcp_tw_reuse;echo 5 > /proc/sys/net/ipv4/tcp_fin_timeout;echo OK");	
	$ssh->executar("> /etc/sysctl.conf;echo '# TUNNING...' >> /etc/sysctl.conf;echo 'net.core.wmem_max=12582912' >> /etc/sysctl.conf;echo 'net.core.rmem_max=12582912' >> /etc/sysctl.conf;echo 'net.ipv4.tcp_rmem= 10240 87380 12582912' >> /etc/sysctl.conf;echo 'net.ipv4.tcp_wmem= 10240 87380 12582912' >> /etc/sysctl.conf;echo 'net.ipv4.tcp_window_scaling = 1' >> /etc/sysctl.conf;echo 'net.ipv4.tcp_timestamps = 1' >> /etc/sysctl.conf;echo 'net.ipv4.tcp_sack = 1' >> /etc/sysctl.conf;echo 'net.ipv4.tcp_no_metrics_save = 1' >> /etc/sysctl.conf;echo 'net.core.netdev_max_backlog = 5000' >> /etc/sysctl.conf;echo 'net.ipv4.ip_local_port_range = 1024 65535' >> /etc/sysctl.conf;echo 'net.ipv4.tcp_max_syn_backlog = 4096' >> /etc/sysctl.conf;echo 'net.core.somaxconn = 1024' >> /etc/sysctl.conf;echo 'net.ipv4.tcp_tw_recycle = 1' >> /etc/sysctl.conf;echo 'net.ipv4.tcp_tw_reuse = 1' >> /etc/sysctl.conf;echo 'fs.file-max=300000' >> /etc/sysctl.conf;echo 'net.ipv4.ip_conntrack_max = 300000' >> /etc/sysctl.conf;echo 'net.netfilter.nf_conntrack_max=300000' >> /etc/sysctl.conf;sysctl -p;echo OK");	
	$ssh->executar('echo "echo;echo Conexoes Atuais: \`cat /proc/sys/net/netfilter/nf_conntrack_count\` de \`cat /proc/sys/net/netfilter/nf_conntrack_max\`;echo" >> /root/.bash_profile;echo OK');	

	$_SESSION["status_acao"] = status_acao("Otimização do PHP, Kernel, Rede, CPU e Memória do servidor de streaming realizada com sucesso.","ok");
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
	
}
if(query_string('3') == "reconfigurar-streamings") {
	
	$sql = mysqli_query($conexao,"SELECT * FROM streamings where codigo_servidor = '".$dados_servidor["codigo"]."' ORDER by login ASC");
	while ($dados_stm = mysqli_fetch_array($sql)) {
	
	$aplicacao = ($dados_stm["aplicacao"]) ? $dados_stm["aplicacao"] : "tvstation";
	$senha_transmissao = (empty($dados_stm["senha_transmissao"])) ? $dados_stm["senha"] : $dados_stm["senha_transmissao"];
	
	$aplicacao_xml = $aplicacao;

	if($dados_stm["autenticar_live"] == "nao") {
	
	if($aplicacao == "tvstation" || $aplicacao == "live") {
	$aplicacao_xml = $aplicacao.'-sem-login';
	}
	
	}
	
	$ssh->executar("/usr/local/WowzaMediaServer/sincronizar ".$dados_stm["login"]." '".$dados_stm["senha_transmissao"]."' ".$dados_stm["bitrate"]." ".$dados_stm["espectadores"]." ".$aplicacao_xml."");
	
	$ssh->executar("/usr/bin/java -cp /usr/local/WowzaMediaServer JMXCommandLine -jmx service:jmx:rmi://localhost:8084/jndi/rmi://localhost:8085/jmxrmi -user admin -pass admin shutdownAppInstance ".$dados_stm["login"]."");
	
	$ssh->executar("/usr/bin/java -cp /usr/local/WowzaMediaServer JMXCommandLine -jmx service:jmx:rmi://localhost:8084/jndi/rmi://localhost:8085/jmxrmi -user admin -pass admin startAppInstance ".$dados_stm["login"]."");
	
	}

	$_SESSION["status_acao"] = status_acao("Streamings reconfigurados com sucesso no Wowza.","alerta");
	
	header("Location: /admin/admin-manutencao-servidor/".query_string('2')."");
	exit();
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Streaming</title>
<meta http-equiv="cache-control" content="no-cache">
<link rel="shortcut icon" href="/admin/img/favicon.ico" type="image/x-icon" />
<link href="/admin/inc/estilo.css" rel="stylesheet" type="text/css" />
<link href="/admin/inc/estilo-menu.css" rel="stylesheet" type="text/css" />
<link href="inc/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="/inc/jquery.knob.min.js"></script>
<script type="text/javascript" src="/admin/inc/sorttable.js"></script>
<script type="text/javascript">
   window.onload = function() {
	document.getElementById('log-sistema-fundo').style.display = 'none';
	document.getElementById('log-sistema').style.display = 'none';
	$(".knob").knob();
	document.getElementById('grafico_streamings').value=document.getElementById('grafico_streamings').value+'%';
	document.getElementById('grafico_load').value=document.getElementById('grafico_load').value+'%';
	document.getElementById('grafico_hd').value=document.getElementById('grafico_hd').value+'%';
	document.getElementById('grafico_memoria').value=document.getElementById('grafico_memoria').value+'%';
};
</script>
</head>

<body>
<div id="topo">
<div id="topo-conteudo"><center><span class="texto_titulo">Manuntenção Servidor</span><br /><br /><span class="texto_padrao_destaque"><?php echo $dados_servidor["nome"]; ?> - <?php echo $dados_servidor["ip"]; ?></span></center></div>
</div>
<div id="menu">
<div id="menu-links">
  	<ul>
      <li style="width:150px">&nbsp;</li>
  		<li><a href="/admin/admin-streamings" class="texto_menu">Streamings</a></li>
  		<li><em></em><a href="/admin/admin-revendas" class="texto_menu">Revendas</a></li>
        <li><em></em><a href="/admin/admin-servidores" class="texto_menu">Servidores</a></li>
        <li><em></em><a href="/admin/admin-dicas" class="texto_menu">Dicas</a></li>
        <li><em></em><a href="/admin/admin-avisos" class="texto_menu">Avisos</a></li>
        <li><em></em><a href="/admin/admin-tutoriais" class="texto_menu">Tutoriais</a></li>
        <li><em></em><a href="/admin/admin-configuracoes" class="texto_menu">Configurações</a></li>
        <li><em></em><a href="/admin/sair" class="texto_menu">Sair</a></li>
  	</ul>
</div>
</div>
<div id="conteudo">
<?php
if($_SESSION['status_acao']) {

$status_acao = stripslashes($_SESSION['status_acao']);

echo '<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-bottom:5px">'.$status_acao.'</table>';

unset($_SESSION['status_acao']);
}
?>
<?php if(empty(query_string('3'))) { ?>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid;">
<tr>
                <td width="142" height="30" align="center" bgcolor="#FFFFFF" class="texto_padrao_destaque" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid;">Streamings</td>
                <td width="142" align="center" bgcolor="#FFFFFF" class="texto_padrao_destaque" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid;">Load</td>
                <td width="142" align="center" bgcolor="#FFFFFF" class="texto_padrao_destaque" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid;">Espa&ccedil;o HD</td>
                <td width="142" align="center" bgcolor="#FFFFFF" class="texto_padrao_destaque" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid;">Mem&oacute;ria</td>
                <td width="142" align="center" bgcolor="#FFFFFF" class="texto_padrao_destaque" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid;">Tr&aacute;fego M&ecirc;s</td>
                <td width="142" align="center" bgcolor="#FFFFFF" class="texto_padrao_destaque" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid;">Tr&aacute;fego Atual</td>
                <td width="142" align="center" bgcolor="#FFFFFF" class="texto_padrao_destaque" style="background-color:#F4F4F7; border-bottom:#CCCCCC 1px solid;">Hora Atual</td>
</tr>
              <tr>
                <td height="120" align="center" bgcolor="#FFFFFF" class="texto_padrao_pequeno"><input class="knob" data-fgcolor="#0066CC" data-thickness=".3" readonly="readonly" data-min="0" data-max="100" data-width="80" data-height="80" value="<?php echo round($porcentagem_uso_streamings); ?>" id="grafico_streamings" /><br /><br /><?php echo $total_stm." / ".$dados_servidor["limite_streamings"]; ?></td>
                <td height="45" align="center" bgcolor="#FFFFFF" class="texto_padrao_pequeno"><input class="knob" data-fgcolor="#0066CC" data-thickness=".3" readonly="readonly" data-min="0" data-max="100" data-width="80" data-height="80" value="<?php echo round($porcentagem_load); ?>" id="grafico_load" /><br /><br /><?php echo $load_atual; ?></td>
                <td align="center" bgcolor="#FFFFFF" class="texto_padrao_pequeno"><input class="knob" data-fgcolor="#0066CC" data-thickness=".3" readonly="readonly" data-min="0" data-max="100" data-width="80" data-height="80" value="<?php echo round($porcentagem_hd); ?>" id="grafico_hd" /><br /><br /><?php echo $uso_hd_atual; ?></td>
                <td align="center" bgcolor="#FFFFFF" class="texto_padrao_pequeno"><input class="knob" data-fgcolor="#0066CC" data-thickness=".3" readonly="readonly" data-min="0" data-max="100" data-width="80" data-height="80" value="<?php echo round($porcentagem_memoria); ?>" id="grafico_memoria" /><br /><br /><?php echo $uso_memoria_atual; ?></td>
                <td align="center" bgcolor="#FFFFFF" class="texto_padrao"><?php echo $dados_servidor["trafego"]; ?></td>
                <td align="center" bgcolor="<?php echo ($dados_servidor["trafego_out"] > 90.0 && !preg_match("/kb/i", $dados_servidor["trafego_out"])) ? "#FFFF82" : "#FFFFFF"; ?>" class="texto_padrao"><?php echo $dados_servidor["trafego_out"]; ?></td>
                <td align="center" bgcolor="#FFFFFF" class="texto_padrao"><?php echo $hora_atual; ?></td>
              </tr>
            </table>
<br />
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid;">
  <tr>
    <td height="45" align="center"><select name="plano" class="input" id="plano" style="width:95%; height:35px; cursor:pointer;margin:10px; font-size:14px; background-color: #F0F0F0" onchange="window.location = '/admin/admin-manutencao-servidor/<?php echo query_string('2'); ?>/'+this.value;document.getElementById('log-sistema-fundo').style.display = 'block';document.getElementById('log-sistema').style.display = 'block';">
        <option value="" selected="selected" disabled="disabled" hidden style="font-size:15px; font-weight:bold;">Selecione uma opção para manutenção</option>
        <optgroup label='SSL' style="font-size:12px;">
        <option value="ssl-instalar">Instalar SSL (<?php echo strtolower($dados_servidor["nome"]).".".$dados_config["dominio_padrao"]; ?>)</option>
        <option value="ssl-renovar">Renovar SSL (<?php echo strtolower($dados_servidor["nome"]).".".$dados_config["dominio_padrao"]; ?>)</option>
        </optgroup>
        <optgroup label='Reiniciar Serviços' style="font-size:12px;">
        <option value="reiniciar-wowza">Wowza</option>
        <option value="reiniciar-apache">Apache</option>
        <option value="reiniciar-ftp">FTP</option>
        <option value="reiniciar-cron">Cron</option>
        </optgroup>
        <optgroup label='Resolver Problemas' style="font-size:12px;">
        <option value="resolver-problemas">Problemas Comuns</option>
        <option value="atualizar-ffmpeg">Atualizar FFMPEG</option>
        <option value="atualizar-youtubedl">Atualizar YouTube-DL</option>
        <option value="otimizar-servidor">Otimizar Servidor</option>
        <option value="atualizar-servidor">Atualizar Servidor(yum update)</option>
        <option value="listar-processos">Listar Processos Pesados</option>
        <option value="liberar-espaco-hd">Liberar Espaço HD(logs)</option>
        <option value="reconfigurar-streamings">Reconfigurar Streamings</option>
        </optgroup>
        <optgroup label='Geral' style="font-size:12px;">
        <option value="ajustar-hora">Ajustar Hora</option>
        <option value="cron-editar">Editar Cron</option>
        <option value="ftp-config-editar">Editar Configuração FTP</option>
        </optgroup>
        </select></td>
    </tr>
</table>
<br />
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid;">
  <tr>
    <td height="45" align="left" class="texto_padrao" style="padding:5px"><span class="texto_padrao_destaque">Instruções de Uso</span><br />
      <br />
      <strong>-SSL:</strong> Use estas op&ccedil;&otilde;es para instalar/renovar o SSL no apache que ser&aacute; usado nos utilit&aacute;rios como download do youtube, carregamento de m&uacute;sicas, upload de m&uacute;sicas e etc.<br />
      <br />
      <strong>-Reiniciar Servi&ccedil;os:</strong> Use estas op&ccedil;&otilde;es para reiniciar Apache/FTP quando m&uacute;sicas ou utilir&aacute;rios n&atilde;o estiverem funcionando, ou para reiniciar a cron.<br />
      <br />
      <strong>-Resolver Problemas:</strong> Use estas op&ccedil;&otilde;es para resolver problemas comuns, reinstalar ffmpeg ou youtube-dl respons&aacute;vel pelos utilit&aacute;rios de downloads de m&uacute;sicas.<br />
      <br />
      <strong>-Geral:</strong> Use estas op&ccedil;&otilde;es para ajustar a hora do servidor, alterar tarefas da cron, alterar dados do mysql na config. do FTP e listar processos que estejam usando mias recursos.</td>
  </tr>
</table>
<br /><br />
<?php } else { ?>

<?php if(query_string('3') == "cron-editar") { ?>
<?php
$ssh->baixar_arquivo("/var/spool/cron/root", "../temp/".$dados_servidor["nome"].".cron");
$cron_atual = file_get_contents("../temp/".$dados_servidor["nome"].".cron");
unlink("../temp/".$dados_servidor["nome"].".cron");
?>
<form action="/admin/admin-manutencao-servidor/<?php echo query_string('2'); ?>" method="post">
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid;">
  <tr>
    <td height="45" align="center" style="padding:15px"><textarea name="cron_nova" style="width:100%; height:250px;"><?php echo $cron_atual; ?></textarea><br /><br /><input type="submit" class="botao" value="Salvar" />&nbsp;<input type="button" class="botao" value="Cancelar" onclick="window.location = '/admin/admin-manutencao-servidor/<?php echo query_string('2'); ?>';" /><input name="cron_editar" type="hidden" id="cron_editar" value="sim" /></td>
  </tr>
</table>
</form>
<?php } ?>

<?php if(query_string('3') == "ftp-config-editar") { ?>
<?php
$ssh->baixar_arquivo("/etc/pure-ftpd/pureftpd-mysql.conf", "../temp/".$dados_servidor["nome"].".ftp");
$ftp_config_atual = file_get_contents("../temp/".$dados_servidor["nome"].".ftp");
unlink("../temp/".$dados_servidor["nome"].".ftp");
?>
<form action="/admin/admin-manutencao-servidor/<?php echo query_string('2'); ?>" method="post">
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid;">
  <tr>
    <td height="45" align="center" style="padding:15px"><textarea name="ftp_config_nova" style="width:100%; height:250px;"><?php echo $ftp_config_atual; ?></textarea><br /><br /><input type="submit" class="botao" value="Salvar" />&nbsp;<input type="button" class="botao" value="Cancelar" onclick="window.location = '/admin/admin-manutencao-servidor/<?php echo query_string('2'); ?>';" /><input name="ftp_config_editar" type="hidden" id="ftp_config_editar" value="sim" /></td>
  </tr>
</table>
</form>
<?php } ?>

<?php if(query_string('3') == "listar-processos") { ?>
<?php
$processos = $ssh->executar("ps -Ao pcpu,pmem,cmd  --sort=-pcpu | head -50");
?>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color:#F4F4F7; border:#CCCCCC 1px solid;">
  <tr>
    <td height="45" align="left" style="padding:15px" class="texto_padrao"><?php echo nl2br(str_replace(" ","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",str_replace("\n ","\n",$processos))); ?><br /><br /><center><input type="button" class="botao" value="Voltar" onclick="window.location = '/admin/admin-manutencao-servidor/<?php echo query_string('2'); ?>';" /></center></td>
  </tr>
</table>
<br /><br />
<?php } ?>

<?php } ?>
</div>
<!-- Início div log do sistema -->
<div id="log-sistema-fundo" style="display:block"></div>
<div id="log-sistema" style="display:block">
<div id="log-sistema-botao"><img src="/admin/img/icones/img-icone-fechar.png" onclick="document.getElementById('log-sistema-fundo').style.display = 'none';document.getElementById('log-sistema').style.display = 'none';" style="cursor:pointer" title="Fechar" /></div>
<div id="log-sistema-conteudo"><img src="/admin/img/ajax-loader.gif" /></div>
</div>
<!-- Fim div log do sistema -->
</body>
</html>
