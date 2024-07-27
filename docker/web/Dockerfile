# php-fpm & nginx WEBSERVER
#
#
FROM app_base_image as web_base
USER root

RUN mkdir /var/www/html/web-letsencrypt
RUN mkdir /etc/letsencrypt

RUN apk --no-cache add \
    nginx \
    && rm /etc/nginx/conf.d/default.conf

# Configure nginx
COPY docker/web/config/nginx.conf /etc/nginx/nginx.conf


# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/log/nginx && \
  chown -R nobody.nobody /etc/letsencrypt
  # chown -R nobody.nobody /var/www/html
# Configure supervisord
COPY docker/web/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

USER nobody

FROM web_base as web_dev
EXPOSE 80 443
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

FROM web_base as web_prod
COPY --chown=nobody ./frontend/dist/ /var/www/html/frontend/
EXPOSE 80 443
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]


