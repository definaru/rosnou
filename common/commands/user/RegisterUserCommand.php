<?php

namespace common\commands\user;

use common\user\Name;

class RegisterUserCommand
{
    /**
     * @var
     */
    private $email;

    /**
     * @var Name
     */
    private $name;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $orgName;

    /**
     * @var string
     */
    private $orgPosition;

    /**
     * RegisterUserCommand constructor.
     *
     * @param Name $name
     * @param string $email
     * @param string $login
     * @param string $password
     * @param string $orgName
     * @param string $orgPosition
     */
    public function __construct(
        Name $name,
        string $email,
        string $password,
        string $orgName,
        string $orgPosition
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->orgName = $orgName;
        $this->orgPosition = $orgPosition;
    }

    /**
     * @return string
     */
    public function password(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function orgName(): string
    {
        return $this->orgName;
    }

    /**
     * @return string
     */
    public function orgPosition(): string
    {
        return $this->orgPosition;
    }

    /**
     * @return Name
     */
    public function name() : Name
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function email() : string
    {
        return $this->email;
    }
}