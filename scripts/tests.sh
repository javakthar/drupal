#!/bin/sh

composer global require "hirak/prestissimo:^0.3"
composer require "wikimedia/composer-merge-plugin:~1.3" --no-interaction
composer update --no-interaction
cd core
../vendor/bin/phpunit --testsuite=unit
../vendor/bin/phpunit --testsuite=kernel