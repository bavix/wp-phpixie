<?php

chdir('..');

exec('git pull', $output);

var_dump($output);