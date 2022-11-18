FROM mysql:8.0

COPY ./conf.d/my.cnf /etc/mysql/conf.d/
COPY ./initdb.d/ /docker-entrypoint-initdb.d
RUN touch /var/log/mysqld.log
RUN chmod 644 /etc/mysql/conf.d/my.cnf

