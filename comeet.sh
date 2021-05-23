#! /bin/bash
# Function for installation tasks
function comeet(){
  # Variables.
  let proc=5
  trash="/dev/null"
  current_user=$(whoami)
  # Cool text.
  echo
  echo -e " ██████╗ ██████╗ ███╗   ███╗███████╗███████╗████████╗"
  echo -e "██╔════╝██╔═══██╗████╗ ████║██╔════╝██╔════╝╚══██╔══╝"
  echo -e "██║     ██║   ██║██╔████╔██║█████╗  █████╗     ██║   "
  echo -e "██║     ██║   ██║██║╚██╔╝██║██╔══╝  ██╔══╝     ██║   "
  echo -e "╚██████╗╚██████╔╝██║ ╚═╝ ██║███████╗███████╗   ██║   "
  echo -e " ╚═════╝ ╚═════╝ ╚═╝     ╚═╝╚══════╝╚══════╝   ╚═╝   "
  echo

  # Introductory text.
  echo "================================================================================"
  echo -e " Welcome \e[1;34m"$current_user"\e[0m - to the bash script installation of the  "
  echo " necessary resources for comeet  "
  echo "================================================================================"
  # We indicate to the user that we begin the tasks.
  echo
  echo "[Performing tasks, please wait a moment (Do not close the terminal) ...]"
  echo
  echo "Processes:"$proc
  echo
  # Tasks.
  # We verify that the docker package is installed.
  # In the case of FALSE, we install the docker.io package
  echo "check that the docker.io package is installed"
  apt-get -y install docker.io &> $trash #
  let "proc -= 1"
  echo "Remaining processes: "$proc

  # We ask the user for a domain
  echo
  echo "Set a domain"
  read DOMAIN 
  #
  echo "Mounting the docker container"
  docker build -t jitsi --build-arg DOMAIN=$DOMAIN . &> $trash #
  let "proc -= 1"
  echo "Remaining processes: "$proc
  #
  echo "Running the docker container"
  docker run -p 443:443/tcp -p 80:80/tcp -p 10000:10000/udp -p 3478:3478/udp -p 5349:5349/tcp --name contenedor-jitsi -d jitsi &> $trash #
  let "proc -= 1"
  echo "Remaining processes: "$proc
  #
  echo "Starting the services in the container"
  docker exec -it contenedor-jitsi service nginx start &> $trash #
  docker exec -it contenedor-jitsi service jitsi-videobridge2 start &> $trash #
  docker exec -it contenedor-jitsi service prosody start &> $trash #
  docker exec -it contenedor-jitsi service jicofo start &> $trash #
  let "proc -= 1"
  echo "Remaining processes: "$proc
  #
  echo "Starting to set up the configuration files"
  #
  # In these configuration files I first take the files from the source folder and rename them to the domain folder
  # Then I replace the content of the files by adding the domain
  # And finally I copy them into the docker container
  #
  docker cp ./conf/confpage contenedor-jitsi:/usr/share/jitsi-meet/confpage/ &> $trash #
  #
  mv ./conf/meet.yaisan.cat.cfg.lua ./conf/$DOMAIN.cfg.lua &> $trash #
  sed -i "s/meet.yaisan.cat/$DOMAIN/g" ./conf/$DOMAIN.cfg.lua &> $trash #
  docker cp ./conf/$DOMAIN.cfg.lua contenedor-jitsi:/etc/prosody/conf.avail/$DOMAIN.cfg.lua &> $trash #
  #
  sed -i "s/meet.yaisan.cat/$DOMAIN/g" ./conf/mod_whitelist_jicofo.lua &> $trash #
  docker cp ./conf/mod_whitelist_jicofo.lua contenedor-jitsi:/usr/share/jitsi-meet/prosody-plugins/mod_whitelist_jicofo.lua &> $trash #
  #
  mv ./conf/meet.yaisan.cat-config.js ./conf/$DOMAIN-config.js &> $trash #
  sed -i "s/meet.yaisan.cat/$DOMAIN/g" ./conf/$DOMAIN-config.js &> $trash #
  docker cp ./conf/$DOMAIN-config.js contenedor-jitsi:/etc/jitsi/meet/$DOMAIN-config.js &> $trash #
  #
  sed -i "s/meet.yaisan.cat/$DOMAIN/g" ./conf/sip-communicator.properties &> $trash #
  docker cp ./conf/sip-communicator.properties contenedor-jitsi:/etc/jitsi/jicofo/sip-communicator.properties &> $trash #
  #
  mv ./conf/meet.yaisan.cat.conf ./conf/$DOMAIN.conf &> $trash #
  sed -i "s/meet.yaisan.cat/$DOMAIN/g" ./conf/$DOMAIN.conf &> $trash #
  docker cp ./conf/$DOMAIN.conf contenedor-jitsi:/etc/nginx/sites-available/$DOMAIN.conf &> $trash #
  #
  docker cp ./conf/sudoers contenedor-jitsi:/etc/sudoers
  # Define a variable to store the modified domain for these configuration files
  NewDomain=$(echo $DOMAIN | sed -e "s/\b.\b/%2e/g")
  #
  docker exec -it contenedor-jitsi mkdir -p /var/lib/prosody/conference%2e$NewDomain/config &> $trash #
  docker exec -it contenedor-jitsi mkdir -p /var/lib/prosody/$NewDomain/accounts &> $trash #
  docker exec -it contenedor-jitsi chmod 747 /var/lib/prosody/conference%2e$NewDomain/config && chmod 755 /var/lib/prosody/conference%2e$NewDomain &> $trash #
  docker exec -it contenedor-jitsi chmod 757 /var/lib/prosody/$NewDomain/accounts && chmod 755 /var/lib/prosody/$NewDomain &> $trash #
  #
  # We indicate to the user that we have completed the tasks.
  echo
  echo "[Tasks completed successfully]"
  echo
  # We release Variables.
  proc=
  trash=
  current_user=
  NewDomain=
}
# We clean the terminal of previously executed commands.
clear
# We check that the Bash file has been run as SuperUser - root.
# In the case of FALSE, we inform the user to rerun with root permissions.
# In case of TRUE, the comeet function is called.
if [ "$(id -u)" != "0" ]; then
   echo
   echo "============================================================================"
   echo "¡This Script must be executed with SuperUser permissions!" 1>&2
   echo "============================================================================"
   echo
    exit 1
else
  comeet
    exit 1
fi
