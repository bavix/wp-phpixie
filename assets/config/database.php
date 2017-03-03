<?php

return [
    'default' => [
        'charset'  => '%database.charset%',
        'driver'   => '%database.driver%',
        'database' => '%database.database%',
        'user'     => '%database.user%',
        'password' => '%database.password%',
        'adapter'  => '%database.adapter%',

        'connectionOptions' => [
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    ]
];