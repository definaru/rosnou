<?php

namespace common\commands;

use common\models\User;

class RecoverPasswordCommand
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $token;

    public function __construct(string $token, User $user, string $password)
    {
        $this->token = $token;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function token()
    {
        return $this->token;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function password()
    {
        return $this->password;
    }
}