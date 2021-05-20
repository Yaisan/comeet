#! /bin/bash
# Function for installation tasks
function comeet(){
  # Variables.
  let proc=6
  trash="/dev/null"
  current_user=$(whoami)
  # Introductory text.
  echo "================================================================================"
  echo -e " Welcome \e[1;34m"$current_user"\e[0m - to the bash script installation of the  "
  echo " necessary resources for comeet  "
  echo "================================================================================"
  # We indicate to the user that we begin the tasks.
  echo
  echo "[Performing tasks, please wait a moment (Do not close the terminal) ...]"
  echo
  echo "Remaining processes:"$proc
  # Tasks.
  echo "Set a domain" read DOMAIN 
  #
  echo "Mounting the docker container"
  docker build - t jitsi-- build - arg DOMAIN = $DOMAIN.docker run - p 443: 443 / tcp - p 80: 80 / tcp - p 10000: 10000 / udp - p 3478: 3478 / udp - p 5349:5349 / tcp-- name contenedor - jitsi - d jitsi &> $trash #
  let "proc -= 1"
  echo "Remaining processes: "$proc
  #
  echo "Starting the nginx service in the container"
  docker exec - it contenedor - jitsi service nginx start &> $trash #
  let "proc -= 1"
  echo "Remaining processes: "$proc
  #
  echo "Starting the jitsi-videobridge2 service in the container"
  docker exec - it contenedor - jitsi service jitsi - videobridge2 start &> $trash #
  let "proc -= 1"
  echo "Remaining processes: "$proc
  #
  echo "Starting the prosody service in the container"
  docker exec - it contenedor - jitsi service prosody start &> $trash #
  let "proc -= 1"
  echo "Remaining processes: "$proc
  #
  echo "Starting the jicofo service in the container"
  docker exec - it contenedor - jitsi service jicofo start &> $trash #
  let "proc -= 1"
  echo "Remaining processes: "$proc
  #
  apt-get autoremove -y &> $s_null #
  # We indicate to the user that we have completed the tasks.
  echo
  echo "[Tasks completed successfully]"
  echo
  # We release Variables.
  proc=
  trash=
  current_user=
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