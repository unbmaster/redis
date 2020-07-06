FROM redis:6.0.5

MAINTAINER UnBMaster <unbmaster@outlook.br>

COPY redis.conf /usr/local/etc/redis/redis.conf

CMD [ "redis-server", "/usr/local/etc/redis/redis.conf" ]