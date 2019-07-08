FROM matiux/php:7.3.6-fpm-alpine3.10-dev

USER root

RUN apk add --no-cache \
    openssh

USER utente

RUN echo 'alias memflush="echo \"flush_all\" | nc servicememcached 11211 -q 1"' >> /home/utente/.zshrc \
    && echo 'alias test="./vendor/bin/simple-phpunit"' >> /home/utente/.zshrc \
    && echo 'alias sfcc="rm -Rf var/cache/*"' >> /home/utente/.zshrc

COPY conf/xdebug-starter.sh /usr/local/bin/xdebug-starter
RUN chmod +x /usr/local/bin/xdebug-starter
RUN /usr/local/bin/xdebug-starter

RUN echo 'alias test="./vendor/bin/simple-phpunit --exclude-group slow"' >> /home/utente/.zshrc \
    && echo 'alias xon="sed -i \"s/xdebug\.remote_enable=0/xdebug\.remote_enable=1/\" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && kill -USR2 1"' >> /home/utente/.zshrc \
    && echo 'alias xoff="sed -i \"s/xdebug\.remote_enable=1/xdebug\.remote_enable=0/\" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && kill -USR2 1"' >> /home/utente/.zshrc \
    && echo 'alias test-all="./vendor/bin/simple-phpunit"' >> /home/utente/.zshrc \
    && echo 'alias sfcc="rm -Rf var/cache/*"' >> /home/utente/.zshrc

COPY --chown=utente:utente ssh/* /home/utente/.ssh/
COPY --chown=utente:utente git/.gitconfig /home/utente/.gitconfig
RUN  chmod 600 /home/utente/.ssh/id_rsa
