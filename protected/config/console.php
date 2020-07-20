<?php

return [
    'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name' => 'Circle.promo',
    'language' => 'ru',
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
            'username' => 'circle_admin',
            'password' => '26c46cae404c6fb0d1e8ed6b628fe6de',
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
            'identityCookie' => ['domain' => YII_DEBUG ? '.circle.loc' : '.circle.promo'],
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
            'enableCsrfValidation' => true,
            'csrfTokenName' => 'csrf',
            'noCsrfValidationRoutes' => [
                '^finance/payByPrfmoneyResult.*$',
                '^finance/PayByPrfmoneyResult.*$',
                '^finance/PayByCoinsResult.*$',
                '^finance/payByCoinsResult.*$',
                '^finance/payByPayeerResult.*$'
            ],
        ],
        'settings' => [
            'class' => 'application.components.MSettings'
        ],
        
    ],
    
   
];