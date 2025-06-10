FROM alpine:latest
LABEL maintainer="Félix MARQUET <felix.mrqt@breizhhardware.fr>"

# Installation des paquets nécessaires
RUN apk add --no-cache \
    apache2 \
    php81-apache2 \
    php81 \
    php81-pdo \
    php81-pdo_pgsql \
    php81-json \
    php81-session \
    php81-mbstring \
    php81-xml \
    php81-openssl \
    php81-curl

# Activation du module rewrite
RUN sed -i 's/#LoadModule rewrite_module/LoadModule rewrite_module/' /etc/apache2/httpd.conf

# Configuration Apache
COPY apache2.conf /etc/apache2/conf.d/custom.conf

# Répertoire de travail
WORKDIR /var/www/html

# Exposer le port 80
EXPOSE 80

# Commande de démarrage
CMD ["httpd", "-D", "FOREGROUND"]