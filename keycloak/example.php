<?php

require_once 'vendor/autoload.php';

use RistekUSDI\Kisara\User as KisaraUser;

// First option
$config = [
    'admin_url' => 'http://localhost:8182',
    'base_url' => 'http://localhost:8182',
    'realm' => 'iam-sandbox',
    'client_id' => 'iampanel',
    'client_secret' => 'DAUevRumor35VOBxKYIs06gTUEvKoabd',
];

$data = [
    'firstName' => 'Ishigami',
    'lastName' => 'Senku',
    'email' => 'senku@dr.stone',
    'username' => 'senku',
    'enabled' => true,
    'credentials' => [
        [
            'algorithm' => 'MD5',
            'type' => 'password',
            'hashedSaltedValue' => md5('123456789'),
            'hashIterations' => 0,
            // You may set temporary if you want user to reset their password
            // 'temporary' => true,
        ]
    ],
];

$result = (new KisaraUser($config))->store($data);
print_r($result);
