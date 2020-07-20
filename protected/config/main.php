<?php

//Формируем конфигурацию приложения
$config = [
    'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name' => 'Circle.promo',
    'language' => 'ru',
    'theme' => 'default',
    'defaultController' => 'system',
    'aliases' => [
        'bootstrap' => realpath(__DIR__.'/../extensions/bootstrap'),
        'assetsTheme' => realpath(__DIR__.'/../../themes/default/assets'),
        'vendor' => realpath(__DIR__.'/../vendor'),
        'currency_files' => realpath(__DIR__.'/../../uploads/currency_files'),
        'video' => realpath(__DIR__.'/../../uploads/video'),
    ],
    
    'preload' => YII_DEBUG ? ['log', 'bootstrap', 'debug'] : ['bootstrap', 'debug'],
    
    'import' => [
        'application.behaviors.*',
	'application.components.*',
        'application.helpers.*',
        'application.models.*',
        'application.widgets.*',
        'application.modules.rights.*',
        'application.modules.rights.components.*',
        'application.modules.user.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
    ],
    
    'modules' => [
        'admin' => [],
        'rights' => [
            'userClass' => 'Users',
            'superuserName' => 'Admin',
            'enableBizRule' => true,
            'displayDescription' => true,
            'baseUrl' => '/rights',
            'layout' => 'webroot.themes.default.views.rights.layouts.main',
            'appLayout' => 'webroot.themes.default.views.layouts._client',
            'debug' => YII_DEBUG,
        ],
        'user' => [
            'registrationUrl' => ['/register'],
            'recoveryUrl' => ['/recovery'],
            'returnLogoutUrl' => ['/'],
        ],
        
    ],
    
    'components' => [
        'db' => [
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=localhost;dbname=circle_base',
            'username' => 'root',
            'password' => 'root',
            'tablePrefix' => '',
            'enableProfiling' => YII_DEBUG ? true : false,
            'enableParamLogging' => YII_DEBUG ? true : false,
            'emulatePrepare' => true,
            'charset' => 'utf8',
            'schemaCachingDuration' => YII_DEBUG ? 0 : 0,
        ],
        'bootstrap' => [
            'class' => 'bootstrap.components.Bootstrap',
            'fontawesome' => true,
            'minify' => YII_DEBUG ? false : true
        ],
        'user' => [
            'class' => 'WebUser',
            'allowAutoLogin' => true,
            'identityCookie' => ['domain' => YII_DEBUG ? '.cpromo.rinion.ru' : '.circle.promo'],
            'loginUrl' => '/login',
            'returnUrl' => ['dashboard'],
        ],
        'authManager' => [
            'class' => 'RDbAuthManager',
            'rightsTable' => 'rights',
            'itemTable' => 'rights_authitem',
            'itemChildTable' => 'rights_authitemchild',
            'assignmentTable' => 'rights_authassignment',
        ],
        'request' => [
            'class' => 'MHttpRequest',
            'enableCookieValidation' => true,
            'enableCsrfValidation' => false,
            'csrfTokenName' => 'csrf',
            'noCsrfValidationRoutes' => [
                '^finance/payByPrfmoneyResult.*$',
                '^finance/PayByPrfmoneyResult.*$',
                '^finance/PayByCoinsResult.*$',
                '^finance/payByCoinsResult.*$',
                '^finance/payByPayeerResult.*$'
            ],
        ],
        'urlManager' => [
            'class' => 'MUrlManager',
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => [
                '/' => 'system/index',
                'login' => 'user/login/login',
                'login/checkVerify' => 'user/login/checkVerify',
                'logout' => 'user/logout/logout',
                'register' => 'user/register/register',
                'recovery' => 'user/recovery/recovery',
                'activate' => 'user/activate/activate',
                'profile' => 'user/profile',
                'user/confirmEmail' => 'user/profile/confirmEmail',
                'info/<view:\w+>/*' => 'system/getInfoPage/name/<view>/*',
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<id:\d+>'=>'<module>/<controller>/view',
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
            ],
            
	],
        'cache' => [ 
            'class' => 'system.caching.CDbCache',
            'connectionID' => 'db',
        ],
        'session' => [
            'class' => 'CHttpSession',
            'autoStart' => true,
            'cookieParams' => [
                'domain' => YII_DEBUG ? '.cpromo.rinion.ru' : '.circle.promo',
                'httpOnly' => true,
            ],
            'timeout' => 86400,
        ],
        'mail' => [
            'class' => 'ext.mailer.EMailer',
            'CharSet' => 'UTF-8',
	],
        'settings' => [
            'class' => 'application.components.MSettings'
        ],
        'mobileDetect' => [
            'class' => 'application.extensions.mobileDetect.MobileDetect'
        ],
        'errorHandler' => [
            'errorAction' => 'system/error',
	],
        'ih' => [
            'class' => 'MImageHandler'
        ],
    ],
    
    'params' => [
        'adminEmail' => 'admin@circle.promo',
        'supportEmail' => 'support@circle.promo',
        'debugCoinsEmail' => 'debug_coins@circle.promo',
        'languages' => ['ru' => 'Русский', 'en' => 'English'],
        'defaultLanguage' => 'ru',
        'replyToAddress' => 'no-reply@circle.promo'
    ]
];

//Подгружаем модуль GII и компонент DEBUG в случае, если, включен режим отладки
if(YII_DEBUG) {
    $config['modules']['gii'] = [
        'class' => 'system.gii.GiiModule',
        'password' => 'v40375432', 
        'ipFilters' => YII_DEBUG ? ['127.0.0.1','::1', '91.207.107.206'] : ['91.207.107.206'],
    ];
    $config['components']['debug'] = [
        'class' => 'ext.debug.Yii2Debug',
        'enabled' => true,
        'allowedIPs' => YII_DEBUG ? ['127.0.0.1','::1', '91.207.107.206'] : ['91.207.107.206'],
    ];
}

return $config;