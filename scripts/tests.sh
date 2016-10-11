#!/bin/sh

composer global require "hirak/prestissimo:^0.3"
composer require "wikimedia/composer-merge-plugin:~1.3" --no-interaction
composer update --no-interaction
drush si standard --y --account-name=admin --account-pass=admin --db-url=mysql://drupal:drupal@mariadb:3306/drupal
cd core
../vendor/bin/phpunit tests/Drupal/Tests/Core/Password/PasswordHashingTest.php
../vendor/bin/phpunit tests/Drupal/FunctionalJavascriptTests/Core/Session/SessionTest.php