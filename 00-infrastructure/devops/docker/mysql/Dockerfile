FROM mysql:8.0

RUN usermod -u 1000 mysql

#USER mysql

COPY create-databases.sql /docker-entrypoint-initdb.d/create-databases.sql
