<?php

return [
    'drivers' => [
        \Stash\Driver\Redis::class => [],
        \Stash\Driver\Apc::class   => [
            'ttl' => 3600
        ]
    ]
];