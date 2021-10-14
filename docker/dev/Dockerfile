FROM wordpress:latest

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && apt-get install -y less\
    && apt-get install -y ssmtp \
    && apt-get install -y sendmail \
    && apt-get clean

RUN composer global require wp-cli/wp-cli && \
    composer global update