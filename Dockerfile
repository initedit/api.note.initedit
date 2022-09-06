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
COPY start.sh /start.sh
RUN chmod +x /start.sh
EXPOSE 80
ENTRYPOINT ["/start.sh"]