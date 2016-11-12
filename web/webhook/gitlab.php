<?php

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

$server = filter_input_array(INPUT_SERVER);

if (!empty($server['HTTP_HOST']))
{

    if ($branch === 'refs/heads/master' && $server['HTTP_HOST'] === 'wheelpro.ru')
    {
        chdir('/home/wheelpro/web/www/');

        $output = shell_exec("git checkout master");
        fwrite($fs, $output . PHP_EOL);

        $output = shell_exec("git pull origin master");
        fwrite($fs, $output . PHP_EOL);

        $output = shell_exec("composer install");
        fwrite($fs, $output . PHP_EOL);
    }
    else if ($branch === 'refs/heads/dev' && $server['HTTP_HOST'] === 'dev.wheelpro.ru')
    {
        chdir('/home/wheelpro/web/dev/');

        $output = shell_exec("git checkout dev");
        fwrite($fs, $output . PHP_EOL);

        $output = shell_exec("git pull origin dev");
        fwrite($fs, $output . PHP_EOL);

        // for apiGen
        shell_exec("rm -fr ../doc/*"); // remove docs
        shell_exec("rm -fr /tmp/_apigen/*"); // remove temp files

        $apiGen = "php ../apigen.phar generate --config apigen.yaml";

        $output = shell_exec($apiGen);
        fwrite($fs, $output . PHP_EOL);
    }

    $output = shell_exec("composer install");
    fwrite($fs, $output . PHP_EOL);

    $output = shell_exec("composer update");
    fwrite($fs, $output . PHP_EOL);

}

$output = shell_exec("redis-cli flushall");
fwrite($fs, $output . PHP_EOL);

$fs and fclose($fs);