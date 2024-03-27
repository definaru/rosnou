<?php

namespace common\commands\handlers\user;

use common\models\User;
use common\models\VerificationToken;
use common\commands\user\VerifyEmailTokenCommand;
use domain\token\services\VerificationTokenService;

class VerifyEmailTokenHandler
{
    /**
     * @var VerificationTokenService
     */
    private $tokenService;

    public function __construct(VerificationTokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function handle(VerifyEmailTokenCommand $command)
    {
        $token = $this->tokenService->get($command->token(), VerificationToken::TYPE_EMAIL_VERIFICATION);

        $user = User::findOne(['id' => $token->user_id]);
        $user->emailVerified();
        $user->save();

        $this->tokenService->remove($command->token(), VerificationToken::TYPE_EMAIL_VERIFICATION);
    }
}