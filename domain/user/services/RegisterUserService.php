<?php

namespace domain\user\services;

use common\user\Name;
use common\models\User;
use domain\user\PasswordHasher;

class RegisterUserService
{
    /**
     * @var PasswordHasher
     */
    private $passwordHasher;

    public function __construct(PasswordHasher $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param Name $name
     * @param string $email
     * @param string $login
     * @param string $password
     * @param string $orgName
     * @param string $orgPosition
     * @return User
     */
    public function registerUser(
        Name $name,
        string $email,
        string $password,
        string $orgName,
        string $orgPosition
    ) {
        $user = User::register(
            $name,
            $email,
            $this->passwordHasher->hash($password),
            $orgName,
            $orgPosition
        );

        return $user;
    }
}