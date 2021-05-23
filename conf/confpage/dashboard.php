<!DOCTYPE html>
<html>
    <?php
    const SALT = 'Manduca123';
    $user = $_POST["user"];
    $password = $_POST["password"];

    $json = file_get_contents("/usr/share/jitsi-meet/confpage/admin.json");
    $json_a = json_decode($json, true);

    if(!($user == $json_a["user"] && hash("sha256", SALT . $password) == $json_a["password"])){
        header("Location: /admin.php?error=true", TRUE, 301);
        exit();
    }
    ?>
    <head>
        <title>YAISAN</title>
        <meta name="description" content="A synthesis project of two SMX students" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!--CSS-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.2/css/bulma-rtl.min.css"/>
        <!--JS-->
        <script src="https://kit.fontawesome.com/ac40c2f10c.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            function SureDelete(roomName){
              if(document.getElementById("textInputName".concat(roomName)).style.visibility == "hidden"){
                if (window.confirm("Do you really want to delete the room?. It will cause other rooms to go down for some seconds.")) {
                  Delete(roomName);
                }
              } else {
                  //boton save
                  document.getElementById("icon".concat(roomName)).classList.remove('fa-check');
                  document.getElementById("icon".concat(roomName)).classList.add('fa-edit');
                  document.getElementById("buttonEdit".concat(roomName)).classList.remove('is-success');
                  document.getElementById("buttonEdit".concat(roomName)).classList.add('is-info');
                  document.getElementById("buttonEditText".concat(roomName)).innerHTML = "Edit";
                  document.getElementById("roomName".concat(roomName)).style.visibility = "visible";
                  document.getElementById("textInputName".concat(roomName)).style.visibility = "hidden";
                  document.getElementById("roomPassword".concat(roomName)).style.visibility = "visible";
                  document.getElementById("textInputPassword".concat(roomName)).style.visibility = "hidden";
                  //boton cancel
                  document.getElementById("iconDelete".concat(roomName)).classList.remove('fa-times');
                  document.getElementById("iconDelete".concat(roomName)).classList.add('fa-trash');
                  document.getElementById("buttonDeleteText".concat(roomName)).innerHTML = "Delete";
              }
            }
            function Delete(roomName){
              $.ajax({
                method: "POST",
                url: "delete.php",
                data: {sala: roomName, type: 'ajax'}
              }).done(function(response){
                location.reload();
                //alert(response);
              });
            }
            function Edit(roomName){
              if(document.getElementById("textInputName".concat(roomName)).style.visibility == "hidden"){
                //boton save
                document.getElementById("icon".concat(roomName)).classList.remove('fa-edit');
                document.getElementById("icon".concat(roomName)).classList.add('fa-check');
                document.getElementById("buttonEdit".concat(roomName)).classList.remove('is-info');
                document.getElementById("buttonEdit".concat(roomName)).classList.add('is-success');
                document.getElementById("buttonEditText".concat(roomName)).innerHTML = "Save";
                document.getElementById("roomName".concat(roomName)).style.visibility = "hidden";
                document.getElementById("textInputName".concat(roomName)).style.visibility = "visible";
                document.getElementById("textInputName".concat(roomName)).value = roomName;
                document.getElementById("roomPassword".concat(roomName)).style.visibility = "hidden";
                document.getElementById("textInputPassword".concat(roomName)).style.visibility = "visible";
                document.getElementById("textInputPassword".concat(roomName)).value = document.getElementById("roomPassword".concat(roomName)).innerHTML;
                //boton cancel
                document.getElementById("iconDelete".concat(roomName)).classList.remove('fa-trash');
                document.getElementById("iconDelete".concat(roomName)).classList.add('fa-times');
                document.getElementById("buttonDeleteText".concat(roomName)).innerHTML = "Cancel";
              } else {
                var newRoomName = document.getElementById("textInputName".concat(roomName)).value;
                var newRoomPassword = document.getElementById("textInputPassword".concat(roomName)).value;
                var roomId = document.getElementById("id".concat(roomName)).innerHTML;
                $.ajax({
                  method: "POST",
                  url: "edit.php",
                  data: {room: roomName, roomID: roomId,newRoom: newRoomName, newPassword: newRoomPassword ,type: 'ajax'}
                }).done(function(response){
                  location.reload();
                  //alert(response);
                });
              }
            }
            function Create(){
              $.ajax({
                  method: "POST",
                  url: "create.php",
                  data: {type: 'ajax'}
                }).done(function(response){
                  location.reload();
                  //alert(response);
                });
            }
            function Apply(){
              window.location.replace("https://".concat(document.location.hostname));
              $.ajax({
                  method: "POST",
                  url: "apply.php",
                  data: {type: 'ajax'}
                }).done(function(response){
                  location.reload();
                  //alert(response);
                });
            }
            function User(Action, Uservar){
              var Username = "";
              var Password = "";
              if(Action == "create"){
                Uservar = "";
                while(Username == "" || Username.includes(".")){
                  Username = window.prompt("Username: ");
                }
                while(Password == ""){
                  Password = window.prompt("Password: ");
                }
              }
              if(Action == "delete"){
                if (window.confirm("Do you really want to delete the user?")) {
                } else {
                  return;
                }
              }
              if(Action == "edit"){
                while(Password == "")
                  Password = window.prompt("Password: ");
              }
              if(Action == "admin"){
                Uservar = "";
                while(Username == "" || Username.includes(".")){
                  Username = window.prompt("Admin username: ");
                }
                while(Password == ""){
                  Password = window.prompt("Admin password: ");
                }
              }
              $.ajax({
                  method: "POST",
                  url: "user.php",
                  data: {type: 'ajax', action: Action, username:Username, password: Password, user: Uservar}
                }).done(function(response){
                  location.reload();
                  if(response != ""){
                    if(response != "login"){
                      alert(response);
                    } else {
                      window.location.replace("https://".concat(document.location.hostname).concat("/admin.php"));
                    }
                  }
                });
            }
        </script>
    </head>
    <body>
        <!--Navbar-->
        <nav class="navbar" role="navigation" aria-label="main navigation">
          <div class="navbar-brand">
            <a class="navbar-item" href="https://yaisan.github.io/comeet/">
              <img src="https://raw.githubusercontent.com/Yaisan/comeet/a4a127f19e8abb6e0612de5e5b184b17fde6b214/docs/img/Logo.svg" width="112" height="28">
            </a>
            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
              <span aria-hidden="true"></span>
              <span aria-hidden="true"></span>
              <span aria-hidden="true"></span>
            </a>
          </div>
        </nav>
        <!--Main-->
        <div class="box">
        <section style="padding-bottom:10px" class="section">
            <figure>
                <img src="https://raw.githubusercontent.com/Yaisan/comeet/a4a127f19e8abb6e0612de5e5b184b17fde6b214/docs/img/LogoComeet.svg">
              </figure>
              <br>
              <article class="message">
                <div class="message-body">
                  In this page you can edit the rooms that are enabled and users that can host the rooms, once you ended creating rooms and users, please press the apply button so changes on server can be done. If not, new rooms may not be available.
                </div>
              </article>
            <button class="button is-info is-outlined" onclick='User("admin")'><i class="fas fa-edit"></i>&nbsp;Edit login credentials</button>
            </section>
        </div>
        <!--Tool-->
        <div class="box">
            <?php
            $dominio = $_SERVER['HTTP_HOST'];
            $rutaSalas = str_replace('.', '%2e', $dominio);
            $rutaSalas = '/var/lib/prosody/conference%2e'.$rutaSalas.'/config';
            $listaId = [];
            shell_exec('bash /usr/share/jitsi-meet/confpage/chdat.sh leer');
            echo '<div class="columns">
            <div class="column"></div>
            <div class="column is-three-fifths">
              <div class="table-container">
                <table class="table is-fullwidth is-hoverable">
                  <thead>
                    <tr>
                      <th>Rooms</th>
                      <th>ID</th>
                      <th>Password</th>
                      <th style="width:auto"><button class="button is-primary is-outlined" onclick=\'Create()\'><i class="fas fa-plus"></i>&nbsp;New room</button>
                          <button class="button is-link is-outlined" onclick=\'Apply()\'><i class="fas fa-save"></i>&nbsp;Apply</button>
                     </th>
                    </tr>
                  </thead>';
            if (is_dir($rutaSalas)){
                $gestor = opendir($rutaSalas);
                $arraySalas = [];
                while (($archivo = readdir($gestor)) !== false)  {
                    if (is_file($rutaSalas."/".$archivo)) {
                        array_push($arraySalas,$archivo);
                    }
                }
                sort($arraySalas);
                foreach ($arraySalas as $archivo){
                  $roomName = substr($archivo, 0, -4);
                  $roomPassword = "";
                  $roomId = "";
                  $lectura = file($rutaSalas."/".$archivo);
                  foreach ($lectura as $value) {
                    $data = explode(" = ", $value);
                    if(count($data) == 2){
                      $parametro = $data[0];
-                     $parametro = substr($parametro, 2);
                      //echo $parametro;
                      $valor = $data[1];
                      $valor = substr($valor, 1);
                      $valor = substr($valor, 0, -3);
                      if($parametro == '["password"]'){$roomPassword = $valor;}
                      if($parametro == '["meetingId"]'){$roomId = $valor;}
                    }
                  }
                        echo '<tr>
                                  <td>
                                      <div style="position:relative;min-width:100px">
                                      <span style="position:absolute;top:8px" id=\'roomName'.$roomName.'\'>'.$roomName.'</span>
                                      <input style="visibility:hidden;position:absolute;top:0px;left:0px" id=\'textInputName'.$roomName.'\' class="input" type="text" placeholder="Room name">
                                      </div>
                                  </td>
                                  <td style="vertical-align:middle"><span id=\'id'.$roomName.'\'>'.$roomId.'</span></td>
                                  <td>
                                      <div style="position:relative;min-width:100px">
                                      <span style="position:absolute;top:8px" id=\'roomPassword'.$roomName.'\'>'.$roomPassword.'</span>
                                      <input style="visibility:hidden;position:absolute;top:0px;left:0px" id=\'textInputPassword'.$roomName.'\' class="input" type="text" placeholder="Room password">
                                      </div>
                                  </td>
                                  <td style="width:230px">
                                      <p style="width:230px;margin-right:0px" class="buttons">
                                      <button style="width:90px;margin-left:0px" id=\'buttonEdit'.$roomName.'\' class="button is-info is-outlined" onclick=\'Edit("'.$roomName.'")\'>
                                          <span class="icon is-small">
                                              <i id=\'icon'.$roomName.'\' class="fas fa-edit"></i>
                                          </span>&nbsp;&nbsp;
                                          <span id=\'buttonEditText'.$roomName.'\'>Edit</span>
                                      </button>
                                      &nbsp;
                                      <button style="width:108px" id=\'buttonDelete'.$roomName.'\' class="button is-danger is-outlined" onclick=\'SureDelete("'.$roomName.'")\'>
                                          <span class="icon is-small">
                                              <i id=\'iconDelete'.$roomName.'\' class="fas fa-trash"></i>
                                          </span>&nbsp;&nbsp;
                                          <span id=\'buttonDeleteText'.$roomName.'\'>Delete</span>
                                      </button>
                                      </p>
                                  </td>
                              </tr>';
                }
            closedir($gestor);
            }
            echo  '</tbody>
            </table>
            </div>
            </div>
            <div class="column"></div>
            <div class="column is-one-fifth">
            <table class="table is-fullwidth is-hoverable">
                  <thead>
                    <tr>
                      <th>Users</th>
                      <th><button class="button is-primary is-outlined" onclick=\'User("create")\'><i class="fas fa-plus"></i>&nbsp;New user</button></th>
                    </tr>
                  </thead>
                  <tbody>';
                      $usersPath = str_replace('.', '%2e', $dominio);
                      $usersPath = '/var/lib/prosody/'.$usersPath.'/accounts';
                      if (is_dir($usersPath)){
                        $gestor = opendir($usersPath);
                        while (($archivo = readdir($gestor)) !== false)  {
                          if (is_file($usersPath."/".$archivo)) {
                            $user = $archivo;

                            echo '
                              <tr>
                                <td>'.substr($user,0,-4).'</td>
                                <td style="width:285px"><button class="button is-info is-outlined" onclick=\'User("edit","'.$user.'")\'><i class="fas fa-edit"></i>&nbsp;Edit password</button>
                                                        <button class="button is-danger is-outlined" onclick=\'User("delete","'.$user.'")\'><i class="fas fa-trash"></i>&nbsp;Delete</button></td>
                              </tr>
                            ';
                          }
                        }
                        closedir($gestor);
                      }
                      shell_exec('bash /usr/share/jitsi-meet/confpage/chdat.sh');
                  echo '</tbody>
            </table>
            </div>
            <div class="column"></div>
            </div>
            ';
            ?>
        </div>
        <!--footer-->
        <footer class="footer">
            <div class="content has-text-centered">
              <p>
                 by <a href="https://github.com/Yelsier">Yago Claros</a> and <a href="https://github.com/SantitoGit">Santiago Guzman</a>
              </p>
            </div>
          </footer>
    </body>
</html>

