<?php

namespace common\commands\user;

use common\models\User;

class LoginUserCommand
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var bool
     */
    private $remember;

    public function __construct(User $user, bool $remember = false)
    {
        $this->user = $user;
        $this->remember = $remember;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return bool
     */
    public function remember()
    {
        return $this->remember;
    }
}