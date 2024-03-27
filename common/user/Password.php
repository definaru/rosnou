<?php

namespace common\user;

class Password
{
    /**
     * @var string
     */
    private $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function hash()
    {
        return md5($this->password);
    }
}