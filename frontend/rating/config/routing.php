<?php

$rules = [
    '/' => 'pages/static/home',
    '/policy/cookie/' => 'pages/static/policy-cookie',
    '/api/results_by_years/<domain>' => 'export/load-rating-data',
    '/api/periods' => 'export/load-periods-data',
    'GET /api/sites' => 'export/load-sites-data',

    //
    //'/o-rejtinge' => 'pages/rating/index',
    //'/o-rejtinge/istoriya' => 'pages/rating/history',
    //'/o-rejtinge/eksperty' => 'pages/rating/experts',
    //'/o-rejtinge/smi-o-rejtinge' => 'pages/rating/smi',
    //'/o-rejtinge/usloviya-uchastiya' => 'pages/rating/participation',
    //'/o-rejtinge/partnery' => 'pages/rating/partners',
    //
    //'o-rejtinge/usloviya-uchastiya/assigment' => 'pages/rating/assignment',
    //
    //'/kontakty' => 'pages/static/contacts',
    //


    '/uchastniki' => 'uchastniki/list',
    // поиск
    '/search/search_do' => 'search/search/list',

    '/map/udata/content/getMap/1' => 'map/content',
    '/country/subjects/<district>' => 'country/subjects',

    '/users/registrate' => 'users/access/registration',
    '/users/verify_email/<token>' => 'users/access/verify-email-token',
    '/users/registrate_done' => 'users/access/registration-done',
    '/users/login_do' => 'users/access/login',
    '/users/logout' => 'users/access/logout',
    '/users/forget' => 'users/access/password-recover',
    '/users/forget/done' => 'users/access/password-recover-message-sent',
    '/users/forget/token/<token>' => 'users/access/password-recover-token',


    '/users/not-activated' => 'users/access/not-activated',
    '/users/resent-verification-email' => 'users/access/verification-email-resent',

    '/users/profile' => 'users/profile/index',
    '/users/sites' => 'sites/sites/index',
    '/users/sites/create' => 'sites/sites/create',
    '/users/sites/edit/<id>' => 'sites/sites/edit',

    '/users/applications' => 'sites/sites/moderate-list',
    '/users/examination/results/<id:\d+>' => 'users/examination/results',
    '/users/examinations' => 'users/examination/expert-list',
    '/users/examination/begin/<id:\d+>' => 'users/examination/expert-check',
    '/users/applications/edit/<id:\d+>' => 'sites/sites/moderate-edit',
    '/users/examination/self_begin/<id:\d+>' => 'users/examination/index',

    'POST /users/tickets/do' => 'users/examination/add-comment',
    '/udata/users/tickets/<result:\d+>/' => 'users/examination/comments-list',
    'DELETE /udata/users/tickets/del' => 'users/examination/delete-comment',

    '/captcha' => 'home/captcha',
];

$modules = require(__DIR__ . '/modules.php');

return [
    'components' => [
        'urlManager' => [
            'class' => \common\components\UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => $rules,
            'modules' => $modules,
            //'suffix' => '/',
        ],
    ],
];
