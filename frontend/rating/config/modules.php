<?php

return [
    \common\models\Section::DEFAULT_MODULE => [
        'name' => 'Страница',
        'rules' => [
            'GET /@' => 'pages/static/show'
        ],
    ],
    'news' => [
        'name' => 'Новости',
        'rules' => [
            '/@/<slug>' => 'news/view',
            '/@' => 'news/list',
        ],
    ],
    'criteria' => [
        'name' => 'Критерии',
        'rules' => [
            '/@' => 'pages/static/criteria',
        ],
    ],
    'experts' => [
        'name' => 'Эксперты',
        'rules' => [
            '/@' => 'experts/list',
        ],
    ],
    'uchastniki' => [
    'name' => 'Участники',
    'rules' => [
        '/@' => 'uchastniki/list',
        '/@/<id:\d+>' => 'uchastniki/view',
        '/@/<site_id:\d+>/<period_slug>/getdiplom' => 'uchastniki/diplom',
        //'/@/dimplom/<period_id:\d+>/<site_id:\d+>' => 'uchastniki/diplom',
        ],
    ],
    'rezults' => [
    'name' => 'Результаты',
    'rules' => [
        '/@/leto-2016/<id:\d+>-kategoriya' => 'rezults/redirect2016',
        '/@' => 'rezults/list',
        '/@/<periodSlug>/<typeSlug>' => 'rezults/view',
        ],
    ],
    'map' => [
        'name' => 'Карта участников',
        'rules' => [
            '/@' => 'map/show',
            '/@/udata/content/getMap/1' => 'map/content',
        ],
    ],
];