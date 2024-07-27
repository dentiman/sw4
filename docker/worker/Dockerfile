FROM app_base_image
USER root
# Configure supervisord
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN chown -R nobody.nobody /var/www/html
USER nobody
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
