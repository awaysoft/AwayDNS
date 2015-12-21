<?php
return [
    'server' => [
        'ip' => '0.0.0.0',
        'port' => 53,
        'work_num' => 1
    ],
    'providers' => [
        'hosts' => [
            // 'path' => '/etc/hosts', /* Load system default file when no setting */
            'default_ttl' => 300,
            'refresh_time' => 60,
        ],
        'json' => [
            'data' => 'dns_record.json',
            'default_ttl' => 300,
        ],
        //'recursive' => [
            
        //]
    ]
];