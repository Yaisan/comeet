<?php
if($_POST['type'] == 'ajax'){
shell_exec('bash /usr/share/jitsi-meet/confpage/chdat.sh leer');
$dominio = $_SERVER['HTTP_HOST'];
$rutaSalas = str_replace('.', '%2e', $dominio);
$rutaSalas = '/var/lib/prosody/conference%2e'.$rutaSalas.'/config';

$gestor = opendir($rutaSalas);
$arraySalas = [];
while (($archivo = readdir($gestor)) !== false)  {
  if (is_file($rutaSalas."/".$archivo)) {
     array_push($arraySalas,$archivo);
  }
}
closedir($gestor);
$rooms = "";
foreach ($arraySalas as $value){
  $rooms = $rooms.'|'.substr($value, 0, -4);
}
$rooms = substr($rooms, 1);

shell_exec('sudo chmod 646 /etc/nginx/sites-available/'.$dominio.'.conf');

$file = fopen('/etc/nginx/sites-available/'.$dominio.'.conf','w');

$lectura = file('/usr/share/jitsi-meet/confpage/nginx.conf');
foreach ($lectura as $value) {
  $value = str_replace('$DOMAIN', $dominio, $value);
  $value = str_replace('$ROOMS', $rooms, $value);
  fwrite($file, $value);
}
fclose($file);

shell_exec('sudo chmod 644 /etc/nginx/sites-available/'.$dominio.'.conf');
shell_exec('sudo /etc/init.d/nginx restart');
}
?>
