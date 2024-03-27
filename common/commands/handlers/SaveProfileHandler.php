<?php

namespace common\commands\handlers;

use common\models\User;
use domain\user\PasswordHasher;
use yii\base\Security;
use common\commands\SaveProfileCommand;

class SaveProfileHandler
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
     * @param SaveProfileCommand $command
     * @return int
     */
    public function handle(SaveProfileCommand $command)
    {
        return $command->user()->updateAttributes([
            'email' => $command->email(),
            'firstname' => $command->name()->first(),
            'lastname' => $command->name()->last(),
            'middlename' => $command->name()->middle(),
            'orgname' => $command->orgName(),
            'position' => $command->orgPosition(),
            'password_hash' => $this->passwordHasher->hash($command->password()),
        ]);
    }
}