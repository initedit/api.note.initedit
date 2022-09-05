FROM composer:1.10.0 AS build
WORKDIR /opt
COPY . .
RUN composer validate
RUN composer install --prefer-dist --no-progress --no-suggest

FROM webdevops/php-apache:7.4
COPY --from=build /opt /app
RUN mkdir -p /app/storage/logs/
RUN chown application:application -R /app/storage/logs/
ENV WEB_DOCUMENT_ROOT=/app/public
EXPOSE 80
COPY env_update.sh /env_update.sh && chmod +x /env_update.sh
ENTRYPOINT ["/bin/bash","-c","/env_update.sh && /entrypoint"]