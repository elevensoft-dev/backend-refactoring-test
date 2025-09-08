###########################################################
# Dependendo da sua arquiterura, mude aqui
# arm64v8 é para MacOS com arquitetura arm64 tipo os M1
###########################################################

#FROM arm64v8/nginx:alpine
FROM nginx:alpine

RUN mkdir /var/www/html -p && touch /var/www/html/index.php

RUN rm /etc/nginx/nginx.conf

COPY nginx.conf /etc/nginx/nginx.conf
