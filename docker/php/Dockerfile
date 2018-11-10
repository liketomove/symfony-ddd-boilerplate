FROM matiux/php:7.2.11-fpm-alpine3.8-dev

RUN echo 'alias memflush="echo \"flush_all\" | nc servicememcached 11211 -q 1"' >> /home/utente/.zshrc \
    && echo 'alias test="./vendor/bin/simple-phpunit"' >> /home/utente/.zshrc \
    && echo 'alias sfcc="rm -Rf var/cache/*"' >> /home/utente/.zshrc

#COPY --chown=utente:utente ssh/* /home/utente/.ssh/
#RUN  chmod 600 /home/utente/.ssh/id_rsa
