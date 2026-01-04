FROM nginx:alpine

ARG PUID

RUN mkdir -p /etc/nginx/sites-enabled \
    && adduser -S www-data -u ${PUID} -G www-data -h /var/cache/nginx -s /sbin/nologin

COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./conf.d/default.conf /etc/nginx/sites-available/default.conf

RUN ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf \
    && mkdir -p /var/lib/nginx/tmp && chown -R www-data:www-data /var/lib/nginx
