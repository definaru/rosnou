<?php

namespace common\commands\handlers;

use common\commands\RecoverPasswordCommand;
use common\models\VerificationToken;
use domain\token\services\VerificationTokenService;
use domain\token\VerificationTokenManager;
use frontend\rating\components\Exception;
use yii\base\Security;

class RecoverPasswordHandler
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var VerificationTokenService
     */
    private $tokenService;

    public function __construct(Security $security, VerificationTokenService $tokenService)
    {
        $this->security = $security;
        $this->tokenService = $tokenService;
    }

    /**
     * @param RecoverPasswordCommand $command
     * @return \common\models\User
     */
    public function handle(RecoverPasswordCommand $command)
    {
        $token = $this->tokenService->get($command->token(), VerificationToken::TYPE_PASSWORD_RECOVERY);

        $user = $command->user();
        $user->password_hash = md5($command->password()); //$this->security->generatePasswordHash($command->password());
        $user->save();

        $this->tokenService->remove($command->token(), VerificationToken::TYPE_PASSWORD_RECOVERY);

        return $user;
    }
}