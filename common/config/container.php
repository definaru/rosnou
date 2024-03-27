<?php

Yii::$container->setSingleton(\domain\user\PasswordHasher::class, \common\components\YiiPasswordHasher::class);