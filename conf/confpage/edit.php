<?php
if($_POST['type'] == 'ajax'){
shell_exec('bash /usr/share/jitsi-meet/confpage/chdat.sh leer');
$sala = $_POST['room'];
$roomId = $_POST['roomID'];
$newRoom = $_POST['newRoom'];
$newPassword = $_POST['newPassword'];
$dominio = $_SERVER['HTTP_HOST'];
$rutaSalas = str_replace('.', '%2e', $dominio);
$rutaSalas = '/var/lib/prosody/conference%2e'.$rutaSalas.'/config';
$archivo = $rutaSalas.'/'.$sala.'.dat';
shell_exec('sudo /etc/init.d/prosody stop');
shell_exec('rm '.$archivo);
$file = fopen($rutaSalas.'/'.$newRoom.'.dat','w');

$lectura = file('/usr/share/jitsi-meet/confpage/salas.dat');
foreach ($lectura as $value) {
  $value = str_replace('$domain', $dominio, $value);
  $value = str_replace('$id', $roomId, $value);
  $value = str_replace('$name', $newRoom, $value);
  $value = str_replace('$password', $newPassword, $value);
  fwrite($file, $value);
}
fclose($file);
shell_exec('sudo chown prosody:prosody '.$rutaSalas.'/'.$newRoom.'.dat');
shell_exec('sudo /etc/init.d/prosody start');
shell_exec('bash /usr/share/jitsi-meet/confpage/chdat.sh');
echo 'Room updated successfully, It make take some seconds for rooms to start working again.';
}
?>

