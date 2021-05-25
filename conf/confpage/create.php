<?php
if($_POST['type'] == 'ajax'){
shell_exec('bash /usr/share/jitsi-meet/confpage/chdat.sh leer');
$dominio = $_SERVER['HTTP_HOST'];
$rutaSalas = str_replace('.', '%2e', $dominio);
$rutaSalas = '/var/lib/prosody/conference%2e'.$rutaSalas.'/config';
//shell_exec('sudo /etc/init.d/prosody stop');

$id = substr(uniqid(rand()).uniqid(rand()),0,-13);
$id = substr_replace($id, '-', 8, 0);
$id = substr_replace($id, '-', 13, 0);
$id = substr_replace($id, '-', 18, 0);
$id = substr_replace($id, '-', 23, 0);

$roomNumber = 1;
$generatedRoom = 'room'.$roomNumber;
$namesList = [];
$gestor = opendir($rutaSalas);
while (($archivo = readdir($gestor)) !== false)  {
  if (is_file($rutaSalas."/".$archivo)) {
    $roomName = substr($archivo, 0, -4);
    array_push($namesList, $roomName);
  }
}
sort($namesList);
closedir($gestor);

foreach ($namesList as $value){
  if($value == $generatedRoom){
    $roomNumber = $roomNumber + 1;
    $generatedRoom = 'room'.$roomNumber;
  }
}

$file = fopen($rutaSalas.'/'.$generatedRoom.'.dat','w');

$lectura = file('/usr/share/jitsi-meet/confpage/salas.dat');
foreach ($lectura as $value) {
  $value = str_replace('$domain', $dominio, $value);
  $value = str_replace('$id', $id, $value);
  $value = str_replace('$name', $generatedRoom, $value);
  $value = str_replace('$password', '', $value);
  fwrite($file, $value);
}
fclose($file);
shell_exec('sudo chmod 755 '.$rutaSalas.'/'.$generatedRoom.'.dat');
shell_exec('sudo chown prosody:prosody '.$rutaSalas.'/'.$generatedRoom.'.dat');

//shell_exec('sudo /etc/init.d/prosody start');
shell_exec('bash /usr/share/jitsi-meet/confpage/chdat.sh');
}
?>
