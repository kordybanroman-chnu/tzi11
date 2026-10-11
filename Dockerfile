FROM php:8.2-alpine
RUN apk add --no-cache postgresql-dev && docker-php-ext-install pgsql
COPY src/ /var/www/html/
WORKDIR /var/www/html/
EXPOSE 80
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-80} -t . router.php"]
