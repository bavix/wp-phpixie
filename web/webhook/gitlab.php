<?php

include_once '../../vendor/autoload.php';

$pswd         = 'ltiN\p[R7Yz*nj/e';
$access_token = '_D1d^+{NK#T.b9q-4*&IMHj:mJk"]Y[fCRA6l;89S0Us&cVQgWP?}!/E5wv7oXuZ';

$client_token = isset($_SERVER['HTTP_X_GITLAB_TOKEN']) ? $_SERVER['HTTP_X_GITLAB_TOKEN'] : '';
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


$wrapper = new \GitWrapper\GitWrapper();

$wrapper->setPrivateKey('/home/wheelpro/sshkeys/wheelpro');

$git = $wrapper->workingCopy(dirname(dirname(__DIR__)));

if ($branch == 'refs/heads/master')
{
    $git->checkout('master');
}
else
{
    $git->checkout('dev');
}

$git->pull();

fwrite($fs, $git->getOutput(), PHP_EOL);

$fs and fclose($fs);