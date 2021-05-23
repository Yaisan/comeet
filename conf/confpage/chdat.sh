#!/bin/bash
if [[ $1 == "leer" ]];
then
  sudo chmod 757 /var/lib/prosody/
  for i in $(ls /var/lib/prosody/conference%2emeet%2eyaisan%2ecat/config/)
  do
    sudo chmod 747 /var/lib/prosody/conference%2emeet%2eyaisan%2ecat/config/$i
  done
  for i in $(ls /var/lib/prosody/meet%2eyaisan%2ecat/accounts/)
  do
    sudo chmod 744 /var/lib/prosody/meet%2eyaisan%2ecat/accounts/$i
  done
else
  for i in $(ls /var/lib/prosody/conference%2emeet%2eyaisan%2ecat/config/)
  do
    sudo chmod 740 /var/lib/prosody/conference%2emeet%2eyaisan%2ecat/config/$i
  done
  for i in $(ls /var/lib/prosody/meet%2eyaisan%2ecat/accounts/)
  do
    sudo chmod 740 /var/lib/prosody/meet%2eyaisan%2ecat/accounts/$i
  done
  sudo chmod 750 /var/lib/prosody/
fi
