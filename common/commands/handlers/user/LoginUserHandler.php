<?php

namespace common\commands\handlers\user;

use common\commands\user\LoginUserCommand;

class LoginUserHandler
{
    /**
     * @param LoginUserCommand $command
     * @return \common\models\User
     */
    public function handle(LoginUserCommand $command)
    {
        \Yii::$app->user->login($command->user());

        return $command->user();
    }
}