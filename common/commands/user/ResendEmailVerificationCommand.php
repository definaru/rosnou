<?php

namespace common\commands\user;

use common\models\User;

class ResendEmailVerificationCommand
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
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