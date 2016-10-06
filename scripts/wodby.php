<?php

define('MY_ORG_ID', '9947e435-01dd-4aa7-a467-fb1567c19537');
// Server where to deploy the new instance.
define('MY_SERVER_ID', 'd4fba3f3-739e-4517-a574-ac3fba06eca1');
// Application to deploy.
define('MY_APP_ID', '3c156893-efd9-4a5b-b669-1a3aca7f273f');
// Use this instance to copy db and files.
define('MY_APP_SOURCE_INSTANCE_ID', 'd11f6dd9-0827-40ec-9298-f1a454932f79');

use \Wodby\Api\Entity;

require_once __DIR__ . '/vendor/autoload.php';

$api = new Wodby\Api($_SERVER['WODBY_API_TOKEN'], new GuzzleHttp\Client());

echo PHP_EOL;
echo "Creating instance.", PHP_EOL;
$result = $api->instance()->create(
  MY_APP_ID,
  'test-' . $_SERVER['CIRCLE_BUILD_NUM'],
  Entity\Instance::TYPE_STAGE,
  $_SERVER['CIRCLE_BRANCH'],
  MY_SERVER_ID,
  "CircleCI build {$_SERVER['CIRCLE_BUILD_NUM']}",
  [
    Entity\Instance::COMPONENT_DATABASE => MY_APP_SOURCE_INSTANCE_ID,
    Entity\Instance::COMPONENT_FILES => MY_APP_SOURCE_INSTANCE_ID,
  ]
);

/** @var Entity\Task $task */
$task = $result['task'];

/** @var Entity\Instance $instance */
$instance = $result['instance'];

echo "Waiting for instance will be created", PHP_EOL;
$api->task()->wait($task->getId(), 600);

echo "Reload instance", PHP_EOL;
$instance = $api->instance()->load($instance->getId());

echo "Done!", PHP_EOL;
var_dump($instance);
