<?php

namespace common\commands\handlers\user;

use common\commands\user\LogoutUserCommand;

class LogoutUserHandler
{
    /**
     * @param LogoutUserCommand $command
     * @return bool
     */
    public function handle(LogoutUserCommand $command)
    {
        return \Yii::$app->user->logout();
    }
}