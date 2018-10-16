<?php
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'DbDynamic',
    ],
    'defaultRoute' => 'site/dashboard',
    'aliases' => [
        '@helpers' => '@app/helpers'
    ],

    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'nUdM6601oR2qw3GUL62-NtCLq9kD-YXc',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\CommonUser',
            'enableAutoLogin' => false,
        ],
        'DbDynamic' => [
            'class' => 'app\components\DbDynamic'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'view' => [
            'theme' => [
                'baseUrl' => '@web/themes/partnerpay',
                'pathMap' => ['@app/views' => '@app/themes/partnerpay'],

            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/index',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<mid:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'currencyCode' => 'Rs '
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
    'modules' => [
        'bbps' => [
            'class' => 'app\modules\Bbps',

        ],
        'spicejet' => [
            'class' => 'app\modules\spicejet\Module',
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
