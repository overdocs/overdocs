FROM ubuntu:16.04
MAINTAINER Olgierd Grzyb <kontakt@olgierd.me>
WORKDIR /srv/immunity
RUN apt-get -yqq update
RUN apt-get -yqq upgrade
RUN apt-get -yqq --no-install-recommends install nginx
COPY nginx.conf /etc/nginx/nginx.conf
CMD ["nginx"]
