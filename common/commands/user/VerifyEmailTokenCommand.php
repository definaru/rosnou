<?php

namespace common\commands\user;

class VerifyEmailTokenCommand
{
    /**
     * @var string
     */
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function token()
    {
        return $this->token;
    }
}