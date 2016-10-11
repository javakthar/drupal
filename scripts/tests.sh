#!/bin/sh

composer global require "hirak/prestissimo:^0.3"
composer require "wikimedia/composer-merge-plugin:~1.3" --no-interaction
composer update --no-interaction
drush si standard --y --account-name=admin --account-pass=admin --db-url=mysql://drupal:drupal@mariadb:3306/drupal
php ./core/scripts/run-tests.sh --url http://localhost:8000/ --php /usr/bin/php --dburl mysql://drupal:drupal@mariadb:3306/drupal 'Drupal\Tests\Component\Utility\ArgumentsResolverTest'
php ./core/scripts/run-tests.sh --php /usr/bin/php PHPUnit
cd core
../vendor/bin/phpunit tests/Drupal/Tests/Core/Password/PasswordHashingTest.php