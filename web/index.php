<?php

declare(strict_types = 1);

date_default_timezone_set('Europe/Moscow');

define('__DIR_WEB__', __DIR__ . '/');

require_once dirname(__DIR__) . '/vendor/autoload.php';

$framework = new Project\Framework();

$framework->registerDebugHandlers();
$framework->processHttpSapiRequest();