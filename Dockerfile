FROM ubuntu:20.04

MAINTAINER YAISAN <https://github.com/Yaisan>

LABEL "authors"="Santiago Guzman and Yago Claros"
LABEL description="This is custom Docker Image for \
the Comeet App."

ARG DOMAIN=meet.yaisan.cat

RUN apt update \
    && apt -y install systemd; apt clean all;

RUN apt install -y wget curl gpg apt-transport-https software-properties-common \
    && apt update

RUN curl https://download.jitsi.org/jitsi-key.gpg.key | sh -c 'gpg --dearmor > /usr/share/keyrings/jitsi-keyring.gpg' \
    && echo 'deb [signed-by=/usr/share/keyrings/jitsi-keyring.gpg] https://download.jitsi.org stable/' | tee /etc/apt/sources.list.d/jitsi-stable.list > /dev/null \
    && echo deb http://packages.prosody.im/debian $(lsb_release -sc) main | tee -a /etc/apt/sources.list \
    && wget https://prosody.im/files/prosody-debian-packages.key -O- | apt-key add - \
    && apt update

RUN apt-get install -y prosody \
    && echo ${DOMAIN} | apt install -y jitsi-meet

CMD ["/sbin/init"]
