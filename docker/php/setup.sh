#!/bin/bash
# Prepare state directories and generate keys
set -e
echo "Creating state directories"
for dir in /data/cache /data/logs
do
    mkdir -p $dir
    chown www-data:www-data $dir
done

echo "Installing Composer"
curl https://getcomposer.org/composer.phar > /usr/local/bin/composer.phar
chmod +x /usr/local/bin/composer
