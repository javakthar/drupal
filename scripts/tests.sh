#!/bin/sh

set -eo pipefail
composer global require "hirak/prestissimo:^0.3"
composer require "wikimedia/composer-merge-plugin:~1.3" --no-interaction
composer update --no-interaction
cd core
phpunit core/tests/Drupal/Tests/Core/Password/PasswordHashingTest.php
phpunit core/tests/Drupal/KernelTests/Component/Utility/SafeMarkupKernelTest.php
../vendor/bin/phpunit core/tests/Drupal/FunctionalTests/Breadcrumb/Breadcrumb404Test.php
../vendor/bin/phpunit core/tests/Drupal/FunctionalJavascriptTests/Core/Session/SessionTest.php