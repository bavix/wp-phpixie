<?php

require_once(__DIR__ . '/../vendor/autoload.php');
var_dump(1);
$framework = new Project\Framework();
$framework->registerDebugHandlers();
$framework->processHttpSapiRequest();