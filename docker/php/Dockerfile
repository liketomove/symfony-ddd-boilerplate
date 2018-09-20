FROM matiux/php:7.1-fpm-2.2.0

RUN apt-get update && apt-get install -y netcat && \
    /usr/local/bin/composer self-update && \
    echo 'alias memflush="echo \"flush_all\" | nc servicememcached 11211 -q 1"' >> /home/utente/.bashrc && \
    echo 'alias test="vendor/bin/phpunit"' >> /home/utente/.bashrc
