<?php

$root = realpath(__DIR__ . '/../../');

$server = filter_input_array(INPUT_SERVER);

$user     = 'wbs';
$password = 'Xn5Z0EeCzmlvUkB1Dwqb';

if (!empty($server['HTTP_HOST']) && $server['HTTP_HOST'] == 'wbs-cms')
{
    $user     = 'root';
    $password = '';
}

return array(
    'default' => array(
        'driver'     => 'pdo',
        'connection' => 'mysql:host=localhost;dbname=wbs',
        'user'       => $user,
        'password'   => $password
    )
);