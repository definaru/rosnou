<?php

namespace common\commands\user;

use common\models\User;
use yii\web\IdentityInterface;

class LogoutUserCommand
{
    /**
     * @var User
     */
    private $user;

    public function __construct(IdentityInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }
}