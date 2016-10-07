<?php

define('MY_ORG_ID', '9947e435-01dd-4aa7-a467-fb1567c19537');
// Server where to deploy the new instance.
define('MY_SERVER_ID', 'f8b23aae-079b-4fb5-916c-e095702f46ed');
// Application to deploy.
define('MY_APP_ID', 'ba42c254-264a-4295-96f4-68516a658a01');
// Use this instance to copy db and files.
define('MY_APP_SOURCE_INSTANCE_ID', 'a6573194-8043-488c-ba20-0c8fd3547f4d');

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

echo "Deploying new instance via Wodby...", PHP_EOL;
$api->task()->wait($task->getId(), 600);

echo "Reload instance", PHP_EOL;
$instance = $api->instance()->load($instance->getId());

echo "Done!", PHP_EOL;
var_dump($instance);

echo "Importing build", PHP_EOL;
$result = $api->instance()
  ->importCodebase($instance->getId(), $_SERVER['CIRCLE_BUILD_URL'] . '/build.tar.gz');

/** @var Entity\Task $task */
$task = $result['task'];
$api->task()->wait($task->getId(), 600);

echo "Done!", PHP_EOL;
