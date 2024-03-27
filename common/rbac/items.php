<?php

use yii\rbac\Item;

return [
    'guest' => [
        'id' => 1,
        'type' => Item::TYPE_ROLE,
        'description' => 'Гость',
        'children' => ['guest'],
    ],
    'user' => [
        'id' => 5,
        'type' => Item::TYPE_ROLE,
        'description' => 'Пользователь',
        'children' => ['guest'],
    ],
    'expert' => [
        'id' => 10,
        'type' => Item::TYPE_ROLE,
        'description' => 'Эксперт',
        'children' => ['guest'],
    ],
    'moderator' => [
        'id' => 20,
        'type' => Item::TYPE_ROLE,
        'description' => 'Модератор',
        'children' => ['guest'],
    ],
    'admin' => [
        'id' => 99,
        'type' => Item::TYPE_ROLE,
        'description' => 'Админ',
        'children' => ['guest', 'moderator', 'expert'],
    ],
];