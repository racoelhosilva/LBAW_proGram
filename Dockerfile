FROM ubuntu:24.04

# Install dependencies
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update; apt-get install -y --no-install-recommends libpq-dev vim nginx php8.3-fpm php8.3-mbstring php8.3-xml php8.3-pgsql php8.3-curl ca-certificates

# Copy project code and install project dependencies
COPY --chown=www-data . /var/www/

# Copy project configurations
COPY ./etc/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./etc/nginx/default.conf /etc/nginx/sites-enabled/default
COPY .env.production /var/www/.env
COPY docker_run.sh /docker_run.sh

# Start command
CMD sh /docker_run.sh
