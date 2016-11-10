<?php

$pswd         = 'ltiN\p[R7Yz*nj/e';
$access_token = '_D1d^+{NK#T.b9q-4*&IMHj:mJk"]Y[fCRA6l;89S0Us&cVQgWP?}!/E5wv7oXuZ';

$client_token = $_SERVER['HTTP_X_GITLAB_TOKEN'] ?? '';
$client_ip    = $_SERVER['REMOTE_ADDR'];

$fs = fopen('../../logs/gitlab.log', 'a');

fwrite($fs, 'Request on [' . date("Y-m-d H:i:s") . '] from [' . $client_ip . ']' . PHP_EOL);

if ($client_token !== $access_token)
{
    echo "error 403";
    fwrite($fs, "Invalid token [{$client_token}]" . PHP_EOL);
    exit(0);
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

fwrite($fs, '=======================================================================' . PHP_EOL);

$branch = trim($data["ref"]);

fwrite($fs, 'BRANCH: ' . print_r($branch, true) . PHP_EOL);
fwrite($fs, '=======================================================================' . PHP_EOL);

$connection = ssh2_connect('localhost', 22);
ssh2_auth_password($connection, 'wheelpro', 'yynK5DJTgseXW48e');

if ($branch == 'refs/heads/master')
{
    $stream = ssh2_exec($connection, 'cd /home/wheelpro/web/www/ && git reset --hard origin/master && git pull');
}
else
{
    $stream = ssh2_exec($connection, 'cd /home/wheelpro/web/dev/ && git reset --hard origin/dev && git pull');
}

$fs and fclose($fs);