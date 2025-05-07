FROM php:8.3-apache

# ------------------------------------------------------------------
# Dependências de runtime + build (pdo_mysql, intl) e ferramentas
# ------------------------------------------------------------------
RUN apt-get update && apt-get install -y --no-install-recommends \
    libicu-dev git unzip \
    && docker-php-ext-install pdo_mysql mysqli intl \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ---------------------------  NOVO  -------------------------------
# O Apache deve servir /var/www/html/public (onde fica index.php do CI4)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN a2enmod rewrite \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf
# ------------------------------------------------------------------

WORKDIR /var/www/html



