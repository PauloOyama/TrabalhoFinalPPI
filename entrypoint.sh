#!/bin/bash
# Entrypoint para instalar as extensões necessárias do servidor PHP

docker-php-ext-install pdo pdo_mysql && \
php -S 0.0.0.0:5000
