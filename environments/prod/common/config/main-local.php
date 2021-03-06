<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=www_justice_plus',
            'username' => 'root',
            'password' => 'xaiTIVP7kB$oHuJecEooq#YsziVvVAzW',
            'charset' => 'utf8',
        ],
        'rabbitMQ' => [
            'class' => \common\components\queue\BasicRabbitMQProducer::class,
            'host' => '127.0.0.1',
            'port' => 5672,
            'user' => 'justice',
            'password' => 'SQuHbc9FJTLqJqMUeTqdmsqORFPWVfWAHyrdJEaU',
            'queueName' => 'justice',
            'exchangeName' => 'justice',
            'exchangeType' => 'topic',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 15,
        ],
        'session' => [
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => '127.0.0.1',
                'port' => 6379,
                'database' => 14,
            ]
        ],
    ],
];
