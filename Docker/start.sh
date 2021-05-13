#!/bin/bash

if [ "$EUID" -ne 0 ];
  if ! dpkg -s docker &> /dev/null;
    then
    sudo apt install docker
  fi
  if ! dpkg -s docker.io &> /dev/null;
    then
    sudo apt install docker.io
  fi

  then
  sudo docker build -t jitsi --build-arg DOMAIN=$1 .
  sudo docker run -p 443:443/tcp -p 80:80/tcp -p 10000:10000/udp -p 3478:3478/udp -p 5349:5349/tcp --name contenedor-jitsi -d jitsi
  sudo docker exec -it contenedor-jitsi service nginx start
  sudo docker exec -it contenedor-jitsi service jitsi-videobridge2 start
  sudo docker exec -it contenedor-jitsi service prosody start
  sudo docker exec -it contenedor-jitsi service jicofo start
else
  if ! dpkg -s docker &> /dev/null;
    then
    apt install docker
  fi
  if ! dpkg -s docker.io &> /dev/null;
    then
    apt install docker.io
  fi
  docker build -t jitsi --build-arg DOMAIN=$1 .
  docker run -p 443:443/tcp -p 80:80/tcp -p 10000:10000/udp -p 3478:3478/udp -p 5349:5349/tcp --name contenedor-jitsi -d jitsi
  docker exec -it contenedor-jitsi service nginx start
  docker exec -it contenedor-jitsi service jitsi-videobridge2 start
  docker exec -it contenedor-jitsi service prosody start
  docker exec -it contenedor-jitsi service jicofo start
fi
