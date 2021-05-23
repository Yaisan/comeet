<?php
const SALT = 'Manduca123';
if($_POST['type'] == 'ajax'){
$action = $_POST['action'];
$dominio = $_SERVER['HTTP_HOST'];
shell_exec('bash /usr/share/jitsi-meet/confpage/chdat.sh leer');
if($action == 'create'){
  $username = $_POST['username'];
  $password = $_POST['password'];

  $usersPath = str_replace('.', '%2e', $dominio);
  $usersPath = '/var/lib/prosody/'.$usersPath.'/accounts';
  $existe = false;

  if (is_dir($usersPath)){
    $gestor = opendir($usersPath);
    while (($archivo = readdir($gestor)) !== false)  {
      if (is_file($usersPath."/".$archivo)) {
        if(substr($archivo,0,-4) == $username){
          $existe = true;
        }
      }
    }
    closedir($gestor);
  }

  if($existe){
    echo 'User already exists';
  } else {
    shell_exec('sudo prosodyctl register '.$username.' '.$dominio.' '.$password);
  }
}

if($action == "edit"){
  $user = substr($_POST['user'], 0, -4);
  $password = $_POST['password'];
  shell_exec('sudo prosodyctl register '.$user.' '.$dominio.' '.$password);
}

if($action == "delete"){
  $user = substr($_POST['user'], 0, -4);
  shell_exec('sudo prosodyctl deluser '.$user.'@'.$dominio);
}

if($action == "admin"){
  $username = $_POST['username'];
  $password = $_POST['password'];
  shell_exec('sudo chmod 747 /usr/share/jitsi-meet/confpage/admin.json');
  $json = file_get_contents("/usr/share/jitsi-meet/confpage/admin.json");
  $json_a = json_decode($json, true);

  $json_a["user"] = $username;
  $json_a["password"] = hash("sha256", SALT . $password);

  $newjson = json_encode($json_a);
  file_put_contents("/usr/share/jitsi-meet/confpage/admin.json", $newjson);
  shell_exec('sudo chmod 744 /usr/share/jitsi-meet/confpage/admin.json');
  echo "login";
}

shell_exec('bash /usr/share/jitsi-meet/confpage/chdat.sh');
}
?>
