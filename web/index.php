<?php

require_once(__DIR__ . '/../vendor/autoload.php');

define('__DIR_WEB__', __DIR__ . '/');

$framework = new Project\Framework();
$framework->registerDebugHandlers();
$framework->processHttpSapiRequest();