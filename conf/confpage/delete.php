<?php
if($_POST['type'] == 'ajax'){
shell_exec('bash /usr/share/jitsi-meet/confpage/chdat.sh leer');
$sala = $_POST['sala'];
$dominio = $_SERVER['HTTP_HOST'];
$rutaSalas = str_replace('.', '%2e', $dominio);
$rutaSalas = '/var/lib/prosody/conference%2e'.$rutaSalas.'/config';
$archivo = $rutaSalas.'/'.$sala.'.dat';
//shell_exec('sudo /etc/init.d/prosody stop');
shell_exec('rm '.$archivo);
//shell_exec('sudo /etc/init.d/prosody start');
shell_exec('bash /usr/share/jitsi-meet/confpage/chdat.sh');
echo $sala." was succefully deleted. It make take some seconds for rooms to start working again.";
}
?>
